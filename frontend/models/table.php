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
defined('_JEXEC') or die;

class JoomSportModelTable extends JModelItem
{
    protected $_context = 'com_joomsport.season';

    public function getObject()
    {
        $jinput = JFactory::getApplication()->input;
        if($jinput->get("jsformat") == "json"){
            $season_id = $jinput->getInt("sid");
            $fileName = JPATH_ROOT.'/media/bearleague/json/season_'.$season_id.'.json';
            if($season_id && is_file($fileName)){
                header('Content-type:application/json;charset=utf-8');
                echo file_get_contents($fileName);
                die();
            }
            
        }
        require_once JPATH_COMPONENT.'/sportleague/sportleague.php';
        //return $this->_item[$pk];
            return $controllerSportLeague;
    }
}