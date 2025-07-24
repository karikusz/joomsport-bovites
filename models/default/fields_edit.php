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

class fields_editJSModel extends JSPRO_Models
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

        $row = new JTableFields($this->db);
        $row->load($is_id);
        $error = $this->db->getErrorMsg();
        if ($error) {
            return JError::raiseError(500, $error);
        }
        $rowOptions = json_decode($row->options, true);
        $is_field[] = JHTML::_('select.option',  0, JText::_('BLBE_PLAYER'), 'id', 't_name');
        $is_field[] = JHTML::_('select.option',  1, JText::_('BLBE_TEAM'), 'id', 't_name');
        $is_field[] = JHTML::_('select.option',  2, JText::_('BLBE_MATCH'), 'id', 't_name');
        $is_field[] = JHTML::_('select.option',  3, JText::_('BLBE_SEASON'), 'id', 't_name');
        $is_field[] = JHTML::_('select.option',  4, JText::_('BLBE_CLUB'), 'id', 't_name');
        $is_field[] = JHTML::_('select.option',  5, JText::_('BLBE_VENUE'), 'id', 't_name');
        $is_field[] = JHTML::_('select.option',  6, JText::_('BLBE_PERSON'), 'id', 't_name');
        $this->_lists['is_type'] = JHTML::_('select.genericlist',   $is_field, 'type', 'class="inputbox" size="1" onchange="tblview_hide();"', 'id', 't_name', $row->type);
        $published = ($row->id) ? $row->published : 1;
        $fdisplay = ($row->id) ? $row->fdisplay : 1;
        $this->_lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $published, JText::_('JPUBLISHED'), JText::_('JUNPUBLISHED'));
        $this->_lists['t_view'] = JHTML::_('select.booleanlist',  'e_table_view', 'class="inputbox"', $row->e_table_view);
        $this->_lists['fdisplay'] = JHTML::_('select.booleanlist',  'fdisplay', 'class="inputbox"', $fdisplay);
        $this->_lists['season_related'] = JHTML::_('select.booleanlist',  'season_related', "class='inputbox' onclick = getClick();", $row->season_related); //javascript:window.alert('{JText::_('SEASRELEXTRA')}');

                //display on player list
                $this->_lists['display_playerlist'] = JHTML::_('select.booleanlist',  'display_playerlist', 'class="inputbox"', $row->display_playerlist);

        $fldtype[] = JHTML::_('select.option',  0, JText::_('BLBE_SELTXTFD'), 'id', 'name');
        $fldtype[] = JHTML::_('select.option',  1, JText::_('BLBE_SELRADB'), 'id', 'name');
        $fldtype[] = JHTML::_('select.option',  2, JText::_('BLBE_TXTAR'), 'id', 'name');
        $fldtype[] = JHTML::_('select.option',  3, JText::_('BLBE_SELBX'), 'id', 'name');
        $fldtype[] = JHTML::_('select.option',  4, JText::_('BLBE_LINK'), 'id', 'name');
        $fldtype[] = JHTML::_('select.option',  5, JText::_('BLBE_PERSON'), 'id', 'name');
        $fldtype[] = JHTML::_('select.option',  6, JText::_('BLBE_DATE'), 'id', 'name');
        $this->_lists['field_type'] = JHTML::_('select.genericlist',   $fldtype, 'field_type', 'class="inputbox" size="1" onchange="shide();"', 'id', 'name', $row->field_type);
        
        
        $fldtype2[] = JHTML::_('select.option',  0, JText::_('BLBE_DATE'), 'id', 'name');
        $fldtype2[] = JHTML::_('select.option',  1, JText::_('BLBE_AGE'), 'id', 'name');
        $fldtype2[] = JHTML::_('select.option',  2, JText::_('BLBE_DATEAGE'), 'id', 'name');
        $this->_lists['dateage'] = JHTML::_('select.genericlist',   $fldtype2, 'options[dateage]', 'class="inputbox" size="1"', 'id', 'name', (isset($rowOptions['dateage'])?$rowOptions['dateage']:''));
        
        
        $query = 'SELECT * FROM #__bl_persons_category ORDER BY name';
        $this->db->setQuery($query);
        $cats = $this->db->loadObjectList();
        $error = $this->db->getErrorMsg();
        if ($error) {
            return JError::raiseError(500, $error);
        }

        $cntr[] = JHTML::_('select.option',  0, JText::_('BLBE_SELECTVALUE'), 'id', 'name');
        $countries = array_merge($cntr, $cats);
        $this->_lists['category'] = JHTML::_('select.genericlist',   $countries, 'person_category', 'class="inputbox" size="1"', 'id', 'name', $row->person_category);
        
        $in_roster = 0;
        if(isset($rowOptions['in_roster'])){
            $in_roster = $rowOptions['in_roster'];
        }

        $this->_lists['in_roster'] = JHTML::_('select.booleanlist',  'in_roster', "class='inputbox'", $in_roster);
        
        
        
        $faccess[] = JHTML::_('select.option',  0, JText::_('BLBE_ALL'), 'id', 'name');
        $faccess[] = JHTML::_('select.option',  1, JText::_('BLBE_SELREGONLY'), 'id', 'name');

        $this->_lists['faccess'] = JHTML::_('select.genericlist',   $faccess, 'faccess', 'class="inputbox" size="1"', 'id', 'name', $row->faccess);

        //----for selectbox----///
        $this->_lists['selval'] = array();

        if ($row->field_type == '3') {
            $query = 'SELECT * FROM #__bl_extra_select WHERE fid='.$row->id.' ORDER BY eordering';
            $this->db->setQuery($query);
            $this->_lists['selval'] = $this->db->loadObjectList();
        }
        
        $this->_lists['languages'] = $this->getLanguages();
        $this->_lists['translation'] = array();
        if(count($this->_lists['languages']) && $row->id){
            $this->_lists['translation'] = $this->getTranslation('fields_'.$row->id);
           
        }
        
        $this->_data = $row;
    }
    public function orderFields()
    {
        $cid = JRequest::getVar('cid', array(), 'post', 'array');
        $order = JRequest::getVar('order', array(), 'post', 'array');
        $row = new JTableFields($this->db);
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
                // remember to reorder this category
            }
        }
    }

    public function saveFields()
    {
        if (!JFactory::getUser()->authorise('core.edit', 'com_joomsport')) {
            return JError::raiseError(303, '');
        }
        $post = JRequest::get('post');
        $post['descr'] = JRequest::getVar('descr', '', 'post', 'string', JREQUEST_ALLOWRAW);
        
        if(isset($_POST['options']) && count($_POST['options'])){
            $postn = $_POST['options'];
            $postn['in_roster'] = $_POST['in_roster'];
            $post['options'] = json_encode($postn);
        }
        
        $row = new JTableFields($this->db);

        if (!empty($post['id'])) {
            $query = 'SELECT season_related FROM #__bl_extra_filds WHERE id='.$post['id'].'';
            $this->db->setQuery($query);
            $is_related = $this->db->loadResult();
            if ($is_related != $post['season_related']) {
                $query = 'DELETE FROM #__bl_extra_values WHERE f_id='.$post['id'];
                $this->db->setQuery($query);
                $this->db->query();
            }
        }

        if (!$row->bind($post)) {
            JError::raiseError(500, $row->getError());
        }
                // make fdisplay=1 because there is no control to edit it
                $row->fdisplay = 1;
        if (!$row->check()) {
            JError::raiseError(500, $row->getError());
        }
        // if new item order last in appropriate group
        if (!$row->store()) {
            JError::raiseError(500, $row->getError());
        }
        $row->checkin();

    //print_R($_POST);die();	

        $mj = 0;
        $mjarr = array();
        $eordering = 0;
        if (isset($_POST['selnames']) && count($_POST['selnames'])) {
            foreach ($_POST['selnames'] as $selname) {
                $selname = $this->db->quote($selname);
                if ($_POST['adeslid'][$mj]) {
                    $this->db->setQuery('UPDATE #__bl_extra_select SET sel_value='.$selname.', eordering='.$eordering.' WHERE id='.$_POST['adeslid'][$mj]);
                } else {
                    $this->db->setQuery('INSERT INTO #__bl_extra_select(fid,sel_value,eordering) VALUES('.$row->id.','.$selname.','.$eordering.')');
                }
                $this->db->query();
                $mjarr[] = $_POST['adeslid'][$mj] ? $_POST['adeslid'][$mj] : $this->db->insertid();
                ++$mj;
                ++$eordering;
            }
        } else {
            $query = 'DELETE FROM #__bl_extra_select WHERE fid='.$row->id;
            $this->db->setQuery($query);
            $this->db->query();
        }

        $query = 'DELETE FROM #__bl_extra_select
		            WHERE fid='.$row->id.' AND id NOT IN ('.(count($mjarr) ? implode(',', $mjarr) : "''").')';

        $this->db->setQuery($query);
        $this->db->query();
        
        //translation
        if(isset($_POST['translation']) && count($_POST['translation'])){
            $this->db->setQuery(
                    "DELETE FROM #__bl_translations WHERE jsfield='fields_".$row->id."'"
                    );
            $this->db->query();
            foreach ($_POST['translation'] as $key => $value) {
                $translation = json_encode($value);
                $translation = nl2br($translation);
                $translation = str_replace("\r\n", "", $translation);
                
                $this->db->setQuery(
                        "INSERT INTO #__bl_translations(jsfield,translation,languageID)"
                        ." VALUES('fields_".$row->id."','".addslashes($translation)."','".$key."')"
                        );
                $this->db->query();
            }
        }
        
        
        $this->_id = $row->id;
    }
}
