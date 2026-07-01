<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

class FileuploaderModelUpload extends BaseDatabaseModel
{
    public function upload()
    {
        $app = Factory::getApplication();
        $input = $app->input;

        $target = $input->getString('target_dir', 'images/fileuploader/uploads');
        $target = Path::clean($target);
        $basePath = JPATH_ROOT . '/' . $target;
        $overwrite = $input->getBool('overwrite', false);

        if (!is_dir($basePath) && !Folder::create($basePath)) {
            return ['type' => 'error', 'message' => Text::_('COM_FILEUPLOADER_ERR_CREATE_TARGET')];
        }

        $files = $input->files->get('files', [], 'array');
        $folder = $input->getString('folder', '');

        if (!empty($folder)) {
            $subPath = Path::clean($basePath . '/' . $folder);
            if (!is_dir($subPath) && !Folder::create($subPath)) {
                return ['type' => 'error', 'message' => Text::_('COM_FILEUPLOADER_ERR_CREATE_FOLDER')];
            }
            $basePath = $subPath;
        }

        $uploaded = [];
        foreach ($files as $file) {
            if (empty($file['name'])) {
                continue;
            }

            if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
                continue;
            }

            $relativePath = $this->extractRelativePath($file['name']);
            $relativeDir = $relativePath['dir'];
            $fileName = $relativePath['name'];
            $destinationDir = $basePath;

            if ($relativeDir !== '') {
                $destinationDir = Path::clean($basePath . '/' . $relativeDir);
                if (!is_dir($destinationDir) && !Folder::create($destinationDir)) {
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

    private function extractRelativePath($name)
    {
        $normalized = str_replace('\\', '/', $name);
        $segments = explode('/', trim($normalized, '/'));
        $fileName = array_pop($segments);
        $dir = implode('/', $segments);

        return ['dir' => $dir, 'name' => $fileName];
    }
}
