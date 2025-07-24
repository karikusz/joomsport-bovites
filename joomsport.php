<?php
/*------------------------------------------------------------------------
# JoomSport Professional 
# ------------------------------------------------------------------------
# BearDev development company 
# Copyright (C) 2011 JoomSport.com. All Rights Reserved.
# @license - http://joomsport.com/news/license.html GNU/GPL
# Websites: http://www.JoomSport.com 
# Technical Support:  Forum - http://joomsport.com/helpdesk/
-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die;

class js_mobile
{
    public static function isMobile()
    {
        return false;
    }
}
if (!JFactory::getUser()->authorise('core.manage', 'com_joomsport')) {
    return JError::raiseError(303, '');
}
jimport('joomla.application.component.controller');
$controller = JRequest::getVar('controller', null, '', 'cmd');
$task = JRequest::getCmd('task');
if (isset($_POST['task']) && $_POST['task'] == 'mp_merge_players') {
    $controller = 'mergeplayers';
}

if ($controller) {
    $path = JPATH_COMPONENT.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.$controller.'.php';

    if (file_exists($path) && $controller) {
        require_once $path;
    } else {
        $controller = '';
        require_once JPATH_COMPONENT.DIRECTORY_SEPARATOR.'controller.php';
    }
    $classname = ucfirst($controller) . 'Controller';
} else {
    require_once 'controller.php';
    $classname = 'JoomSportController';
}


$controller = new $classname();

if (isset($_GET['task']) && $_GET['task'] == 'uploadGallery') {
    $task = 'uploadGallery';
}

$controller->execute($task);
$controller->redirect();
