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

class club_editJSModel extends JSPRO_Models
{
    public $_data = null;
    public $_lists = null;
    public $_mode = 1;
    public $_id = null;
    public function __construct()
    {
        parent::__construct();

        $mainframe = JFactory::getApplication();

        $this->getData();
    }

    public function getData()
    {
        $cid = JRequest::getVar('cid', array(0), '', 'array');
        $is_id = $cid[0];

        $row = new JTableClub($this->db);
        $row->load($is_id);
        $error = $this->db->getErrorMsg();
        if ($error) {
            return JError::raiseError(500, $error);
        }

        $query = 'SELECT * FROM #__bl_teams WHERE club_id = 0 ORDER BY t_name';
        $this->db->setQuery($query);
        $teams_wclub = $this->db->loadObjectList();

        if ($row->id) {
            $query = 'SELECT * FROM #__bl_teams WHERE club_id = "'.$row->id.'" ORDER BY t_name';
            $this->db->setQuery($query);
            $teams_inclub = $this->db->loadObjectList();
        } else {
            $teams_inclub = array();
        }

        $this->_lists['teams'] = @JHTML::_('select.genericlist',   $teams_wclub, 'teams_id', ' size="10" multiple ondblclick="javascript:JS_addSelectedToList(\'adminForm\',\'teams_id\',\'teams_season\');"', 'id', 't_name', 0);
        $this->_lists['teams2'] = @JHTML::_('select.genericlist',   $teams_inclub, 'teams_season[]', ' size="10" multiple ondblclick="javascript:JS_addSelectedToList(\'adminForm\',\'teams_season\',\'teams_id\');"', 'id', 't_name', 0);

        $this->_lists['ext_fields'] = $this->getAdditfields(4, $row->id);

        $this->_lists['photos'] = $this->getPhotos(6, $row->id);

        $this->_lists['post_max_size'] = $this->getValSettingsServ('post_max_size');

        $this->_data = $row;
        
        $this->_lists['languages'] = $this->getLanguages();
        $this->_lists['translation'] = array();
        if(count($this->_lists['languages']) && $row->id){
            $this->_lists['translation'] = $this->getTranslation('club_'.$row->id);
           
        }
    }

    public function saveClub()
    {
        if (!JFactory::getUser()->authorise('core.edit', 'com_joomsport')) {
            return JError::raiseError(303, '');
        }
        $mainframe = JFactory::getApplication();
        $post = JRequest::get('post');
        $post['c_descr'] = JRequest::getVar('c_descr', '', 'post', 'string', JREQUEST_ALLOWRAW);
        //$post['def_img'] = JRequest::getVar( 'c_def_img', 0, 'post', 'int' );
        $post['def_img'] = JRequest::getVar('ph_default', 0, 'post', 'int');
        $row = new JTableClub($this->db);

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

        require_once 'components/com_joomsport/helpers/images.php';
        $default_id = ImagesHelper::saveImgs($_POST['jsgallery'], $row->id, 6);
        $row->def_img = $default_id;
        $row->store();

        $teams_season = JRequest::getVar('teams_season', array(0), '', 'array');

        JArrayHelper::toInteger($teams_season, array(0));
        $this->db->setQuery("UPDATE #__bl_teams SET club_id=0 WHERE club_id={$row->id}");
        $this->db->query();
        if (count($teams_season)) {
            foreach ($teams_season as $teams) {
                if (intval($teams)) {
                    $this->db->setQuery("UPDATE #__bl_teams SET club_id={$row->id} WHERE id={$teams}");
                    $this->db->query();
                }
            }
        }

        if (isset($_POST['extraf']) && count($_POST['extraf'])) {
            foreach ($_POST['extraf'] as $p => $dummy) {
                if (intval($_POST['extra_id'][$p])) {
                    $query = "SELECT season_related FROM `#__bl_extra_filds` WHERE id='".intval($_POST['extra_id'][$p])."'";
                    $this->db->setQuery($query);
                    $season_related = $this->db->loadResult();

                    $db_season = $season_related ? $seasf_id : 0;

                    $query = 'DELETE FROM #__bl_extra_values WHERE f_id = '.$_POST['extra_id'][$p].' AND uid = '.$row->id.' AND season_id='.$db_season;
                    $this->db->setQuery($query);
                    $this->db->query();

                    $_POST['extraf'][$p] = $this->db->quote($_POST['extraf'][$p]);
                    if ($_POST['extra_ftype'][$p] == '2') {
                        $query = 'INSERT INTO #__bl_extra_values(f_id,uid,fvalue_text,season_id) VALUES('.$_POST['extra_id'][$p].','.$row->id.','.$_POST['extraf'][$p].",{$db_season})";
                    } else {
                        $query = 'INSERT INTO #__bl_extra_values(f_id,uid,fvalue,season_id) VALUES('.$_POST['extra_id'][$p].','.$row->id.','.$_POST['extraf'][$p].",{$db_season})";
                    }
                    $this->db->setQuery($query);
                    $this->db->query();
                }
            }
        }

        $this->_id = $row->id;
        
        //translation
        if(isset($_POST['translation']) && count($_POST['translation'])){
            $this->db->setQuery(
                    "DELETE FROM #__bl_translations WHERE jsfield='club_".$row->id."'"
                    );
            $this->db->query();
            foreach ($_POST['translation'] as $key => $value) {
                $value['c_descr'] = str_replace("\r\n", "", $value['c_descr']);
                $translation = json_encode($value);
                $translation = nl2br($translation);
                $translation = str_replace("\r\n", "", $translation);
                
                $this->db->setQuery(
                        "INSERT INTO #__bl_translations(jsfield,translation,languageID)"
                        ." VALUES('club_".$row->id."','".addslashes($translation)."','".$key."')"
                        );
                $this->db->query();
            }
        }
    }
}
