<?php

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

class FileuploaderViewUpload extends HtmlView
{
    public function display($tpl = null)
    {
        $params = ComponentHelper::getParams('com_fileuploader');
        $this->targetDir = $params->get('default_target_dir', 'images/fileuploader/uploads');

        $this->addToolbar();

        return parent::display($tpl);
    }

    protected function addToolbar()
    {
        $title = Text::_('COM_FILEUPLOADER');
        ToolbarHelper::title($title, 'upload');
        ToolbarHelper::custom('upload', 'upload', '', Text::_('COM_FILEUPLOADER_UPLOAD'), false);
    }
}
