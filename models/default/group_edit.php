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

class group_editJSModel extends JSPRO_Models
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
        $sid = $this->_lists['sid'] = JRequest::getVar('sid', 0, '', 'int');
        $row = new JTableGroups($this->db);
        $row->load($is_id);
        $error = $this->db->getErrorMsg();
        if ($error) {
            return JError::raiseError(500, $error);
        }
        $row->id = $row->id ? $row->id : 0;

        $query = 'SELECT t.t_single
			            FROM #__bl_tournament as t, #__bl_seasons as s
			            WHERE s.s_id = '.($sid).' AND s.t_id = t.id';
        $this->db->setQuery($query);
        $tourn = $this->db->loadResult();

        if ($tourn == 1) {
            $query = 'SELECT t.id as id
				            FROM #__bl_season_players as st, #__bl_players as t, #__bl_grteams as gr
				            WHERE gr.t_id = t.id '
                                          .'  AND gr.g_id = '.$row->id.''
                                        .' AND st.season_id = '.$sid.'
				            AND t.id = st.player_id
				            ORDER BY t.first_name';
        } else {
            $query = 'SELECT t.id as id
				            FROM #__bl_season_teams as st, #__bl_teams as t, #__bl_grteams as gr
				            WHERE gr.t_id = t.id 
                                            AND gr.g_id = '.$row->id.' '
                                        .' AND st.season_id = '.$sid.'
				            AND t.id = st.team_id
				            ORDER BY t.t_name';
        }

        $this->db->setQuery($query);
        $teams_season_ids = $this->db->loadColumn();

        if ($tourn == 1) {
            $query = 'SELECT t.id as id
				            FROM #__bl_season_players as st, #__bl_players as t, #__bl_grteams as gr, #__bl_groups as g
				            WHERE gr.t_id = t.id
                                            AND gr.g_id != '.$row->id.'
                                                AND g.id=gr.g_id
				            AND g.s_id=st.season_id AND st.season_id = '.$sid.' AND t.id = st.player_id
				            ORDER BY t.first_name';
        } else {
            $query = 'SELECT t.id as id
				            FROM #__bl_season_teams as st, #__bl_teams as t, #__bl_grteams as gr, #__bl_groups as g
				            WHERE gr.t_id = t.id
                                            AND gr.g_id != '.$row->id.'
                                                AND g.id=gr.g_id
				            AND g.s_id=st.season_id  AND st.season_id = '.$sid.' AND t.id = st.team_id
				            ORDER BY t.t_name';
        }

        $this->db->setQuery($query);
        $teams_season_ogr = $this->db->loadColumn();

        if ($tourn == 1) {
            $query = "SELECT CONCAT(p.first_name,' ',p.last_name) as t_name,p.id
				            FROM #__bl_players as p,#__bl_season_players as st
				            WHERE st.season_id = ".$sid.' AND p.id = st.player_id '
                            .(count($teams_season_ids) ? 'AND p.id NOT IN ('.implode(',', $teams_season_ids).')' : '').' '
                            .(count($teams_season_ogr) ? 'AND p.id NOT IN ('.implode(',', $teams_season_ogr).')' : '').'
                            ORDER BY p.first_name';
        } else {
            $query = 'SELECT * FROM #__bl_teams as t, #__bl_season_teams as st
				            WHERE st.season_id = '.$sid.' AND t.id = st.team_id '
                            .(count($teams_season_ids) ? 'AND t.id NOT IN ('.implode(',', $teams_season_ids).')' : '').' '
                            .(count($teams_season_ogr) ? 'AND t.id NOT IN ('.implode(',', $teams_season_ogr).')' : '').'
                            ORDER BY t.t_name';
        }

        $this->db->setQuery($query);
        $teams = $this->db->loadObjectList();
        $this->_lists['teams'] = @JHTML::_('select.genericlist',   $teams, 'teams_id', 'class="chzn-done" size="10" multiple ondblclick="javascript:JS_addSelectedToList(\'adminForm\',\'teams_id\',\'teams_seasons\');"', 'id', 't_name', 0);

        if ($tourn == 1) {
            $query = "SELECT t.id as id, CONCAT(t.first_name,' ',t.last_name) as t_name
				            FROM #__bl_season_players as st, #__bl_players as t, #__bl_grteams as gr
				            WHERE gr.t_id = t.id
                                            AND gr.g_id = ".$row->id.''
                                        .' AND st.season_id = '.$sid.'
				            AND t.id = st.player_id
				            ORDER BY t.first_name';
        } else {
            $query = 'SELECT t.id as id, t.t_name as t_name
				            FROM #__bl_season_teams as st, #__bl_teams as t, #__bl_grteams as gr
				            WHERE gr.t_id = t.id
                                            AND gr.g_id = '.$row->id.''
                                        .' AND st.season_id = '.$sid.'
				            AND t.id = st.team_id
				            ORDER BY t.t_name';
        }

        $this->db->setQuery($query);
        $teams_season = $this->db->loadObjectList();
        $this->_lists['teams2'] = @JHTML::_('select.genericlist',   $teams_season, 'teams_seasons[]', 'class="chzn-done" size="10" multiple ondblclick="javascript:JS_addSelectedToList(\'adminForm\',\'teams_seasons\',\'teams_id\');"', 'id', 't_name', 0);

        $this->_lists['single'] = $tourn;
        
        $this->_lists['languages'] = $this->getLanguages();
        $this->_lists['translation'] = array();
        if(count($this->_lists['languages']) && $row->id){
            $this->_lists['translation'] = $this->getTranslation('group_'.$row->id);
        }
        
        
        $this->_data = $row;
    }

    public function orderGroup()
    {
        $cid = JRequest::getVar('groupId', array(), 'post', 'array');
        //$order		= JRequest::getVar( 'order', array(), 'post', 'array' );

        $row = new JTableGroups($this->db);
        $total = count($cid);

        if (empty($cid)) {
            return JError::raiseWarning(500, JText::_('No items selected'));
        }
        // update ordering values
        for ($i = 0; $i < $total; ++$i) {
            $row->load((int) $cid[$i]);
            if ($row->ordering != $i) {
                $row->ordering = $i;
                if (!$row->store()) {
                    return JError::raiseError(500, $this->db->getErrorMsg());
                }
            }
        }
    }

    public function saveGroup()
    {
        $post = JRequest::get('post');
        $row = new JTableGroups($this->db);
        if (!$row->bind($post)) {
            JError::raiseError(500, $row->getError());
        }

        $this->db->setQuery('SELECT MAX(ordering) FROM #__bl_groups');
        $ordering = (int) $this->db->loadResult();
        if (!$row->id) {
            $row->ordering = $ordering + 1;
        }
        if (!$row->check()) {
            JError::raiseError(500, $row->getError());
        }
        // if new item order last in appropriate group
        if (!$row->store()) {
            JError::raiseError(500, $row->getError());
        }
        $row->checkin();
        $query = 'DELETE FROM #__bl_grteams WHERE g_id = '.$row->id;
        $this->db->setQuery($query);
        $this->db->query();
        $teams_season = JRequest::getVar('teams_seasons', array(0), '', 'array');
        JArrayHelper::toInteger($teams_season, '');
        if (count($teams_season)) {
            foreach ($teams_season as $teams) {
                $query = 'INSERT INTO #__bl_grteams(g_id,t_id) VALUES('.$row->id.','.$teams.')';
                $this->db->setQuery($query);
                $this->db->query();
            }
        }
        //translation
        if(isset($_POST['translation']) && count($_POST['translation'])){
            $this->db->setQuery(
                    "DELETE FROM #__bl_translations WHERE jsfield='group_".$row->id."'"
                    );
            $this->db->query();
            foreach ($_POST['translation'] as $key => $value) {
                $value['group_name'] = str_replace("\r\n", "", $value['group_name']);
                
                $translation = json_encode($value);
                $translation = nl2br($translation);
                $translation = str_replace("\r\n", "", $translation);
                
                $this->db->setQuery(
                        "INSERT INTO #__bl_translations(jsfield,translation,languageID)"
                        ." VALUES('group_".$row->id."','".addslashes($translation)."','".$key."')"
                        );
                $this->db->query();
            }
        }

        $this->_id = $row->id;
    }
}
