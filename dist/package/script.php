<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filesystem\Path;

class Pkg_FileuploaderInstallerScript
{
    public function install($parent)
    {
        $this->ensureUploadFolder();
    }

    public function update($parent)
    {
        $this->ensureUploadFolder();
    }

    public function postflight($type, $parent)
    {
        $this->ensureUploadFolder();
    }

    private function ensureUploadFolder()
    {
        $folder = Path::clean(JPATH_ROOT . '/images/fileuploader/uploads');

        if (!is_dir($folder) && !Folder::create($folder)) {
            return;
        }

        if (!is_writable($folder)) {
            @chmod($folder, 0755);
        }
    }
}
