<?php

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Session\Session;

class FileuploaderController extends BaseController
{
    public function display($cachable = false, $urlparams = array())
    {
        $viewName = $this->input->getCmd('view', 'upload');
        $viewLayout = $this->input->getCmd('layout', 'default');

        $view = $this->getView($viewName, 'html');
        $view->setModel($this->getModel('Upload', 'FileuploaderModel'), true);
        $view->setLayout($viewLayout);
        $view->display();

        return $this;
    }

    public function upload()
    {
        if (!Session::checkToken()) {
            $this->setRedirect('index.php?option=com_fileuploader', Text::_('JINVALID_TOKEN'), 'error');

            return $this;
        }

        $model = $this->getModel('Upload', 'FileuploaderModel');
        $result = $model->upload();

        $this->setRedirect('index.php?option=com_fileuploader', $result['message'], $result['type']);

        return $this;
    }
}
