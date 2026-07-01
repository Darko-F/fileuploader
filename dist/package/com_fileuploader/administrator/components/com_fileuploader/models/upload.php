<?php

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

class FileuploaderModelUpload extends BaseDatabaseModel
{
    public function upload()
    {
        $app = Factory::getApplication();
        $input = $app->input;
        $params = ComponentHelper::getParams('com_fileuploader');
        $defaultTarget = $params->get('default_target_dir', 'images/fileuploader/uploads');

        $basePath = $this->resolvePath($input->getString('target_dir', $defaultTarget), JPATH_ROOT);

        if ($basePath === false) {
            return ['type' => 'error', 'message' => Text::_('COM_FILEUPLOADER_ERR_INVALID_PATH')];
        }

        $overwrite = $input->getBool('overwrite', false);

        if (!$this->ensureDirectory($basePath)) {
            return ['type' => 'error', 'message' => Text::_('COM_FILEUPLOADER_ERR_CREATE_TARGET')];
        }

        $files = $this->normaliseFiles($input->files->get('files', [], 'array'));
        $folder = $input->getString('folder', '');

        if (!empty($folder)) {
            $subPath = $this->resolvePath($folder, $basePath);

            if ($subPath === false) {
                return ['type' => 'error', 'message' => Text::_('COM_FILEUPLOADER_ERR_INVALID_PATH')];
            }

            if (!$this->ensureDirectory($subPath)) {
                return ['type' => 'error', 'message' => Text::_('COM_FILEUPLOADER_ERR_CREATE_FOLDER')];
            }

            $basePath = $subPath;
        }

        $uploaded = [];
        foreach ($files as $file) {
            if (empty($file['name'])) {
                continue;
            }

            if (isset($file['error']) && (int) $file['error'] !== UPLOAD_ERR_OK) {
                continue;
            }

            if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
                continue;
            }

            $relativePath = $this->extractRelativePath(!empty($file['full_path']) ? $file['full_path'] : $file['name']);

            if ($relativePath === false) {
                return ['type' => 'error', 'message' => Text::_('COM_FILEUPLOADER_ERR_INVALID_PATH')];
            }

            $relativeDir = $relativePath['dir'];
            $fileName = $relativePath['name'];
            $destinationDir = $basePath;

            if ($relativeDir !== '') {
                $destinationDir = $this->resolvePath($relativeDir, $basePath);

                if ($destinationDir === false) {
                    return ['type' => 'error', 'message' => Text::_('COM_FILEUPLOADER_ERR_INVALID_PATH')];
                }

                if (!$this->ensureDirectory($destinationDir)) {
                    return ['type' => 'error', 'message' => Text::_('COM_FILEUPLOADER_ERR_CREATE_FOLDER')];
                }
            }

            $destination = $destinationDir . '/' . $fileName;
            if (!$overwrite && file_exists($destination)) {
                continue;
            }

            if (!move_uploaded_file($file['tmp_name'], $destination)) {
                return ['type' => 'error', 'message' => Text::_('COM_FILEUPLOADER_ERR_UPLOAD')];
            }

            $uploaded[] = $relativeDir !== '' ? $relativeDir . '/' . $fileName : $fileName;
        }

        if (empty($uploaded)) {
            return ['type' => 'warning', 'message' => Text::_('COM_FILEUPLOADER_NO_FILES')];
        }

        return ['type' => 'message', 'message' => Text::sprintf('COM_FILEUPLOADER_SUCCESS', implode(', ', $uploaded))];
    }

    private function ensureDirectory($path)
    {
        if (is_dir($path)) {
            return is_writable($path);
        }

        return mkdir($path, 0755, true);
    }

    private function normaliseFiles($files)
    {
        if (empty($files)) {
            return [];
        }

        if (isset($files['name']) && is_array($files['name'])) {
            $normalised = [];

            foreach ($files['name'] as $index => $name) {
                $normalised[] = [
                    'name' => $name,
                    'type' => $files['type'][$index] ?? '',
                    'tmp_name' => $files['tmp_name'][$index] ?? '',
                    'error' => $files['error'][$index] ?? UPLOAD_ERR_NO_FILE,
                    'size' => $files['size'][$index] ?? 0,
                    'full_path' => $files['full_path'][$index] ?? $name,
                ];
            }

            return $normalised;
        }

        if (isset($files['name'])) {
            return [$files];
        }

        return is_array($files) ? $files : [];
    }

    private function resolvePath($path, $root)
    {
        $relativePath = $this->cleanRelativePath($path, false);

        if ($relativePath === false) {
            return false;
        }

        $root = rtrim($this->cleanAbsolutePath($root), '/');
        $candidate = $this->cleanAbsolutePath($root . '/' . $relativePath);

        if ($candidate !== $root && strpos($candidate . '/', $root . '/') !== 0) {
            return false;
        }

        return $candidate;
    }

    private function cleanAbsolutePath($path)
    {
        $path = str_replace('\\', '/', (string) $path);
        $prefix = '';

        if (preg_match('#^[A-Za-z]:/#', $path) === 1) {
            $prefix = substr($path, 0, 2);
            $path = substr($path, 2);
        }

        $isAbsolute = strpos($path, '/') === 0;
        $segments = [];

        foreach (explode('/', $path) as $segment) {
            if ($segment === '' || $segment === '.') {
                continue;
            }

            if ($segment === '..') {
                array_pop($segments);
                continue;
            }

            $segments[] = $segment;
        }

        $clean = implode('/', $segments);

        if ($isAbsolute) {
            $clean = '/' . $clean;
        }

        return $prefix . $clean;
    }

    private function extractRelativePath($name)
    {
        $relativePath = $this->cleanRelativePath($name, true);

        if ($relativePath === false) {
            return false;
        }

        $segments = explode('/', $relativePath);
        $fileName = $this->makeSafe(array_pop($segments));

        if ($fileName === '') {
            return false;
        }

        $dir = implode('/', $segments);

        return ['dir' => $dir, 'name' => $fileName];
    }

    private function cleanRelativePath($path, $allowFile)
    {
        $path = trim(str_replace('\\', '/', (string) $path));

        if ($path === '' || strpos($path, "\0") !== false) {
            return false;
        }

        $path = trim($path, '/');
        $segments = [];

        foreach (explode('/', $path) as $segment) {
            if ($segment === '' || $segment === '.' || $segment === '..') {
                return false;
            }

            $safeSegment = $this->makeSafe($segment);

            if ($safeSegment === '') {
                return false;
            }

            $segments[] = $safeSegment;
        }

        if (!$allowFile && empty($segments)) {
            return false;
        }

        return implode('/', $segments);
    }

    private function makeSafe($name)
    {
        $name = trim((string) $name);
        $name = preg_replace('#[\\\\/:*?"<>|\x00-\x1F]#', '-', $name);
        $name = preg_replace('#\s+#', ' ', $name);
        $name = trim($name, ". \t\n\r\0\x0B");

        return $name;
    }
}
