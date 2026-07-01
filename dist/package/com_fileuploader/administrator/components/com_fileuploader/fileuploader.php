<?php

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;

$controller = BaseController::getInstance('Fileuploader');
$input = Joomla\CMS\Factory::getApplication()->input;
$task = $input->getCmd('task', 'display');

$controller->execute($task);
$controller->redirect();
