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
// No direct access.
defined('_JEXEC') or die;

require dirname(__FILE__).'/../models.php';

class event_editJSModel extends JSPRO_Models
{
    public $_data = null;
    public $_lists = null;
    public $_mode = 1;
    public $_id = null;
    public function __construct()
    {
        parent::__construct();

        $this->getData();
    }

    public function getData()
    {
        $cid = JRequest::getVar('cid', array(0), '', 'array');
        $is_id = $cid[0];

        $row = new JTableEvents($this->db);
        $row->load($is_id);
        $error = $this->db->getErrorMsg();
        if ($error) {
            return JError::raiseError(500, $error);
        }

        $javascript = 'onChange = "View_eventimg();"';
        $this->_lists['image'] = JHTML::_('list.images',  'e_img', $row->e_img, $javascript, 'media/bearleague/events');

        $jas = 'onChange = "calctpfun();"';
        $is_tourn[] = JHTML::_('select.option',  0, JText::_('BLBE_MATCH'), 'id', 'name');
        $is_tourn[] = JHTML::_('select.option',  1, JText::_('BLBE_PLAYER'), 'id', 'name');
        $is_tourn[] = JHTML::_('select.option',  2, JText::_('BLBE_SUM'), 'id', 'name');

        $this->_lists['player_event'] = JHTML::_('select.genericlist',   $is_tourn, 'player_event', 'class="inputbox" size="1" '.$jas, 'id', 'name', $row->player_event);

        $is_rt[] = JHTML::_('select.option',  0, JText::_('BLBE_SUM'), 'id', 'name');
        $is_rt[] = JHTML::_('select.option',  1, JText::_('BLBE_AVG'), 'id', 'name');

        $this->_lists['restype'] = JHTML::_('select.genericlist',   $is_rt, 'result_type', 'class="inputbox" size="1"', 'id', 'name', $row->result_type);

        $is_sumev[] = JHTML::_('select.option',  0, JText::_('BLBE_SELEVENT'), 'id', 'name');
        $query = "SELECT e_name as name,id FROM #__bl_events WHERE player_event='1' AND result_type='0' ORDER BY e_name";
        $this->db->setQuery($query);
        $evns = $this->db->loadObjectList();
        if (count($evns)) {
            $is_sumev = array_merge($is_sumev, $evns);
        }
        $this->_lists['sumev1'] = JHTML::_('select.genericlist',   $is_sumev, 'sumev1', 'class="inputbox" size="1" ', 'id', 'name', $row->sumev1);
        $this->_lists['sumev2'] = JHTML::_('select.genericlist',   $is_sumev, 'sumev2', 'class="inputbox" size="1" ', 'id', 'name', $row->sumev2);
        $this->_lists['post_max_size'] = $this->getValSettingsServ('post_max_size');
        
        $this->_lists['languages'] = $this->getLanguages();
        $this->_lists['translation'] = array();
        if(count($this->_lists['languages']) && $row->id){
            $this->_lists['translation'] = $this->getTranslation('events_'.$row->id);
           
        }
        
        //subevents
        //$is_sumev[] = JHTML::_('select.option',  0, JText::_('BLBE_SELEVENT'), 'id', 'name');
        
        $dependson = $row->dependson?json_decode($row->dependson, true):array();
        
        $query = "SELECT e.e_name as name,e.id"
                . " FROM #__bl_events as e"
                . " JOIN #__bl_events_depending as de ON de.event_id = e.id"
                . " WHERE de.subevent_id = ".intval($row->id).""
                . " ORDER BY e.e_name";
        $this->db->setQuery($query);
        $evnsSelected = $this->db->loadObjectList();
        
        $query = "SELECT e.e_name as name,e.id"
                . " FROM #__bl_events as e"
                . " LEFT JOIN #__bl_events_depending as de ON de.event_id = e.id"
                . " WHERE e.player_event='1' AND e.result_type='0' AND e.dependson=''"
                . " AND de.id IS NULL AND e.id != ".intval($row->id).""
                . " ORDER BY e.e_name";
        $this->db->setQuery($query);
        $evns = $this->db->loadObjectList();
        
        if(count($evnsSelected)){
            if(count($evns)){
                $evns = array_merge($evnsSelected, $evns);
            }else{
                $evns = $evnsSelected;
            }
        }
        
        $this->_lists['dependson'] = JHTML::_('select.genericlist',   $evns, 'dependson[]', 'class="inputbox" size="1" multiple', 'id', 'name', $dependson);
        
        
        $this->_data = $row;
    }

    public function orderEvent()
    {
        $cid = JRequest::getVar('cid', array(), 'post', 'array');
        $order = JRequest::getVar('order', array(), 'post', 'array');

        $row = new JTableEvents($this->db);
        $total = count($cid);

        if (empty($cid)) {
            return JError::raiseWarning(500, JText::_('No items selected'));
        }
        // update ordering values
        for ($i = 0; $i < $total; ++$i) {
            $row->load((int) $cid[$i]);
            if ($row->ordering != $order[$i]) {
                $row->ordering = $order[$i];
                if (!$row->store()) {
                    return JError::raiseError(500, $this->db->getErrorMsg());
                }
            }
        }
    }

    public function saveEvent()
    {
        if (!JFactory::getUser()->authorise('core.edit', 'com_joomsport')) {
            return JError::raiseError(303, '');
        }
        $post = JRequest::get('post');
        $post['e_descr'] = JRequest::getVar('e_descr', '', 'post', 'string', JREQUEST_ALLOWRAW);
        
        $dependson = JRequest::getVar('dependson', array(), 'post', 'array');
        $post["dependson"] = count($dependson)?json_encode($dependson):'';
        
        
        //var_dump($post);die();
        $row = new JTableEvents($this->db);
        $error = $this->db->getErrorMsg();
        if ($error) {
            return JError::raiseError(500, $error);
        }
        if ($this->isPostedImage()) {
            if (false !== ($filename = $this->uploadEventImage())) {
                $post['e_img'] = $filename;
                if (is_file(JPATH_ROOT.'/media/bearleague/thumb/'.$filename)) {
                    unlink(JPATH_ROOT.'/media/bearleague/thumb/'.$filename);
                }
            }
        }
        if (!$row->bind($post)) {
            JError::raiseError(500, $row->getError());
        }
        
        if (!$row->check()) {
            JError::raiseError(500, $row->getError());
        }
        // if new item order last in appropriate group
        if (!$row->store()) {
            JError::raiseError(500, $row->getError());
        }
        $row->checkin();
        
        
        //translation
        if(isset($_POST['translation']) && count($_POST['translation'])){
            $this->db->setQuery(
                    "DELETE FROM #__bl_translations WHERE jsfield='events_".$row->id."'"
                    );
            $this->db->query();
            foreach ($_POST['translation'] as $key => $value) {
                $translation = json_encode($value);
                $translation = nl2br($translation);
                $translation = str_replace("\r\n", "", $translation);
                
                $this->db->setQuery(
                        "INSERT INTO #__bl_translations(jsfield,translation,languageID)"
                        ." VALUES('events_".$row->id."','".addslashes($translation)."','".$key."')"
                        );
                $this->db->query();
            }
        }
        
        $this->_id = $row->id;
        
        $query = "DELETE FROM #__bl_events_depending WHERE subevent_id = ".$row->id;
        $this->db->setQuery($query);
        $this->db->query();
        if(count($dependson)){
            foreach($dependson as $dp){
                $query = "INSERT INTO #__bl_events_depending(subevent_id,event_id)"
                        . " VALUES(".$row->id.",".intval($dp).")";
                $this->db->setQuery($query);
                $this->db->query();
            }
        }
        
    }

    public function deleteEvent($cid)
    {
        if (count($cid)) {
            $cids = implode(',', $cid);
            $this->db->setQuery('DELETE FROM #__bl_events WHERE id IN ('.$cids.')');
            $this->db->query();
            
            $this->db->setQuery('DELETE FROM #__bl_events_depending WHERE event_id IN ('.$cids.')');
            $this->db->query();
            
            $this->db->setQuery('DELETE FROM #__bl_events_depending WHERE subevent_id IN ('.$cids.')');
            $this->db->query();
            
            $this->db->setQuery('DELETE FROM #__bl_match_events WHERE e_id IN ('.$cids.')');
            $this->db->query();
            $error = $this->db->getErrorMsg();
            if ($error) {
                return JError::raiseError(500, $error);
            }
        }
    }

    protected function isPostedImage()
    {
        return isset($_FILES['new_event_img']['name'])
            && !empty($_FILES['new_event_img']['tmp_name']);
    }

    protected function uploadEventImage()
    {
        $pathinfo = pathinfo($_FILES['new_event_img']['name']);
        $baseDir = $this->getEventsImagesDir();

        $sameNameImgsCnt = 0;
        $filename = $pathinfo['basename'];
        while (is_file($baseDir.$filename)) {
            ++$sameNameImgsCnt;
            $filename = sprintf('%s_%d.%s',
                $pathinfo['filename'],
                $sameNameImgsCnt,
                $pathinfo['extension']
            );
        }

        if ($this->uploadFile(
            $_FILES['new_event_img']['tmp_name'],
            $filename,
            $baseDir
        )) {
            return $filename;
        }

        return false;
    }

    public function getEventsImagesDir()
    {
        return JPATH_ROOT.'/media/bearleague/events/';
    }
}
