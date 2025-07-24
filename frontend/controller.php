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


jimport('joomla.application.component.controller');

class JoomsportController extends JControllerLegacy
{
    protected $js_prefix = '';
    protected $mainframe = null;
    protected $option = 'com_joomsport';

    public function __construct()
    {
        parent::__construct();
    }

    public function display($cachable = false, $urlparams = false)
    {
        $db = JFactory::getDBO();
        //load languages for addons
        $query = "SELECT options FROM #__bl_addons WHERE published='1' AND options != ''";
        $db->setQuery($query);
        $addons = $db->loadColumn();
        $html = '';
        for($intA=0;$intA<count($addons);$intA++){
            $options = json_decode($addons[$intA], true);
            if(isset($options['langugesFE'])){
                $lang = JFactory::getLanguage();
                $extension = $options['langugesFE'];

                $reload = true;
                $lang->load($extension);
            }
        }   
        //end lang
        
        $vName = $this->input->getCmd('view', '');
        if (!$vName) {
            $vName = $this->input->getCmd('task', 'seasonlist');
        }
        $this->input->set('view', $vName);
        parent::display($cachable);

        return $this;
    }
        /*public function plugins(){
            require_once 'components/com_joomsport/sportleague/sportleague.php';
            require_once JS_PATH_CLASSES . 'class-jsport-plugins.php';
            $arguments = $_GET;
            $plugfunc = (isset($_GET["plugfunc"]) && $_GET["plugfunc"]) ? $_GET["plugfunc"] : "";
            
            if($plugfunc){
                classJsportPlugins::get($plugfunc, $arguments);
            }
        }*/

        public function getMatchStat(){
            require_once 'components/com_joomsport/sportleague/sportleague.php';
            require_once JS_PATH_MODELS.'model-jsport-match.php';

            $match_id = JRequest::getVar('match_id', 0, 'post', 'int');
            $obj = new modelJsportMatch($match_id);
            $obj->getPlayerEvents();

            $intU = 0;
            if ($obj->lists['m_events_home']) {
                foreach ($obj->lists['m_events_home'] as $row) {
                    if (($row->playerid)) {
                        $objT = new classJsportPlayer($row->playerid, 0);
                        $objEvent = new classJsportEvent($row->id);
                        $obj->lists['m_events_home'][$intU]->objEvent = $objEvent;
                        $obj->lists['m_events_home'][$intU]->obj = $objT->getRowSimple();
                        ++$intU;
                    }
                }
            }
            $intU = 0;
            if ($obj->lists['m_events_away']) {
                foreach ($obj->lists['m_events_away'] as $row) {
                    if (($row->playerid)) {
                        $objT = new classJsportPlayer($row->playerid, 0);
                        $objEvent = new classJsportEvent($row->id);
                        $obj->lists['m_events_away'][$intU]->objEvent = $objEvent;
                        $obj->lists['m_events_away'][$intU]->obj = $objT->getRowSimple();
                        ++$intU;
                    }
                }
            }
            $max_count = count($obj->lists['m_events_home']) > count($obj->lists['m_events_away'])?count($obj->lists['m_events_home']):count($obj->lists['m_events_away']);
            if($max_count){
                echo '<div class="jsTTContainer">';
                echo '<table class="table">';
                for($intA=0;$intA<$max_count;$intA++){
                    echo "<tr>";
                    if(isset($obj->lists['m_events_home'][$intA])){
                        echo "<td>";
                        echo $obj->lists['m_events_home'][$intA]->minutes ? $obj->lists['m_events_home'][$intA]->minutes . "'" : '';
                        echo "</td>";
                        echo "<td>";
                        echo $obj->lists['m_events_home'][$intA]->objEvent->getEmblem(false);
                        echo "</td>";
                        echo "<td>";
                        echo $obj->lists['m_events_home'][$intA]->obj->getName(false);
                        echo "</td>";
                    }else{
                        echo "<td colspan='3'>&nbsp;</td>";
                    }
                    if(isset($obj->lists['m_events_away'][$intA])){
                        echo "<td>";
                        echo $obj->lists['m_events_away'][$intA]->minutes ? $obj->lists['m_events_away'][$intA]->minutes . "'" : '';
                        echo "</td>";
                        echo "<td>";
                        echo $obj->lists['m_events_away'][$intA]->objEvent->getEmblem(false);
                        echo "</td>";
                        echo "<td>";
                        echo $obj->lists['m_events_away'][$intA]->obj->getName(false);
                        echo "</td>";
                    }else{
                        echo "<td colspan='3'>&nbsp;</td>";
                    }
                    echo "</tr>";
                }
                echo '</table>';
                echo '</div>';
            }
            exit();
        }
}
