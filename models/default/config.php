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
require_once JPATH_ROOT.'/components/com_joomsport/includes/utils.php';

class configJSModel extends JSPRO_Models
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
        $this->_lists['date_format'] = $this->getJS_Config('date_format');

        $is_data = array();

        $is_data[] = JHTML::_('select.option', '%d-%m-%Y %H:%M', 'd-m-Y H:M', 'id', 'name');
        $is_data[] = JHTML::_('select.option', '%d.%m.%Y %H:%M', 'd.m.Y H:M', 'id', 'name');
        $is_data[] = JHTML::_('select.option', '%Y.%m.%d %H:%M', 'Y.m.d H:M', 'id', 'name');
        $is_data[] = JHTML::_('select.option', '%m-%d-%Y %I:%M %p', 'm-d-Y I:M p', 'id', 'name');
        $is_data[] = JHTML::_('select.option', '%m %B, %Y %H:%M', 'm B, Y H:M', 'id', 'name');
        $is_data[] = JHTML::_('select.option', '%m %B, %Y %I:%H %p', 'm B, Y I:H p', 'id', 'name');
        $is_data[] = JHTML::_('select.option', '%d-%m-%Y', 'd-m-Y', 'id', 'name');
        $is_data[] = JHTML::_('select.option', '%A %d %B, %Y  %H:%M', 'A d B, Y  H:M', 'id', 'name');
        $is_data[] = JHTML::_('select.option', 'd-m-Y  hH:M', 'd-m-Y  hH:M', 'id', 'name');

        $this->_lists['data_sel'] = JHTML::_('select.genericlist',   $is_data, 'date_format', 'class="inputbox" size="1"', 'id', 'name', $this->_lists['date_format']);

        $this->_lists['yteam_color'] = $this->getJS_Config('yteam_color');

        $query = "SELECT * FROM #__bl_extra_filds WHERE type='0' AND season_related='0' AND published='1' ORDER BY ordering";
        $this->db->setQuery($query);
        $this->_lists['adf_player'] = $this->db->loadObjectList();
        $error = $this->db->getErrorMsg();
        if ($error) {
            return JError::raiseError(500, $error);
        }

        $query = "SELECT * FROM #__bl_extra_filds WHERE type='1' AND season_related='0' ORDER BY ordering";
        $this->db->setQuery($query);
        $this->_lists['adf_team'] = $this->db->loadObjectList();

        $this->_lists['enbl_player_system_num'] = $this->getJS_Config('enbl_player_system_num');
        
        //Player Country registration
        $this->_lists['country_reg'] = $this->getJS_Config('country_reg');
        $this->_lists['country_reg_rq'] = $this->getJS_Config('country_reg_rq');
        //Nick registration
        $this->_lists['nick_reg'] = $this->getJS_Config('nick_reg');
        $this->_lists['nick_reg_rq'] = $this->getJS_Config('nick_reg_rq');
        //Match comments
        $this->_lists['mcomments'] = $this->getJS_Config('mcomments');
        //Player registration
        $this->_lists['player_reg'] = $this->getJS_Config('player_reg');
        //team registration
        $this->_lists['team_reg'] = $this->getJS_Config('team_reg');

        $this->_lists['show_playertab'] = $this->getJS_Config('show_playertab');

        //
        $this->_lists['moder_addplayer'] = $this->getJS_Config('moder_addplayer');
        $pllist_order = $this->getJS_Config('pllist_order');
        $pllist_order_se = $this->getJS_Config('pllist_order_se');//SELECT
        $pllistpage_order = $this->getJS_Config('pllistpage_order');

        $query = "SELECT name, CONCAT(id,'_1') as id FROM #__bl_extra_filds WHERE type='0' AND (field_type = 0 OR field_type = 3) ORDER BY ordering";
        $this->db->setQuery($query);
        $adf = $this->db->loadObjectList();
        $alltmp[] = JHTML::_('select.option', 0, JTEXT::_('Name'), 'id', 'name');
        $alltmp[] = JHTML::_('select.option', -1, JTEXT::_('BLBE_LASTNAME'), 'id', 'name');
        /*UPDATE*/
        $alltmp1 = array();
        if (count($adf)) {
            $alltmp1 = array_merge($alltmp1, $adf);
        }

        $query = "SELECT CONCAT(ev.id,'_2') as id,ev.e_name as name
		            FROM #__bl_events as ev WHERE ev.player_event IN (1, 2)
		            ORDER BY ev.e_name";
        $this->db->setQuery($query);
        $events_cd = $this->db->loadObjectList();
        $error = $this->db->getErrorMsg();
        if ($error) {
            return JError::raiseError(500, $error);
        }

        if ($events_cd) {
            $alltmp1 = array_merge($alltmp1, $events_cd);
        }
        ////my sort-------------------->
        function mySort($f1, $f2)
        {
            return strcasecmp($f1->name, $f2->name);
        }
        usort($alltmp1, 'mySort');

        $alltmp = array_merge($alltmp, $alltmp1);
//NEW SELECT

        $query = "SELECT name, CONCAT(id,'_1') as id FROM #__bl_extra_filds
		            WHERE type='0' AND field_type = 3
		            ORDER BY ordering";
        $this->db->setQuery($query);
        $adf_se = $this->db->loadObjectList();

        $alltmpse[] = JHTML::_('select.option', 0, JTEXT::_('Name'), 'id', 'name');

        $alltmp_se = array();
        if (count($adf_se)) {
            $alltmp_se = array_merge($alltmp_se, $adf_se);
        }
        $alltmp_se = array_merge($alltmpse, $alltmp_se);

/////////	

        $this->_lists['pllist_order'] = JHTML::_('select.genericlist',   $alltmp, 'pllist_order', 'class="inputbox" size="1"', 'id', 'name', $pllist_order);

//SELECT!!!!!!!
        $this->_lists['pllist_order_se'] = JHTML::_('select.genericlist',   $alltmp_se, 'pllist_order_se', 'class="inputbox" size="1"', 'id', 'name', $pllist_order_se);
        //print_r($alltmp_se);
//////////////		
        //width logo
        $this->_lists['teamlogo_height'] = $this->getJS_Config('teamlogo_height');

        //account limits
        $this->_lists['teams_per_account'] = $this->getJS_Config('teams_per_account');
        $this->_lists['players_per_account'] = $this->getJS_Config('players_per_account');

        //venue
        $unbl_venue = $this->getJS_Config('unbl_venue');

        $is_data_venue[] = JHTML::_('select.option', '0', JText::_('BLBE_VENUE_OPTION_LOCATION'), 'id', 'name');
        $is_data_venue[] = JHTML::_('select.option', '1', JText::_('BLBE_VENUE_OPTION_VENUE'), 'id', 'name');
        $is_data_venue[] = JHTML::_('select.option', '2', JText::_('JTOOLBAR_DISABLE'), 'id', 'name');

        $this->_lists['unbl_venue'] = JHTML::_('select.genericlist',   $is_data_venue, 'unbl_venue', 'class="inputbox" size="1"', 'id', 'name', $unbl_venue);

        $this->_lists['cal_venue'] = $this->getJS_Config('cal_venue');

        //played matches
        $this->_lists['played_matches'] = $this->getJS_Config('played_matches');
        //display name - nick
        $player_name = $this->getJS_Config('player_name');

        $is_data = array();

        $is_data[] = JHTML::_('select.option', '0', JText::_('BLBE_FNLN'), 'id', 'name');
        $is_data[] = JHTML::_('select.option', '1', JText::_('BLBE_NICKNAME'), 'id', 'name');
        $is_data[] = JHTML::_('select.option', '2', JText::_('BLBE_NAME'), 'id', 'name');
        $is_data[] = JHTML::_('select.option', '3', JText::_('BLBE_PLAYER_SHORTNAME'), 'id', 'name');

        $this->_lists['player_name'] = JHTML::_('select.genericlist',   $is_data, 'player_name', 'class="inputbox" size="1"', 'id', 'name', $player_name);
        ///esport invites
        $esport_invite_player = $this->getJS_Config('esport_invite_player');

        $is_data = array();

        $is_data[] = JHTML::_('select.option', '0', JText::_('BLBE_MODERADDPL'), 'id', 'name');
        $is_data[] = JHTML::_('select.option', '1', JText::_('BLBE_MODERINVITEPL'), 'id', 'name');

        $this->_lists['esport_invite_player'] = JHTML::_('select.genericlist',   $is_data, 'esport_invite_player', 'class="inputbox" size="1" style="width:250px;"', 'id', 'name', $esport_invite_player);
        //invite confirm
        $this->_lists['esport_invite_confirm'] = $this->getJS_Config('esport_invite_confirm');
        //invite unregistered
        $this->_lists['esport_invite_unregister'] = $this->getJS_Config('esport_invite_unregister');
        //
        $this->_lists['esport_join_team'] = $this->getJS_Config('esport_join_team');
        //invite to match
        $this->_lists['esport_invite_match'] = $this->getJS_Config('esport_invite_match');
        ///admin rights
        $this->_lists['jssa_editplayer'] = $this->getJS_Config('jssa_editplayer');

        $this->_lists['jssa_editplayer_single'] = $this->getJS_Config('jssa_editplayer_single');

        $this->_lists['jssa_deleteplayers'] = $this->getJS_Config('jssa_deleteplayers');

        $this->_lists['jssa_deleteplayers_single'] = $this->getJS_Config('jssa_deleteplayers_single');

        $this->_lists['jssa_editteam'] = $this->getJS_Config('jssa_editteam');
        $this->_lists['jssa_delteam'] = $this->getJS_Config('jssa_delteam');

        $knock_style = $this->getJS_Config('knock_style');
        $is_data_v[] = JHTML::_('select.option', '0', JText::_('BLBE_VIEWHOR'), 'id', 'name');
        $is_data_v[] = JHTML::_('select.option', '1', JText::_('BLBE_VIEWVER'), 'id', 'name');

        $this->_lists['knock_style'] = JHTML::_('select.genericlist',   $is_data_v, 'knock_style', 'class="inputbox" size="1"', 'id', 'name', $knock_style);

                //get profile link
                $display_profile = $this->getJS_Config('display_profile');
        $is_data_profile[] = JHTML::_('select.option', 'joomsport', JText::_('JoomSport Player'), 'id', 'name');
        $is_data_profile[] = JHTML::_('select.option', 'cb', JText::_('Community Builder Profile'), 'id', 'name');
        $is_data_profile[] = JHTML::_('select.option', 'jsocial', JText::_('JomSocial Profile'), 'id', 'name');

        $this->_lists['display_profile'] = JHTML::_('select.genericlist',   $is_data_profile, 'display_profile', 'class="inputbox" size="1"', 'id', 'name', $display_profile);

                //

                $this->_lists['set_emblemhgonmatch'] = $this->getJS_Config('set_emblemhgonmatch');
        $this->_lists['set_defimgwidth'] = $this->getJS_Config('set_defimgwidth');
        $set_teampgplayertabVal = $this->getJS_Config('set_teampgplayertab');
        $set_teampgplayertab[] = JHTML::_('select.option', '0', JText::_('BLBE_PLTABCHOICE_STAT'), 'id', 'name');
        $set_teampgplayertab[] = JHTML::_('select.option', '1', JText::_('BLBE_PLTABCHOICE_PHOTO'), 'id', 'name');

        $this->_lists['set_teampgplayertab'] = JHTML::_('select.genericlist',   $set_teampgplayertab, 'set_teampgplayertab', 'class="inputbox" size="1"', 'id', 'name', $set_teampgplayertabVal);

        //social buttons
        $this->_lists['jsb_twitter'] = $this->getJS_Config('jsb_twitter');
        $this->_lists['jsb_gplus'] = $this->getJS_Config('jsb_gplus');
        $this->_lists['jsb_fbshare'] = $this->getJS_Config('jsb_fbshare');
        $this->_lists['jsb_fblike'] = $this->getJS_Config('jsb_fblike');
        $this->_lists['jsbp_season'] = $this->getJS_Config('jsbp_season');
        $this->_lists['jsbp_team'] = $this->getJS_Config('jsbp_team');
        $this->_lists['jsbp_player'] = $this->getJS_Config('jsbp_player');
        $this->_lists['jsbp_match'] = $this->getJS_Config('jsbp_match');
        $this->_lists['jsbp_venue'] = $this->getJS_Config('jsbp_venue');

                //team layout
                $this->_lists['tlb_position'] = $this->getJS_Config('tlb_position');
        $this->_lists['tlb_form'] = $this->getJS_Config('tlb_form');
        $this->_lists['tlb_latest'] = $this->getJS_Config('tlb_latest');
        $this->_lists['tlb_next'] = $this->getJS_Config('tlb_next');

        //add existing team for season admin
        $this->_lists['jssa_addexteam'] = $this->getJS_Config('jssa_addexteam');
/*UPDATE*/
        $this->_lists['jssa_addexteam_single'] = $this->getJS_Config('jssa_addexteam_single');

        $this->_lists['player_team_reg'] = $this->getJS_Config('player_team_reg');

        $this->_lists['jsmr_mark_played'] = $this->getJS_Config('jsmr_mark_played');
        $this->_lists['jsmr_editresult_yours'] = $this->getJS_Config('jsmr_editresult_yours');
        $this->_lists['jsmr_editresult_opposite'] = $this->getJS_Config('jsmr_editresult_opposite');
        $this->_lists['jsmr_edit_playerevent_yours'] = $this->getJS_Config('jsmr_edit_playerevent_yours');
        $this->_lists['jsmr_edit_playerevent_opposite'] = $this->getJS_Config('jsmr_edit_playerevent_opposite');
        $this->_lists['jsmr_edit_matchevent_yours'] = $this->getJS_Config('jsmr_edit_matchevent_yours');
        $this->_lists['jsmr_edit_matchevent_opposite'] = $this->getJS_Config('jsmr_edit_matchevent_opposite');
        $this->_lists['jsmr_edit_squad_yours'] = $this->getJS_Config('jsmr_edit_squad_yours');
        $this->_lists['jsmr_edit_squad_opposite'] = $this->getJS_Config('jsmr_edit_squad_opposite');

                //team settings
                $this->_lists['enbl_teamlinks'] = $this->getJS_Config('enbl_teamlinks');
        $this->_lists['enbl_teamlogolinks'] = $this->getJS_Config('enbl_teamlogolinks');
        $this->_lists['enbl_teamhgllinks'] = $this->getJS_Config('enbl_teamhgllinks');
                //player settings
                $this->_lists['enbl_playerlinks'] = $this->getJS_Config('enbl_playerlinks');
        $this->_lists['enbl_playerlogolinks'] = $this->getJS_Config('enbl_playerlogolinks');

        $this->_lists['enbl_mdnameoncalendar'] = $this->getJS_Config('enbl_mdnameoncalendar');

        //autoreg
        $this->_lists['autoreg_player'] = JHTML::_('select.booleanlist',  'autoreg_player', 'class="inputbox"', $this->getJS_Config('autoreg_player'));
        $this->_lists['reg_lastname'] = $this->getJS_Config('reg_lastname');
        $this->_lists['reg_lastname_rq'] = $this->getJS_Config('reg_lastname_rq');

        //brand
        $this->_lists['jsbrand_on'] = $this->getJS_Config('jsbrand_on');
        $this->_lists['jsbrand_epanel_image'] = $this->getJS_Config('jsbrand_epanel_image');
        
        
        // Custom fields: team city, etc.
        $customFields = JS_Utils::getCustomFields();
        $this->_lists['cf_team_city'] = count($customFields)
            ? $customFields['team_city']
            : array('enabled' => false, 'required' => false);

        $this->_lists['moder_create_match'] = $this->getJS_Config('moder_create_match');

                //
                $this->_lists['mday_fields'] = json_decode($this->getJS_Config('mday_fields'));

        $query = "SELECT ef.*
		            FROM #__bl_extra_filds as ef
		            
		            WHERE ef.published=1 AND ef.type='2' AND ef.field_type!='2'
		            ORDER BY ef.ordering";
        $this->db->setQuery($query);
        $this->_lists['mday_extra'] = $this->db->loadObjectList();

                //teams
                $query = 'SELECT id,t_name FROM #__bl_teams '
                        ." WHERE t_yteam = '0'"
                        .' ORDER BY t_name';

        $this->db->setQuery($query);

        $teams = $this->db->loadObjectList();

        $error = $this->db->getErrorMsg();
        if ($error) {
            return JError::raiseError(500, $error);
        }

        $this->_lists['teams'] = @JHTML::_('select.genericlist',   $teams, 'teams_id', ' size="10" multiple ondblclick="javascript:JS_addSelectedToList(\'adminForm\',\'teams_id\',\'your_teams_id\');"', 'id', 't_name', 0);

        $query = 'SELECT id,t_name FROM #__bl_teams '
                        ." WHERE t_yteam = '1'"
                        .' ORDER BY t_name';

        $this->db->setQuery($query);

        $yteams = $this->db->loadObjectList();
        $error = $this->db->getErrorMsg();
        if ($error) {
            return JError::raiseError(500, $error);
        }

        $this->_lists['yteams'] = @JHTML::_('select.genericlist',   $yteams, 'your_teams_id[]', ' size="10" multiple ondblclick="javascript:JS_addSelectedToList(\'adminForm\',\'your_teams_id\',\'teams_id\');"', 'id', 't_name', 0);
        $this->_lists['highlight_team'] = $this->getJS_Config('highlight_team');
        $this->_lists['enbl_club'] = $this->getJS_Config('enbl_club');

        $this->db->setQuery('SELECT * FROM #__bl_match_statuses ORDER BY ordering');
        $this->_lists['mstatuses'] = $this->db->loadObjectList();

        $this->_lists['enbl_linktoplayerlist'] = $this->getJS_Config('enbl_linktoplayerlist');
        $this->_lists['enbl_linktoplayerlistcal'] = $this->getJS_Config('enbl_linktoplayerlistcal');
        $this->_lists['enbl_calmatchsearch'] = $this->getJS_Config('enbl_calmatchsearch');
        
        //calendar layout
        $is_data = array();

        $is_data[] = JHTML::_('select.option', '0', JText::_('BLBE_CALV_ALLMATCHES'), 'id', 'name');
        $is_data[] = JHTML::_('select.option', '1', JText::_('BLBE_CALV_BYMDAY'), 'id', 'name');

        $this->_lists['jscalendar_theme'] = JHTML::_('select.genericlist',   $is_data, 'jscalendar_theme', 'class="inputbox" size="1" style="width:250px;"', 'id', 'name', $this->getJS_Config('jscalendar_theme'));
        
        //time line options
        $this->_lists['jstimeline'] = $this->getJS_Config('jstimeline');
        
        //career block
        $this->_lists['jsblock_career_enable'] = 0;
        $this->_lists['jsblock_career_fields_selected'] = array();
        $jsblock_career = $this->getJS_Config('jsblock_career');
        if($jsblock_career){
            $jsB = json_decode($jsblock_career,true);
            if($jsB["enable"]){
                $this->_lists['jsblock_career_enable'] = $jsB["enable"];
                $this->_lists['jsblock_career_fields_selected'] = $jsB["options"];
            }
            
        }
        
        $this->db->setQuery("SELECT CONCAT('ev_',id) as id,e_name as name FROM #__bl_events WHERE player_event != 0 ORDER BY ordering");
        $events = $this->db->loadObjectList();
        $is_data = array();

        $is_data[] = JHTML::_('select.option', 'op_mplayed', JText::_('BLBE_CAREER_PLAYED'), 'id', 'name');
        $is_data[] = JHTML::_('select.option', 'op_mlineup', JText::_('BLBE_CAREER_LINEUP'), 'id', 'name');
        $is_data[] = JHTML::_('select.option', 'op_minutes', JText::_('BLBE_CAREER_PLAYEDMINUTES'), 'id', 'name');
        $is_data[] = JHTML::_('select.option', 'op_subsin', JText::_('BLBE_CAREER_SUBSIN'), 'id', 'name');
        $is_data[] = JHTML::_('select.option', 'op_subsout', JText::_('BLBE_CAREER_SUBSOUT'), 'id', 'name');
        if(!empty($events)){
           $is_data = array_merge($is_data, $events);
        }
        
        $this->_lists['jsblock_career_fields'] = JHTML::_('select.genericlist',   $is_data, 'jsblock_career[options][]', 'class="inputbox" multiple="multiple" size="1" style="width:250px;" id="jsblock_career_options"', 'id', 'name', $this->_lists['jsblock_career_fields_selected']);
        $this->_lists['jsblock_matchstat'] = $this->getJS_Config('jsblock_matchstat');
        
        
        //box score
        $query = "SELECT name, id"
                . " FROM #__bl_extra_filds"
                . " WHERE type='0' AND field_type = 3"
                . " ORDER BY name";
        $this->db->setQuery($query);
        $boxpos = $this->db->loadObjectList();
        
        $boxpos_bulk[] = JHTML::_('select.option', 0, JText::_('BLBE_SELECTIONNO'),"id","name");
        if($boxpos){
            $boxpos_bulk = array_merge($boxpos_bulk,$boxpos);
        }
        $this->_lists['boxExtraField'] = JHTML::_('select.genericlist',   $boxpos_bulk, 'boxExtraField', 'class="inputbox"', 'id', 'name', $this->getJS_Config('boxExtraField'));
        
        
        //kickout events
        $kev = $this->getJS_Config('kick_events');
        if($kev){
            $kev = json_decode($kev,true);
        }
        $this->db->setQuery("SELECT id,e_name as name FROM #__bl_events WHERE player_event = 1");
        $player_events = $this->db->loadObjectList();
        $this->_lists['kick_events'] = JHTML::_('select.genericlist',   $player_events, 'kick_events[]', 'class="inputbox" multiple', 'id', 'name', $kev);
        
        //opposite events
        $opev = $this->getJS_Config('opposite_events');
        if($opev){
            $opev = json_decode($opev, true);
        }
        $this->_lists['opposite_events'] = JHTML::_('select.genericlist',   $player_events, 'opposite_events[]', 'class="inputbox" multiple', 'id', 'name', $opev);
        
        //multilanguage
        $this->_lists['multilanguage'] = $this->getJS_Config('multilanguage');
        //js_livematches
        $this->_lists['js_livematches'] = $this->getJS_Config('js_livematches');
        
        //rostertab
        
        $this->_lists['show_playerstattab'] = $this->getJS_Config('show_playerstattab');
        $this->_lists['show_rostertab'] = $this->getJS_Config('show_rostertab');
        
        $query = "SELECT name, id FROM #__bl_extra_filds
		            WHERE type='0' AND field_type = 3
		            ORDER BY ordering";
        $this->db->setQuery($query);
        $adf_group = $this->db->loadObjectList();
        $adf_group_select = array();
        $adf_group_select[] = JHTML::_('select.option', 0, JTEXT::_('BLBE_SELECT'), 'id', 'name');

        
        if (count($adf_group)) {
            $adf_group_select = array_merge($adf_group_select, $adf_group);
        }
        $this->_lists['set_teampgplayertab_groupby'] = JHTML::_('select.genericlist',   $adf_group_select, 'set_teampgplayertab_groupby', 'class="inputbox"', 'id', 'name', $this->getJS_Config('set_teampgplayertab_groupby'));
        
        $query = "SELECT name, id FROM #__bl_extra_filds
		            WHERE type='0' AND field_type = 0
		            ORDER BY ordering";
        $this->db->setQuery($query);
        $adf_group = $this->db->loadObjectList();
        $adf_group_select = array();
        $adf_group_select[] = JHTML::_('select.option', 0, JTEXT::_('BLBE_SELECT'), 'id', 'name');
        if($this->_lists['enbl_player_system_num']){
            $adf_group_select[] = JHTML::_('select.option', -1, JTEXT::_('BLBE_SYSTEM_PLAYER_NUMBER_VAL'), 'id', 'name');
        }
        if (count($adf_group)) {
            $adf_group_select = array_merge($adf_group_select, $adf_group);
        }
        $this->_lists['set_playerfieldnumber'] = JHTML::_('select.genericlist',   $adf_group_select, 'set_playerfieldnumber', 'class="inputbox"', 'id', 'name', $this->getJS_Config('set_playerfieldnumber'));
        
        $query = "SELECT name, id FROM #__bl_extra_filds
		            WHERE type='0'
		            ORDER BY ordering";
        $this->db->setQuery($query);
        $adf_group = $this->db->loadObjectList();
        $adf_group_select = array();
        $adf_group_select[] = JHTML::_('select.option', 0, JTEXT::_('BLBE_SELECT'), 'id', 'name');

        
        if (count($adf_group)) {
            $adf_group_select = array_merge($adf_group_select, $adf_group);
        }
        
        $adf_group_select[] = JHTML::_('select.option', -1, JTEXT::_('BLBE_COUNTRY'), 'id', 'name');
        
        $this->_lists['set_playercardef'] = JHTML::_('select.genericlist',   $adf_group_select, 'set_playercardef', 'class="inputbox"', 'id', 'name', $this->getJS_Config('set_playercardef'));
        
        
        $this->_lists['enbl_mdname_on_match'] = JHTML::_('select.booleanlist',  'enbl_mdname_on_match', 'class="inputbox"', $this->getJS_Config('enbl_mdname_on_match'));
        
        //h2h

        $this->_lists['enbl_match_analytics_block'] = $this->getJS_Config('enbl_match_analytics_block');
        $evArr = json_decode($this->getJS_Config('avgevents_events'),true);

        $query = "SELECT CONCAT(ev.id) as id,ev.e_name as name
		            FROM #__bl_events as ev
		            ORDER BY ev.ordering,ev.player_event DESC,ev.e_name";
        $this->db->setQuery($query);
        $events_avg = $this->db->loadObjectList();

        $this->_lists['avgevents_events'] = JHTML::_('select.genericlist', $events_avg,  'avgevents_events[]', 'class="inputbox" multiple','id','name', $evArr);

        ///squad
        $query = "SELECT name, id FROM #__bl_extra_filds
		            WHERE type='0' AND field_type IN('0','3') AND published='1'
		            ORDER BY ordering";
        $this->db->setQuery($query);
        $plsquadf = $this->db->loadObjectList();

        $adf_group_select = array();
        $adf_group_select[] = JHTML::_('select.option', 0, JTEXT::_('BLBE_SELECT'), 'id', 'name');
        if($this->_lists['enbl_player_system_num']){
            $adf_group_select[] = JHTML::_('select.option', -1, JTEXT::_('BLBE_SYSTEM_PLAYER_NUMBER_VAL'), 'id', 'name');
        }
        if (count($plsquadf)) {
            $adf_group_select = array_merge($adf_group_select, $plsquadf);
        }
        $this->_lists['jsmatch_squad_firstcol'] = JHTML::_('select.genericlist',   $adf_group_select, 'jsmatch_squad_firstcol', 'class="inputbox"', 'id', 'name', $this->getJS_Config('jsmatch_squad_firstcol'));
        $this->_lists['jsmatch_squad_lastcol'] = JHTML::_('select.genericlist',   $adf_group_select, 'jsmatch_squad_lastcol', 'class="inputbox"', 'id', 'name', $this->getJS_Config('jsmatch_squad_lastcol'));

        //match tooltip
        $this->_lists['enbl_matchtooltip'] = $this->getJS_Config('enbl_matchtooltip');

        $this->_lists['enbl_playerlinks_hglteams'] = $this->getJS_Config('enbl_playerlinks_hglteams');


        $this->_lists['pllist_order'] = JHTML::_('select.genericlist',   $alltmp, 'pllist_order', 'class="inputbox" size="1"', 'id', 'name', $pllist_order);


        //
        $this->_lists['pllistpage_order'] = JHTML::_('select.genericlist',   $alltmp, 'pllistpage_order', 'class="inputbox" size="1"', 'id', 'name', $pllistpage_order);


    }

    public function saveConfig()
    {
        if (!JFactory::getUser()->authorise('core.edit', 'com_joomsport')) {
            return JError::raiseError(303, '');
        }
        $date_format = JRequest::getVar('date_format', '', 'post', 'string');
        $yteam_color = JRequest::getVar('yteam_color', '', 'post', 'string');
        $display_profile = JRequest::getVar('display_profile', '', 'post', 'string');

        $nick_reg = JRequest::getVar('nick_reg', 0, 'post', 'int');
        $nick_reg_rq = JRequest::getVar('nick_reg_rq', 0, 'post', 'int');
        $country_reg = JRequest::getVar('country_reg', 0, 'post', 'int');
        $country_reg_rq = JRequest::getVar('country_reg_rq', 0, 'post', 'int');
        $mcomments = JRequest::getVar('mcomments', 0, 'post', 'int');
        $player_reg = JRequest::getVar('player_reg', 0, 'post', 'int');
        $team_reg = JRequest::getVar('team_reg', 0, 'post', 'int');
        $moder_addplayer = JRequest::getVar('moder_addplayer', 0, 'post', 'int');
        $pllist_order = JRequest::getVar('pllist_order', 0, 'post', 'string');
        $pllistpage_order = JRequest::getVar('pllistpage_order', 0, 'post', 'string');

        $pllist_order_se = JRequest::getVar('pllist_order_se', 0, 'post', 'string');//SELECT
        $teamlogo_height = JRequest::getVar('teamlogo_height', 0, 'post', 'int');
        $teams_per_account = JRequest::getVar('teams_per_account', 0, 'post', 'int');
        $players_per_account = JRequest::getVar('players_per_account', 0, 'post', 'int');
        $unbl_venue = JRequest::getVar('unbl_venue', 0, 'post', 'int');
        $cal_venue = JRequest::getVar('cal_venue', 0, 'post', 'int');
        $played_matches = JRequest::getVar('played_matches', 0, 'post', 'int');
        $player_name = JRequest::getVar('player_name', 0, 'post', 'int');
        $esport_invite_player = JRequest::getVar('esport_invite_player', 0, 'post', 'int');
        $esport_invite_confirm = JRequest::getVar('esport_invite_confirm', 0, 'post', 'int');
        $esport_invite_unregister = JRequest::getVar('esport_invite_unregister', 0, 'post', 'int');
        $esport_join_team = JRequest::getVar('esport_join_team', 0, 'post', 'int');
        $jssa_editplayer = JRequest::getVar('jssa_editplayer', 0, 'post', 'int');
        $jssa_editplayer_single = JRequest::getVar('jssa_editplayer_single', 0, 'post', 'int');
        $jssa_deleteplayers = JRequest::getVar('jssa_deleteplayers', 0, 'post', 'int');
        $jssa_deleteplayers_single = JRequest::getVar('jssa_deleteplayers_single', 0, 'post', 'int');
        $esport_invite_match = JRequest::getVar('esport_invite_match', 0, 'post', 'int');
        $knock_style = JRequest::getVar('knock_style', 0, 'post', 'int');

        $jsb_twitter = JRequest::getVar('jsb_twitter', 0, 'post', 'int');
        $jsb_gplus = JRequest::getVar('jsb_gplus', 0, 'post', 'int');
        $jsb_fbshare = JRequest::getVar('jsb_fbshare', 0, 'post', 'int');
        $jsb_fblike = JRequest::getVar('jsb_fblike', 0, 'post', 'int');
        $jsbp_season = JRequest::getVar('jsbp_season', 0, 'post', 'int');
        $jsbp_team = JRequest::getVar('jsbp_team', 0, 'post', 'int');
        $jsbp_player = JRequest::getVar('jsbp_player', 0, 'post', 'int');
        $jsbp_match = JRequest::getVar('jsbp_match', 0, 'post', 'int');
        $jsbp_venue = JRequest::getVar('jsbp_venue', 0, 'post', 'int');

        //team layout
                $tlb_position = JRequest::getVar('tlb_position', 0, 'post', 'int');
        $tlb_form = JRequest::getVar('tlb_form', 0, 'post', 'int');
        $tlb_latest = JRequest::getVar('tlb_latest', 0, 'post', 'int');
        $tlb_next = JRequest::getVar('tlb_next', 0, 'post', 'int');

        $jssa_editteam = JRequest::getVar('jssa_editteam', 0, 'post', 'int');
        $jssa_delteam = JRequest::getVar('jssa_delteam', 0, 'post', 'int');

        $jssa_addexteam = JRequest::getVar('jssa_addexteam', 0, 'post', 'int');
/*UPDATE*/$jssa_addexteam_single = JRequest::getVar('jssa_addexteam_single', 0, 'post', 'int');
        $player_team_reg = JRequest::getVar('player_team_reg', 0, 'post', 'int');

        $autoreg_player = JRequest::getVar('autoreg_player', 0, 'post', 'int');
        $reg_lastname = JRequest::getVar('reg_lastname', 0, 'post', 'int');
        $reg_lastname_rq = JRequest::getVar('reg_lastname_rq', 0, 'post', 'int');

        $jsbrand_on = JRequest::getVar('jsbrand_on', 0, 'post', 'int');
        //$jsbrand_epanel_image = JRequest::getVar( 't_logo', '', 'post', 'string' );
        $istlogo = JRequest::getVar('istlogo', 0, 'post', 'int');

        $jsmr_mark_played = JRequest::getVar('jsmr_mark_played', 0, 'post', 'int');
        $jsmr_editresult_yours = JRequest::getVar('jsmr_editresult_yours', 0, 'post', 'int');
        $jsmr_editresult_opposite = JRequest::getVar('jsmr_editresult_opposite', 0, 'post', 'int');
        $jsmr_edit_playerevent_yours = JRequest::getVar('jsmr_edit_playerevent_yours', 0, 'post', 'int');
        $jsmr_edit_playerevent_opposite = JRequest::getVar('jsmr_edit_playerevent_opposite', 0, 'post', 'int');
        $jsmr_edit_matchevent_yours = JRequest::getVar('jsmr_edit_matchevent_yours', 0, 'post', 'int');
        $jsmr_edit_matchevent_opposite = JRequest::getVar('jsmr_edit_matchevent_opposite', 0, 'post', 'int');
        $jsmr_edit_squad_yours = JRequest::getVar('jsmr_edit_squad_yours', 0, 'post', 'int');
        $jsmr_edit_squad_opposite = JRequest::getVar('jsmr_edit_squad_opposite', 0, 'post', 'int');
        $highlight_team = JRequest::getVar('highlight_team', 0, 'post', 'int');
        $enbl_club = JRequest::getVar('enbl_club', 0, 'post', 'int');
        $show_playertab = JRequest::getVar('show_playertab', 0, 'post', 'int');
        $enbl_calmatchsearch = JRequest::getVar('enbl_calmatchsearch', 0, 'post', 'int');
        $enbl_teamlinks = JRequest::getVar('enbl_teamlinks', 0, 'post', 'int');
        $enbl_teamlogolinks = JRequest::getVar('enbl_teamlogolinks', 0, 'post', 'int');
        $enbl_teamhgllinks = JRequest::getVar('enbl_teamhgllinks', 0, 'post', 'int');
        $enbl_playerlinks = JRequest::getVar('enbl_playerlinks', 0, 'post', 'int');
        $enbl_playerlogolinks = JRequest::getVar('enbl_playerlogolinks', 0, 'post', 'int');
        $enbl_mdnameoncalendar = JRequest::getVar('enbl_mdnameoncalendar', 0, 'post', 'int');

        
        $set_emblemhgonmatch = JRequest::getVar('set_emblemhgonmatch', 0, 'post', 'int');
        $set_defimgwidth = JRequest::getVar('set_defimgwidth', 0, 'post', 'int');
        //$set_teampgplayertab = JRequest::getVar('set_teampgplayertab', 0, 'post', 'int');
        //
        $jscalendar_theme = JRequest::getVar('jscalendar_theme', 0, 'post', 'int');
        $jsblock_matchstat = JRequest::getVar('jsblock_matchstat', 0, 'post', 'int');
        $enbl_player_system_num = JRequest::getVar('enbl_player_system_num', 0, 'post', 'int');
        $query = "UPDATE #__bl_config SET cfg_value='".($enbl_player_system_num)."' WHERE cfg_name='enbl_player_system_num'";
        $this->db->setquery($query);
        $this->db->query();

        //multilanguage
        $multilanguage = JRequest::getVar('multilanguage', 0, 'post', 'int');
        $query = "UPDATE #__bl_config SET cfg_value='".($multilanguage)."' WHERE cfg_name='multilanguage'";
        $this->db->setquery($query);
        $this->db->query();
        
        //js_livematches
        $js_livematches = JRequest::getVar('js_livematches', 0, 'post', 'int');
        $query = "UPDATE #__bl_config SET cfg_value='".($js_livematches)."' WHERE cfg_name='js_livematches'";
        $this->db->setquery($query);
        $this->db->query();
        
        //kick events
        $kick_events = JRequest::getVar('kick_events', array(), 'post', 'array');
        $query = "UPDATE #__bl_config SET cfg_value='".json_encode($kick_events)."' WHERE cfg_name='kick_events'";
        $this->db->setquery($query);
        $this->db->query();
        
         //opposite events
        $opposite_events = JRequest::getVar('opposite_events', array(), 'post', 'array');
        $query = "UPDATE #__bl_config SET cfg_value='".json_encode($opposite_events)."' WHERE cfg_name='opposite_events'";
        $this->db->setquery($query);
        $this->db->query();
        
        //boxscore
        $boxExtraField = JRequest::getVar('boxExtraField', 0, 'post', 'int');
        $query = "UPDATE #__bl_config SET cfg_value='".$boxExtraField."' WHERE cfg_name='boxExtraField'";
        $this->db->setquery($query);
        $this->db->query();
        
        $query = "UPDATE #__bl_config SET cfg_value='".$jsblock_matchstat."' WHERE cfg_name='jsblock_matchstat'";
        $this->db->setquery($query);
        $this->db->query();

        $query = "UPDATE #__bl_config SET cfg_value='".$jscalendar_theme."' WHERE cfg_name='jscalendar_theme'";
        $this->db->setquery($query);
        $this->db->query();

        $query = "UPDATE #__bl_config SET cfg_value='".$set_emblemhgonmatch."' WHERE cfg_name='set_emblemhgonmatch'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$set_defimgwidth."' WHERE cfg_name='set_defimgwidth'";
        $this->db->setquery($query);
        $this->db->query();
        /*$query = "UPDATE #__bl_config SET cfg_value='".$set_teampgplayertab."' WHERE cfg_name='set_teampgplayertab'";
        $this->db->setquery($query);
        $this->db->query();*/

        $query = "UPDATE #__bl_config SET cfg_value='".$enbl_mdnameoncalendar."' WHERE cfg_name='enbl_mdnameoncalendar'";
        $this->db->setquery($query);
        $this->db->query();
                //player settings
                $query = "UPDATE #__bl_config SET cfg_value='".$enbl_playerlinks."' WHERE cfg_name='enbl_playerlinks'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$enbl_playerlogolinks."' WHERE cfg_name='enbl_playerlogolinks'";
        $this->db->setquery($query);
        $this->db->query();
                //team settings
                $query = "UPDATE #__bl_config SET cfg_value='".$enbl_teamlinks."' WHERE cfg_name='enbl_teamlinks'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$enbl_teamlogolinks."' WHERE cfg_name='enbl_teamlogolinks'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$enbl_teamhgllinks."' WHERE cfg_name='enbl_teamhgllinks'";
        $this->db->setquery($query);
        $this->db->query();

        $query = "UPDATE #__bl_config SET cfg_value='".$enbl_calmatchsearch."' WHERE cfg_name='enbl_calmatchsearch'";
        $this->db->setquery($query);
        $this->db->query();

        $query = "UPDATE #__bl_config SET cfg_value='".$show_playertab."' WHERE cfg_name='show_playertab'";
        $this->db->setquery($query);
        $this->db->query();

        $query = "UPDATE #__bl_config SET cfg_value='".$enbl_club."' WHERE cfg_name='enbl_club'";
        $this->db->setquery($query);
        $this->db->query();

        $enbl_linktoplayerlist = JRequest::getVar('enbl_linktoplayerlist', 0, 'post', 'int');
        $query = "UPDATE #__bl_config SET cfg_value='".$enbl_linktoplayerlist."' WHERE cfg_name='enbl_linktoplayerlist'";
        $this->db->setquery($query);
        $this->db->query();

        $enbl_linktoplayerlistcal = JRequest::getVar('enbl_linktoplayerlistcal', 0, 'post', 'int');
        $query = "UPDATE #__bl_config SET cfg_value='".$enbl_linktoplayerlistcal."' WHERE cfg_name='enbl_linktoplayerlistcal'";
        $this->db->setquery($query);
        $this->db->query();

        $query = "UPDATE #__bl_config SET cfg_value='".$highlight_team."' WHERE cfg_name='highlight_team'";
        $this->db->setquery($query);
        $this->db->query();
        if ($highlight_team) {
            $yteams = JRequest::getVar('your_teams_id', array(), 'post', 'array');
            $query = "UPDATE #__bl_teams SET t_yteam='0'";
            $this->db->setquery($query);
            $this->db->query();
            if (count($yteams)) {
                $query = "UPDATE #__bl_teams SET t_yteam='1'"
                                .' WHERE id IN ('.implode(',', $yteams).')';
                $this->db->setquery($query);
                $this->db->query();
            }
        }
        
        $jsblock_career = JRequest::getVar('jsblock_career', array(), 'post', 'array');
        if(strlen(json_encode($jsblock_career)) < 255){ 
        $query = "UPDATE #__bl_config SET cfg_value='".json_encode($jsblock_career)."' WHERE cfg_name='jsblock_career'";
        $this->db->setquery($query);
        $this->db->query();
        }
        
        $mdf = JRequest::getVar('mdf', array(), 'post', 'array');
        $query = "UPDATE #__bl_config SET cfg_value='".json_encode($mdf)."' WHERE cfg_name='mday_fields'";
        $this->db->setquery($query);
        $this->db->query();

        $moder_create_match = JRequest::getVar('moder_create_match', 0, 'post', 'int');
        $query = "UPDATE #__bl_config SET cfg_value='".$moder_create_match."' WHERE cfg_name='moder_create_match'";
        $this->db->setquery($query);
        $this->db->query();

        $query = "UPDATE #__bl_config SET cfg_value='".$jsmr_mark_played."' WHERE cfg_name='jsmr_mark_played'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$jsmr_editresult_yours."' WHERE cfg_name='jsmr_editresult_yours'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$jsmr_editresult_opposite."' WHERE cfg_name='jsmr_editresult_opposite'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$jsmr_edit_playerevent_yours."' WHERE cfg_name='jsmr_edit_playerevent_yours'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$jsmr_edit_playerevent_opposite."' WHERE cfg_name='jsmr_edit_playerevent_opposite'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$jsmr_edit_matchevent_yours."' WHERE cfg_name='jsmr_edit_matchevent_yours'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$jsmr_edit_matchevent_opposite."' WHERE cfg_name='jsmr_edit_matchevent_opposite'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$jsmr_edit_squad_yours."' WHERE cfg_name='jsmr_edit_squad_yours'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$jsmr_edit_squad_opposite."' WHERE cfg_name='jsmr_edit_squad_opposite'";
        $this->db->setquery($query);
        $this->db->query();

        if (!$istlogo) {
            $jsbrand_epanel_image = '';
            $query = "UPDATE #__bl_config SET cfg_value='".$jsbrand_epanel_image."' WHERE cfg_name='jsbrand_epanel_image'";
            $this->db->setquery($query);
            $this->db->query();
        }
        if (isset($_FILES['t_logo']['name']) && $_FILES['t_logo']['tmp_name'] != '' && isset($_FILES['t_logo']['tmp_name'])) {
            $ext = pathinfo($_FILES['t_logo']['name']);
            $bl_filename = 'bl'.time().rand(0, 3000).'.'.$ext['extension'];
            $bl_filename = str_replace(' ', '', $bl_filename);
            //echo $bl_filename;
             if ($this->uploadFile($_FILES['t_logo']['tmp_name'], $bl_filename)) {
                 $jsbrand_epanel_image = '/media/bearleague/'.$bl_filename;
                 $query = "UPDATE #__bl_config SET cfg_value='".$jsbrand_epanel_image."' WHERE cfg_name='jsbrand_epanel_image'";
                 $this->db->setquery($query);
                 $this->db->query();
             }
        }

        $query = "UPDATE #__bl_config SET cfg_value='".$jsbrand_on."' WHERE cfg_name='jsbrand_on'";
        $this->db->setquery($query);
        $this->db->query();

        $query = "UPDATE #__bl_config SET cfg_value='".$jssa_editteam."' WHERE cfg_name='jssa_editteam'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$jssa_delteam."' WHERE cfg_name='jssa_delteam'";
        $this->db->setquery($query);
        $this->db->query();

        $query = "UPDATE #__bl_config SET cfg_value='".$date_format."' WHERE cfg_name='date_format'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$yteam_color."' WHERE cfg_name='yteam_color'";
        $this->db->setquery($query);
        $this->db->query();

        $query = "UPDATE #__bl_config SET cfg_value='".$nick_reg."' WHERE cfg_name='nick_reg'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$nick_reg_rq."' WHERE cfg_name='nick_reg_rq'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$country_reg."' WHERE cfg_name='country_reg'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$country_reg_rq."' WHERE cfg_name='country_reg_rq'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$mcomments."' WHERE cfg_name='mcomments'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$player_reg."' WHERE cfg_name='player_reg'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$team_reg."' WHERE cfg_name='team_reg'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$moder_addplayer."' WHERE cfg_name='moder_addplayer'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$pllist_order."' WHERE cfg_name='pllist_order'";
        $this->db->setquery($query);
        $this->db->query();

        $query = "UPDATE #__bl_config SET cfg_value='".$pllistpage_order."' WHERE cfg_name='pllistpage_order'";
        $this->db->setquery($query);
        $this->db->query();


//select
        $query = "UPDATE #__bl_config SET cfg_value='".$pllist_order_se."' WHERE cfg_name='pllist_order_se'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$teamlogo_height."' WHERE cfg_name='teamlogo_height'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$teams_per_account."' WHERE cfg_name='teams_per_account'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$players_per_account."' WHERE cfg_name='players_per_account'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$unbl_venue."' WHERE cfg_name='unbl_venue'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$cal_venue."' WHERE cfg_name='cal_venue'";
        $this->db->setquery($query);
        $this->db->query();
        //$query = "UPDATE #__bl_config SET cfg_value='".$played_matches."' WHERE cfg_name='played_matches'";
        //$this->db->setquery($query);
        //$this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$player_name."' WHERE cfg_name='player_name'";
        $this->db->setquery($query);
        $this->db->query();
        //esport invite
        $query = "UPDATE #__bl_config SET cfg_value='".$esport_invite_player."' WHERE cfg_name='esport_invite_player'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$esport_invite_confirm."' WHERE cfg_name='esport_invite_confirm'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$esport_invite_unregister."' WHERE cfg_name='esport_invite_unregister'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$esport_join_team."' WHERE cfg_name='esport_join_team'";
        $this->db->setquery($query);
        $this->db->query();
        ///admin rights
        $query = "UPDATE #__bl_config SET cfg_value='".$jssa_editplayer."' WHERE cfg_name='jssa_editplayer'";
        $this->db->setquery($query);
        $this->db->query();

        $query = "UPDATE #__bl_config SET cfg_value='".$jssa_editplayer_single."' WHERE cfg_name='jssa_editplayer_single'";
        $this->db->setquery($query);
        $this->db->query();

        $query = "UPDATE #__bl_config SET cfg_value='".$jssa_deleteplayers."' WHERE cfg_name='jssa_deleteplayers'";
        $this->db->setquery($query);
        $this->db->query();

        $query = "UPDATE #__bl_config SET cfg_value='".$jssa_deleteplayers_single."' WHERE cfg_name='jssa_deleteplayers_single'";
        $this->db->setquery($query);
        $this->db->query();

        //invite to match
        $query = "UPDATE #__bl_config SET cfg_value='".$esport_invite_match."' WHERE cfg_name='esport_invite_match'";
        $this->db->setquery($query);
        $this->db->query();

        //knock_style
        $query = "UPDATE #__bl_config SET cfg_value='".$knock_style."' WHERE cfg_name='knock_style'";
        $this->db->setquery($query);
        $this->db->query();

        //social buttons
        $query = "UPDATE #__bl_config SET cfg_value='".$jsb_twitter."' WHERE cfg_name='jsb_twitter'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$jsb_gplus."' WHERE cfg_name='jsb_gplus'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$jsb_fbshare."' WHERE cfg_name='jsb_fbshare'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$jsb_fblike."' WHERE cfg_name='jsb_fblike'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$jsbp_season."' WHERE cfg_name='jsbp_season'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$jsbp_team."' WHERE cfg_name='jsbp_team'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$jsbp_player."' WHERE cfg_name='jsbp_player'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$jsbp_match."' WHERE cfg_name='jsbp_match'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$jsbp_venue."' WHERE cfg_name='jsbp_venue'";
        $this->db->setquery($query);
        $this->db->query();
        //team layout
                $query = "UPDATE #__bl_config SET cfg_value='".$tlb_position."' WHERE cfg_name='tlb_position'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$tlb_form."' WHERE cfg_name='tlb_form'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$tlb_latest."' WHERE cfg_name='tlb_latest'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$tlb_next."' WHERE cfg_name='tlb_next'";
        $this->db->setquery($query);
        $this->db->query();

        $query = "UPDATE #__bl_config SET cfg_value='".$jssa_addexteam."' WHERE cfg_name='jssa_addexteam'";
        $this->db->setquery($query);
        $this->db->query();
/*UPDATE*/
        $query = "UPDATE #__bl_config SET cfg_value='".$jssa_addexteam_single."' WHERE cfg_name='jssa_addexteam_single'";
        $this->db->setquery($query);
        $this->db->query();

        $query = "UPDATE #__bl_config SET cfg_value='".$player_team_reg."' WHERE cfg_name='player_team_reg'";
        $this->db->setquery($query);
        $this->db->query();

        //autoreg
        $query = "UPDATE #__bl_config SET cfg_value='".$autoreg_player."' WHERE cfg_name='autoreg_player'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$reg_lastname."' WHERE cfg_name='reg_lastname'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".$reg_lastname_rq."' WHERE cfg_name='reg_lastname_rq'";
        $this->db->setquery($query);
        $this->db->query();

        $query = "UPDATE #__bl_config SET cfg_value='".$display_profile."' WHERE cfg_name='display_profile'";
        $this->db->setquery($query);
        $this->db->query();

        $adf_pl = JRequest::getVar('adf_pl', array(0), '', 'array');
        JArrayHelper::toInteger($adf_pl, array(0));
        if (count($adf_pl)) {
            $counter = 0;
            foreach ($adf_pl as $map) {
                $query = "UPDATE #__bl_extra_filds SET reg_exist='".((isset($_POST['adfpl_reg_'.$map]) && $_POST['adfpl_reg_'.$map] == 1) ? 1 : 0)."',reg_require='".((isset($_POST['adfpl_rq_'.$map]) && $_POST['adfpl_rq_'.$map] == 1) ? 1 : 0)."' WHERE id=".$map;
                $this->db->setQuery($query);
                $this->db->query();
                ++$counter;
            }
        }

        $adf_pl = JRequest::getVar('adf_tm', array(0), '', 'array');
        JArrayHelper::toInteger($adf_pl, array(0));
        if (count($adf_pl)) {
            $counter = 0;
            foreach ($adf_pl as $map) {
                $query = "UPDATE #__bl_extra_filds SET reg_exist='".((isset($_POST['adf_reg_'.$map]) && $_POST['adf_reg_'.$map] == 1) ? 1 : 0)."',reg_require='".((isset($_POST['adf_rq_'.$map]) && $_POST['adf_rq_'.$map] == 1) ? 1 : 0)."' WHERE id=".$map;
                $this->db->setQuery($query);
                $this->db->query();
                ++$counter;
            }
        }

        // Custom fields: team city, etc.
        $customFields = JS_Utils::getCustomFields();
        $customFields['team_city']['enabled'] = (bool) JRequest::getVar('cf_team_city_enabled', 0, 'post', 'int');
        $customFields['team_city']['required'] = (bool) JRequest::getVar('cf_team_city_required', 0, 'post', 'int');

        $query = "UPDATE #__bl_config
	        SET cfg_value='".serialize($customFields)."'
            WHERE cfg_name='custom_fields'";

        $this->db->setquery($query);
        $this->db->query();
        JS_Utils::invalidateFieldsCache();

                //match custom statuses;
               // mstatusesId
                $mstatuses = array();
                //hack for autoincrement bug
                $this->db->setQuery('DELETE FROM #__bl_match_statuses WHERE id = 1');
            $this->db->query();
        if (isset($_POST['mstatusesId']) && count($_POST['mstatusesId'])) {
            for ($intA = 0; $intA < count($_POST['mstatusesId']); ++$intA) {
                if ($_POST['mstatusesId'][$intA] == 0 && $_POST['mstatusesName'][$intA] && $_POST['mstatusesShortName'][$intA]) {
                    $this->db->setQuery("INSERT INTO #__bl_match_statuses(stName,stShort,ordering) VALUES('".addslashes($_POST['mstatusesName'][$intA])."','".addslashes($_POST['mstatusesShortName'][$intA])."',{$intA})");
                    $this->db->query();
                    $id = $this->db->insertid();
                    //hack for autoincrement bug
                    if($id == 1){
                        $this->db->setQuery("INSERT INTO #__bl_match_statuses(stName,stShort,ordering) VALUES('".addslashes($_POST['mstatusesName'][$intA])."','".addslashes($_POST['mstatusesShortName'][$intA])."',{$intA})");
                        $this->db->query();
                        $id = $this->db->insertid();
                    }
                } elseif ($_POST['mstatusesId'][$intA]) {
                    $this->db->setQuery("UPDATE #__bl_match_statuses SET stName='".addslashes($_POST['mstatusesName'][$intA])."',stShort='".addslashes($_POST['mstatusesShortName'][$intA])."',ordering={$intA} WHERE id=".intval($_POST['mstatusesId'][$intA]));
                    $this->db->query();
                    $id = intval($_POST['mstatusesId'][$intA]);
                }
                //hack for autoincrement bug
                if($id != 1){
                    $mstatuses[] = $id;
                }
            }
        }
        if (count($mstatuses)) {
            $this->db->setQuery('DELETE FROM #__bl_match_statuses WHERE id NOT IN ('.implode(',', $mstatuses).')');
            $this->db->query();
        }elseif(!isset($_POST['mstatusesId'])){
            $this->db->setQuery('DELETE FROM #__bl_match_statuses');
            $this->db->query();
        }
        
        
        //time line
        $jstimeline = JRequest::getVar('jstimeline', array(0), '', 'array');
        $query = "UPDATE #__bl_config SET cfg_value='".json_encode($jstimeline)."' WHERE cfg_name='jstimeline'";
        $this->db->setquery($query);
        $this->db->query();
        
        
        //rostertab
        
        $show_playerstattab = JRequest::getVar('show_playerstattab', 0, 'post', 'int');
        $show_rostertab = JRequest::getVar('show_rostertab', 0, 'post', 'int');
        $set_teampgplayertab_groupby = JRequest::getVar('set_teampgplayertab_groupby', 0, 'post', 'int');
        $set_playerfieldnumber = JRequest::getVar('set_playerfieldnumber', 0, 'post', 'int');
        $set_playercardef = JRequest::getVar('set_playercardef', 0, 'post', 'int');

        $query = "UPDATE #__bl_config SET cfg_value='".($show_rostertab)."' WHERE cfg_name='show_rostertab'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".($set_teampgplayertab_groupby)."' WHERE cfg_name='set_teampgplayertab_groupby'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".($set_playerfieldnumber)."' WHERE cfg_name='set_playerfieldnumber'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".($set_playercardef)."' WHERE cfg_name='set_playercardef'";
        $this->db->setquery($query);
        $this->db->query();
        $query = "UPDATE #__bl_config SET cfg_value='".($show_playerstattab)."' WHERE cfg_name='show_playerstattab'";
        $this->db->setquery($query);
        $this->db->query();
        
        
        $enbl_mdname_on_match = JRequest::getVar('enbl_mdname_on_match', 0, 'post', 'int');
        $query = "UPDATE #__bl_config SET cfg_value='".($enbl_mdname_on_match)."' WHERE cfg_name='enbl_mdname_on_match'";
        $this->db->setquery($query);
        $this->db->query();
        

        //h2h

        $enbl_match_analytics_block = JRequest::getVar('enbl_match_analytics_block', 0, 'post', 'int');
        $query = "UPDATE #__bl_config SET cfg_value='".($enbl_match_analytics_block)."' WHERE cfg_name='enbl_match_analytics_block'";
        $this->db->setquery($query);
        $this->db->query();

        //kick events
        $avgevents_events = JRequest::getVar('avgevents_events', array(), 'post', 'array');
        $query = "UPDATE #__bl_config SET cfg_value='".json_encode($avgevents_events)."' WHERE cfg_name='avgevents_events'";
        $this->db->setquery($query);
        $this->db->query();

        //squad
        $jsmatch_squad_firstcol = JRequest::getVar('jsmatch_squad_firstcol', 0, 'post', 'int');
        $query = "UPDATE #__bl_config SET cfg_value='".($jsmatch_squad_firstcol)."' WHERE cfg_name='jsmatch_squad_firstcol'";
        $this->db->setquery($query);
        $this->db->query();

        $jsmatch_squad_lastcol = JRequest::getVar('jsmatch_squad_lastcol', 0, 'post', 'int');
        $query = "UPDATE #__bl_config SET cfg_value='".($jsmatch_squad_lastcol)."' WHERE cfg_name='jsmatch_squad_lastcol'";
        $this->db->setquery($query);
        $this->db->query();


        $enbl_matchtooltip = JRequest::getVar('enbl_matchtooltip', 0, 'post', 'int');
        $query = "UPDATE #__bl_config SET cfg_value='".($enbl_matchtooltip)."' WHERE cfg_name='enbl_matchtooltip'";
        $this->db->setquery($query);
        $this->db->query();

        $enbl_playerlinks_hglteams = JRequest::getVar('enbl_playerlinks_hglteams', 0, 'post', 'int');
        $query = "UPDATE #__bl_config SET cfg_value='".($enbl_playerlinks_hglteams)."' WHERE cfg_name='enbl_playerlinks_hglteams'";
        $this->db->setquery($query);
        $this->db->query();



    }
}
