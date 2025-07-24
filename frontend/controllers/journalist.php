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
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
require_once dirname(__FILE__).'/../includes/func.php';
$mainframe = JFactory::getApplication();
$db = JFactory::getDBO();
$user = JFactory::getUser();

$task = JRequest::getVar('task', null, 'default', 'cmd');

    /*if ($user->get('guest')) {
        $return_url = $_SERVER['REQUEST_URI'];
        $return_url = base64_encode($return_url);

        $uopt = 'com_users';

        $return = 'index.php?option='.$uopt.'&view=login&return='.$return_url;

            // Redirect to a login form
            $mainframe->redirect($return, JText::_('BLMESS_NOT_LOGIN'));
    }*/


class JoomsportControllerJournalist extends JControllerLegacy
{
    protected $js_prefix = '';
    protected $mainframe = null;
    protected $option = 'com_joomsport';

    public function __construct()
    {
        parent::__construct();
        $this->mainframe = JFactory::getApplication();

    }
    
    public function setMatchGeneral(){
        $db = JFactory::getDBO();
        $match_id  = JRequest::getVar('match_id', 0, 'request', 'int');
        $match_status  = JRequest::getVar('match_status', 0, 'request', 'int');
        $match_duration  = JRequest::getVar('match_duration', 0, 'request', 'int');
        
        ob_clean();
        
        if(!$this->isSeasonAdmin($match_id)){
            exit();
        }
        
        $db->setQuery("SELECT m.*,md.s_id FROM #__bl_match as m"
                    . " JOIN #__bl_matchday as md ON md.id = m.m_id"
                    . " WHERE m.id={$match_id}");
        $match = $db->loadObject();
        
        $options_arr = array();
        if($match->options){
            $options_arr = json_decode($match->options, true);
        }
        $options_arr["duration"] = $match_duration;

        $db->setQuery("UPDATE #__bl_match SET m_played='".$match_status."',options='".json_encode($options_arr)."'"
                    . " WHERE id={$match_id}");
        $db->query();  
        
        if($match_status == '1'){
            //fire matchonsave event
            require_once JPATH_COMPONENT_SITE.DIRECTORY_SEPARATOR.'sportleague'.DIRECTORY_SEPARATOR.'sportleague.php';
            classJsportPlugins::get('onMatchSave', array('match_id' => $match_id));
            
            $db->setQuery("SELECT md.s_id FROM #__bl_match as m"
                    . " JOIN #__bl_matchday as md ON md.id = m.m_id"
                    . " WHERE m.id={$match_id}");
            $season_id = $db->loadResult();
            
            classJsportPlugins::get('generateTableStanding', array('season_id' => $season_id));

            //update player list
            classJsportPlugins::get('generatePlayerList', array('season_id' => $season_id));
        }
        
        exit();
        
    }
    
    public function setMatchScore(){
        $db = JFactory::getDBO();
        $match_id  = JRequest::getVar('match_id', 0, 'request', 'int');
        $js_live_score1  = JRequest::getVar('js_live_score1', 0, 'request', 'int');
        $js_live_score2  = JRequest::getVar('js_live_score2', 0, 'request', 'int');
        $is_extra  = JRequest::getVar('is_extra', 0, 'request', 'int');
        
        ob_clean();
        
        if(!$this->isSeasonAdmin($match_id)){
            exit();
        }
        
        $db->setQuery("UPDATE #__bl_match SET score1='".$js_live_score1."',score2='".$js_live_score2."',is_extra='".$is_extra."'"
                    . " WHERE id={$match_id}");
        $db->query();  
        
        $query = 'DELETE  FROM #__bl_mapscore WHERE m_id = '.$match_id;
        $db->setQuery($query);
        $db->query();
        if (isset($_POST['js_live_mapid']) && count($_POST['js_live_mapid'])) {
            for ($i = 0; $i < count($_POST['js_live_mapid']); ++$i) {
                $new_event = $_POST['js_live_mapid'][$i];
                $query = 'INSERT INTO #__bl_mapscore(m_id,map_id,m_score1,m_score2) VALUES('.$match_id.','.$new_event.','.intval($_POST['js_live_map1'][$i]).','.intval($_POST['js_live_map2'][$i]).')';
                $db->setQuery($query);
                $db->query();
            }
        }
        
    }

    public function showMatchLineUp(){
        $db = JFactory::getDBO();
        $match_id  = JRequest::getVar('match_id', 0, 'request', 'int');
        ob_clean();
        if(!$this->isSeasonAdmin($match_id)){
            exit();
        }
        $return = array();
        if($match_id){
            $return["error"] = 0;
            $return["html"] = '<form id="jsSquadForm" name="jsSquadForm">';
            $db->setQuery("SELECT m.*,md.s_id FROM #__bl_match as m"
                    . " JOIN #__bl_matchday as md ON md.id = m.m_id"
                    . " WHERE m.id={$match_id}");
            $match = $db->loadObject();
            
            $db->setQuery("SELECT t_name FROM #__bl_teams"
                            . " WHERE id={$match->team1_id}");
            $team1Name = $db->loadResult();
            
            $db->setQuery("SELECT t_name FROM #__bl_teams"
                            . " WHERE id={$match->team2_id}");
            $team2Name = $db->loadResult();
            
            //var_dump($match);die();
            $query = "SELECT CONCAT(p.id,'*',s.team_id) as id,CONCAT(p.first_name,' ',p.last_name) as p_name,p.id as pid
                            FROM #__bl_players as p, #__bl_players_team as s
                            WHERE s.confirmed='0' AND s.player_join='0' AND s.player_id = p.id
                            AND s.team_id = ".$match->team1_id.' AND s.season_id='.$match->s_id
                .' ORDER BY p.first_name,p.last_name';
            $db->setQuery($query);
            $players1 = $db->loadObjectList();
            $query = "SELECT CONCAT(p.id,'*',s.team_id) as id,CONCAT(p.first_name,' ',p.last_name) as p_name,p.id as pid
                            FROM #__bl_players as p, #__bl_players_team as s
                            WHERE s.confirmed='0' AND s.player_join='0' AND s.player_id = p.id
                            AND s.team_id = ".$match->team2_id.' AND s.season_id='.$match->s_id
                .' ORDER BY p.first_name,p.last_name';
            $db->setQuery($query);
            $players2 = $db->loadObjectList();
            
            $sqr = array();

            $sqr[] = JHTML::_('select.option', 1, JText::_('BLBE_LANGVIEWSOTH_LINUP'));
            $sqr[] = JHTML::_('select.option', 2, JText::_('BLBE_SUBSTITUTE_OPTION'));
            $sqr[] = JHTML::_('select.option', 0, JText::_('BLBE_NOT_PARTICIPATED'));
            
            if(count($players1)){
                $query = 'SELECT p.id FROM #__bl_players as p, #__bl_squard as s '
                .' WHERE p.id=s.player_id AND s.match_id='.$match_id." AND s.team_id={$match->team1_id}"
                ." AND s.mainsquard = '1'";
                $db->setQuery($query);
                $squard1 = $db->loadColumn();
                
                $query = 'SELECT p.id FROM #__bl_players as p, #__bl_squard as s WHERE p.id=s.player_id AND s.match_id='.$match_id." AND s.team_id={$match->team1_id} AND s.mainsquard = '0'";
                $db->setQuery($query);
                $squard1_res = $db->loadColumn();
                
                $return["html"] .= '<h3>'.$team1Name.'</h3>';
                $return["html"] .= '<table class="table table-striped"><tbody>';
                for($intA=0;$intA<count($players1);$intA++){
                    
                    $squadIS = 0;
                    if(count($squard1) && in_array($players1[$intA]->pid, $squard1)){
                        $squadIS = 1;
                    }elseif(count($squard1_res) && in_array($players1[$intA]->pid, $squard1_res)){
                        $squadIS = 2;
                    }
                    
                    
                    $return["html"] .= "<tr>";
                    $return["html"] .=  "<td>".$players1[$intA]->p_name."</td>";
                    $return["html"] .= '<td style="text-align:right;"><div class="controls squardbut"><fieldset class="radio btn-group-js">';
                    $return["html"] .= JHTML::_('select.radiolist', $sqr, 'squadradio1_'.$players1[$intA]->pid, 'class="inputbox" id="squadradio_'.$players1[$intA]->pid.'" ', 'value', 'text', $squadIS);
                    $return["html"] .= '<input type="hidden" name="t1_squard[]" value="'.$players1[$intA]->pid.'" />';
                    $return["html"] .= '</fieldset></div></td>';
                    $return["html"] .= "</tr>";
                }
                $return["html"] .= '</tbody></table>';
            }
            if(count($players2)){
                $query = 'SELECT p.id FROM #__bl_players as p, #__bl_squard as s '
                .' WHERE p.id=s.player_id AND s.match_id='.$match_id." AND s.team_id={$match->team2_id}"
                ." AND s.mainsquard = '1'";
                $db->setQuery($query);
                $squard2 = $db->loadColumn();
                
                $query = 'SELECT p.id FROM #__bl_players as p, #__bl_squard as s WHERE p.id=s.player_id AND s.match_id='.$match_id." AND s.team_id={$match->team2_id} AND s.mainsquard = '0'";
                $db->setQuery($query);
                $squard2_res = $db->loadColumn();
                $return["html"] .= '<h3>'.$team2Name.'</h3>';
                $return["html"] .= '<table class="table table-striped"><tbody>';
                for($intA=0;$intA<count($players2);$intA++){
                    $squadIS = 0;
                    if(count($squard2) && in_array($players2[$intA]->pid, $squard2)){
                        $squadIS = 1;
                    }elseif(count($squard2_res) && in_array($players2[$intA]->pid, $squard2_res)){
                        $squadIS = 2;
                    }
                    $return["html"] .= "<tr>";
                    $return["html"] .=  "<td>".$players2[$intA]->p_name."</td>";
                    $return["html"] .= '<td  style="text-align:right;"><div class="controls squardbut"><fieldset class="radio btn-group-js">';
                    $return["html"] .= JHTML::_('select.radiolist', $sqr, 'squadradio2_'.$players2[$intA]->pid, 'class="inputbox" id="squadradio_'.$players2[$intA]->pid.'" ', 'value', 'text', $squadIS);
                    $return["html"] .= '<input type="hidden" name="t2_squard[]" value="'.$players2[$intA]->pid.'" />';
                    $return["html"] .= '</fieldset></div></td>';
                    $return["html"] .= "</tr>";
                     
                }
                $return["html"] .= '</tbody></table>';
            }
            $return["html"] .= '</form>';
            
        }
        echo json_encode($return);
        exit();
        
    }
    public function setMatchLineUp(){
        $db = JFactory::getDBO();
        $match_id  = JRequest::getVar('match_id', 0, 'request', 'int');
        ob_clean();
        
        if(!$this->isSeasonAdmin($match_id)){
            exit();
        }
        //-----SQUARD--------///
        
        $db->setQuery("SELECT m.*,md.s_id FROM #__bl_match as m"
                    . " JOIN #__bl_matchday as md ON md.id = m.m_id"
                    . " WHERE m.id={$match_id}");
        $match = $db->loadObject();

        $query = 'DELETE FROM #__bl_squard WHERE match_id = '.$match_id;
        $db->setQuery($query);
        $db->query();
        if (isset($_POST['t1_squard']) && count($_POST['t1_squard'])) {
            for ($i = 0; $i < count($_POST['t1_squard']); ++$i) {
                $new_event = $_POST['t1_squard'][$i];
                if (isset($_POST['squadradio1_'.$new_event]) && $_POST['squadradio1_'.$new_event]) {
                    if ($_POST['squadradio1_'.$new_event] == '1' || $_POST['squadradio1_'.$new_event] == '2') {
                        $main_squad = ($_POST['squadradio1_'.$new_event] == '1') ? 1 : 0;
                        $query = 'INSERT INTO #__bl_squard(match_id,team_id,player_id,mainsquard) VALUES('.$match_id.','.$match->team1_id.','.$new_event.",'".$main_squad."')";
                        $db->setQuery($query);
                        $db->query();
                    }
                }
            }
        }

        if (isset($_POST['t2_squard']) && count($_POST['t2_squard'])) {
            for ($i = 0; $i < count($_POST['t2_squard']); ++$i) {
                $new_event = $_POST['t2_squard'][$i];
                if (isset($_POST['squadradio2_'.$new_event]) && $_POST['squadradio2_'.$new_event]) {
                    if ($_POST['squadradio2_'.$new_event] == '1' || $_POST['squadradio2_'.$new_event] == '2') {
                        $main_squad = ($_POST['squadradio2_'.$new_event] == '1') ? 1 : 0;
                        $query = 'INSERT INTO #__bl_squard(match_id,team_id,player_id,mainsquard) VALUES('.$match_id.','.$match->team2_id.','.$new_event.",'".$main_squad."')";
                        $db->setQuery($query);
                        $db->query();
                    }
                }
            }
        }
        exit();
    }

    public function getMatchSubs(){
        $db = JFactory::getDBO();
        $match_id  = JRequest::getVar('match_id', 0, 'request', 'int');
        $team_id  = JRequest::getVar('team_id', 0, 'request', 'int');
        $is_edit  = JRequest::getVar('isedit', 0, 'request', 'int');
        $recordID  = JRequest::getVar('recordID', 0, 'request', 'int');
        $playerIN = $playerOUT = 0;
        if($recordID){
            $db->setQuery("SELECT * FROM #__bl_liveposts WHERE id = '".$recordID."'");
            $record = $db->loadObject();
            $record_options = json_decode($record->options,true);
            if(isset($record_options["psubs"]) && $record_options["psubs"]){
                
                $query = "SELECT * FROM #__bl_subsin WHERE id=".intval($record_options["psubs"])." AND team_id=".$team_id;
                $db->setQuery($query);
                $subsObj = $db->loadObject();
                $playerIN = $subsObj->player_in;
                $playerOUT = $subsObj->player_out;
            }
        }
        
        ob_clean();
        if(!$this->isSeasonAdmin($match_id)){
            exit();
        }
        $return = array();
        if($match_id){
            $return["error"] = 0;
            $return["html"] = '';
            $is_mainsubs[] = JHTML::_('select.option',  0, JText::_('BLFA_SELPLAYER_OUT'), 'id', 'p_name');

            $query = "SELECT p.id as id, CONCAT(p.first_name,' ',p.last_name) as p_name"
                                .' FROM #__bl_players as p'
                                .' JOIN #__bl_squard as s ON p.id = s.player_id'
                                . ' LEFT JOIN #__bl_subsin as subs ON subs.match_id=s.match_id AND p.id = subs.player_in'
                                . ' LEFT JOIN #__bl_subsin as subs2 ON subs2.match_id=s.match_id AND p.id = subs2.player_out'
                                .' WHERE s.team_id='.$team_id.' AND s.match_id = '.$match_id
                                ." AND s.accepted = '1' AND subs2.match_id IS NULL"
                                
                                . " AND ((s.mainsquard = '1') OR (subs.match_id))";
            $db->setQuery($query);
            $main_squad = $db->loadObjectList();

            if(count($main_squad)){
                $is_mainsubs = array_merge($is_mainsubs, $main_squad);
            }
            
            if($playerIN){
                $query = "SELECT p.id as id, CONCAT(p.first_name,' ',p.last_name) as p_name"
                                .' FROM #__bl_players as p'
                                .' WHERE p.id = '.$playerIN;
                $db->setQuery($query);
                $pl = $db->loadObjectList();
                
                if(count($pl)){
                    $is_mainsubs = (array_merge($is_mainsubs, $pl));
                }
            }
            
            $return["html"] .= JHTML::_('select.genericlist',   $is_mainsubs, 'jslive_subs_out'.($is_edit?'_edit':''), 'class="chzn-done" size="1"', 'id', 'p_name', $playerIN);

            $is_mainsubs2[] = JHTML::_('select.option',  0, JText::_('BLFA_SELPLAYER_IN'), 'id', 'p_name');

            $query = "SELECT p.id as id, CONCAT(p.first_name,' ',p.last_name) as p_name"
                                .' FROM #__bl_players as p'
                                .' JOIN #__bl_squard as s ON p.id = s.player_id'
                                . ' LEFT JOIN #__bl_subsin as subs ON subs.match_id=s.match_id AND p.id = subs.player_in'
                                .' WHERE s.team_id='.$team_id.' AND s.match_id = '.$match_id
                                
                                ." AND s.accepted = '1'"
                                . " AND ((s.mainsquard = '0') AND (subs.match_id IS NULL))";
            $db->setQuery($query);
            $subs_squad = $db->loadObjectList();
            if(count($subs_squad)){
                $is_mainsubs2 = array_merge($is_mainsubs2, $subs_squad);
            }
            if($playerOUT){
                $query = "SELECT p.id as id, CONCAT(p.first_name,' ',p.last_name) as p_name"
                                .' FROM #__bl_players as p'
                                .' WHERE p.id = '.$playerOUT;
                $db->setQuery($query);
                $pl = $db->loadObjectList();
                if(count($pl)){
                    $is_mainsubs2 = array_merge($is_mainsubs2, $pl);
                }
            }
            
            $return["html"] .= JHTML::_('select.genericlist',   $is_mainsubs2, 'jslive_subs_in'.($is_edit?'_edit':''), 'class="chzn-done" size="1"', 'id', 'p_name', $playerOUT);
        }
        
        echo json_encode($return);
        
        exit();
    }
    public function addPost(){
        require_once 'components/com_joomsport/sportleague/sportleague.php';
        require_once JS_PATH_OBJECTS.'class-jsport-team.php';
        require_once JS_PATH_OBJECTS.'class-jsport-player.php';
        require_once JS_PATH_OBJECTS.'class-jsport-event.php';
        
        $db = JFactory::getDBO();
        $match_id  = JRequest::getVar('match_id', 0, 'request', 'int');
        $post_text = JRequest::getVar( 'live_post', '', 'post', 'string', JREQUEST_ALLOWRAW );
        $event_id  = JRequest::getVar('event_id', 0, 'request', 'int');
        $playerz_id  = JRequest::getVar('playerz_id', '', 'request', 'string');
        
        $teamsq_id  = JRequest::getVar('teamsq_id', 0, 'request', 'int');
        $subs_out  = JRequest::getVar('jslive_subs_out', 0, 'request', 'int');
        $subs_in  = JRequest::getVar('jslive_subs_in', 0, 'request', 'int');
        
        $subev = JRequest::getVar('playerzSub_id', array(), 'request', 'array');
        $sub_ev_id  = JRequest::getVar('sub_ev_id', 0, 'request', 'int');
        
        
        ob_clean();
        $player_event_id = 0;
        $additional_post = '';
        $RecOptions = array();
        
        if(!$this->isSeasonAdmin($match_id)){
            exit();
        }
        
        $post_text = '<div class="jsPostText">'.$post_text.'<div class="jsclear"></div></div>';
        
        $return_json = array();
        
        if($event_id && $playerz_id){
            $player = explode('*', $playerz_id);
            if(isset($player[0]) && intval($player[0]) && isset($player[1]) && intval($player[1])){
                
                $query = "SELECT MAX(eordering) FROM #__bl_match_events WHERE match_id={$match_id}";
                $db->setQuery($query);
                $eordering = (int) $db->loadResult();
                $eordering++;
                
                $query = "INSERT INTO #__bl_match_events(match_id,player_id,minutes,e_id,t_id,ecount,eordering)"
                . " VALUES({$match_id},'".intval($player[0])."','".($_POST["js_live_post_time"])."',{$event_id},'".intval($player[1])."',1,{$eordering})";
        
                $db->setQuery($query);
                $db->query();
                $player_event_id = $db->insertid();
                
                $sube_player = '';
                
                if($sub_ev_id && count($subev)){
                    $plOrd = 0;
                    
                    foreach($subev as $pl){
                        $plis = explode('*', $pl);
                        $query = 'INSERT INTO #__bl_match_events(e_id,player_id,match_id,ecount,minutes,t_id,eordering,additional_to) VALUES('.intval($sub_ev_id).','.intval($plis[0]).','.$match_id.',1,"'.addslashes($_POST["js_live_post_time"]).'",'.intval($plis[1]).','.$plOrd.','.$player_event_id.')';
                        $db->setQuery($query);
                        $db->query();
                        
                        
                        $playerObjSub = new classJsportPlayer(intval($plis[0]),0);
                        $p_nameSub = $playerObjSub->getName(false);
                        if($sube_player){
                            $sube_player .= ", ";
                        }
                        $sube_player .= $p_nameSub; 
                        
                        $plOrd++;
                    }
                    
                    $SubeventObj = new classJsportEvent($sub_ev_id);
                    $sube_name = $SubeventObj->getEventName();
                    
                }
                
                
                //$db->setQuery("SELECT e_name FROM #__bl_events WHERE id={$event_id}");
                //$e_name = $db->loadResult();
                
                $eventObj = new classJsportEvent($event_id);
                $e_name = $eventObj->getEventName();
                
                //$db->setQuery("SELECT CONCAT(first_name,' ',last_name) FROM #__bl_players WHERE id=".intval($player[0]));
                //$p_name = $db->loadResult();
                
                $playerObj = new classJsportPlayer(intval($player[0]),0);
                $p_name = $playerObj->getName(false);
                
                //$db->setQuery("SELECT t_name FROM #__bl_teams WHERE id=".intval($player[1]));
                //$t_name = $db->loadResult();
                
                $teamObj = new classJsportTeam(intval($player[1]),0);
                $t_name = $teamObj->getName(false);
                
                $additional_post .= '<div class="jslivepost_events">' . $e_name .', '.$p_name .($sube_player?". ".$sube_name.": ".$sube_player:"").' ('.$t_name.')</div>';
                
                $RecOptions["pevent"] = $player_event_id;
                
            }
        }
        
        if($teamsq_id && $subs_in && $subs_out){
            
            $query = "SELECT md.s_id FROM "
                    . "#__bl_match as m"
                    . " JOIN #__bl_matchday as md ON m.m_id=md.id";
            $db->setQuery($query);
            $season_id = $db->loadResult();
            
            $query = "INSERT INTO #__bl_subsin(match_id,team_id,player_in,player_out,minutes,season_id)"
                . " VALUES({$match_id},'".intval($teamsq_id)."',{$subs_in},{$subs_out},'".intval($_POST["js_live_post_time"])."',{$season_id})";
        
            $db->setQuery($query);
            $db->query();
            $player_subs_id = $db->insertid();
            
            $playerObj = new classJsportPlayer(intval($subs_in),0);
            $p_name = $playerObj->getName(false);
             
            $playerObj = new classJsportPlayer(intval($subs_out),0);
            $p2_name = $playerObj->getName(false);
            
            /*$db->setQuery("SELECT CONCAT(first_name,' ',last_name) FROM #__bl_players WHERE id=".intval($subs_in));
            $p_name = $db->loadResult();
            $db->setQuery("SELECT CONCAT(first_name,' ',last_name) FROM #__bl_players WHERE id=".intval($subs_out));
            $p2_name = $db->loadResult();*/

            //$db->setQuery("SELECT t_name FROM #__bl_teams WHERE id=".intval($teamsq_id));
            //$t_name = $db->loadResult();
            
            $teamObj = new classJsportTeam(intval($teamsq_id),0);
            $t_name = $teamObj->getName(false);
            
            $additional_post .= '<div class="jslivepost_subs">('.$t_name.') ' . $p2_name .'<img src="'.JUri::base().'components/com_joomsport/img/ico/out-new.png" />  '.$p_name .'<img src="'.JUri::base().'components/com_joomsport/img/ico/in-new.png" /></div>';
            
            $RecOptions["psubs"] = $player_subs_id;
            
        }
        
        $js_live_twitter = JRequest::getVar('js_live_twitter', '', 'request', 'string');
        
        if($js_live_twitter){
            $ch = curl_init(); 

            // set url 
            curl_setopt($ch, CURLOPT_URL, "https://publish.twitter.com/oembed?url=".$js_live_twitter); 

            //return the transfer as a string 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

            // $output contains the output string 
            $output = curl_exec($ch); 

            // close curl resource to free up system resources 
            curl_close($ch);  
            if($output){
                $output = json_decode($output, true);
                if(isset($output["html"])){
                    $post_text .= '<div class="jslivetwitt">'.$output["html"].'</div>';
                }
            }
            $RecOptions["twitter"] = $js_live_twitter;
        }
        
        $js_live_fb = JRequest::getVar('js_live_fb', '', 'request', 'string');
        if($js_live_fb){
            $post_text .= '<div class="fb-post" data-href="'.$js_live_fb.'"></div>';
            $RecOptions["facebook"] = $js_live_fb;
        }
        
        
        
        
        $post_text = $additional_post . $post_text;
        
        $user = JFactory::getUser();
        
        $db->setQuery("SELECT MAX(ordering) FROM #__bl_liveposts WHERE match_id={$match_id}");
        $ordering = (int) $db->loadResult();
        $ordering++;
        
        $languageTag = JFactory::getLanguage()->getTag();
        
        $query = "INSERT INTO #__bl_liveposts(match_id,ordering,minutes,languageID,journalistID,postText,postIcon,options,postTime)"
                . " VALUES({$match_id},{$ordering},'".addslashes($_POST["js_live_post_time"])."','".$languageTag."',{$user->id},'".addslashes($post_text)."','".addslashes($_POST["e_img"])."','".json_encode($RecOptions)."','".gmdate('Y-m-d H:i:s')."')";
        
                $db->setQuery($query);
        $db->query();
        $lpost_id = $db->insertid();
        $isimg = '';
        if(isset($_POST["e_img"]) && $_POST["e_img"]){
            $isimg = '<img src="media/bearleague/events/'.addslashes($_POST["e_img"]).'" />';
        }
        $html = '<div class="jsLivePostDiv" jsupdaten="'.$lpost_id.'">';
        $html .= '<div class="jsLivePostDivTime">'.addslashes($_POST["js_live_post_time"]).'<div>'.$isimg.'</div></div><div class="jsLivePostDivmain">'.$post_text.'</div>';
        $html .= '<div class="jsLivePostDivEditing" jsdata="'.$lpost_id.'"><i class="fa fa-times" aria-hidden="true"></i><i class="fa fa-pencil-square-o" aria-hidden="true"></i></div>';
        $html .= '</div>';
        
        $return_json = array(
            'error' => 0,
            'html' => $html
        );
        
        echo json_encode($return_json);
        
        exit();
    }
    
    public function checkUpdts(){
        $db = JFactory::getDBO();
        $match_id  = JRequest::getVar('match_id', 0, 'request', 'int');
        $lastupd  = JRequest::getVar('lastupd', '', 'request', 'string');
        require_once JPATH_ROOT.'/components/com_joomsport/sportleague/base/joomla/classes/class-jsport-link.php';
        
        ob_clean();
        require_once 'components/com_joomsport/sportleague/sportleague.php';
        require_once JS_PATH_OBJECTS.'class-jsport-match.php';
        
        $html = $events = array();
        
        $db->setQuery("SELECT m.*,md.s_id FROM #__bl_match as m"
                    . " JOIN #__bl_matchday as md ON md.id = m.m_id"
                    . " WHERE m.id={$match_id}");
        $match = $db->loadObject();
        
        if($match->m_played != -1){
            exit();
        }
        $languageTag = JFactory::getLanguage()->getTag();
        $user = JFactory::getUser();
        $new_updates = '';
        if($lastupd){
            $new_updates = gmdate('Y-m-d H:i:s', strtotime($lastupd));
        }
        //$new_updates = gmdate('Y-m-d H:i:s', strtotime('-1 minutes'));
        
        $db->setQuery("SELECT * FROM #__bl_liveposts WHERE match_id={$match_id} AND languageID = '".$languageTag."' AND journalistID != '".$user->id."' AND postTime >= '".$new_updates."' ORDER BY ordering");
        $updates = $db->loadObjectList();
        
        $db->setQuery("SELECT id FROM #__bl_liveposts WHERE match_id={$match_id} AND languageID = '".$languageTag."' ORDER BY ordering");
        $allposts = $db->loadColumn();
        
        
        
        for($intA=0;$intA<count($updates);$intA++){
            $imgsrc = JUri::base().'media/system/images/blank.png';
            if($updates[$intA]->postIcon){
                $imgsrc = JUri::base().'media/bearleague/events/'.$updates[$intA]->postIcon;
            }
            
            $html[] = array($updates[$intA]->id,'<div class="jsLivePostDiv" jsupdaten="'.$updates[$intA]->id.'"><div class="jsLivePostDivTime">'.($updates[$intA]->minutes).'<div><img src="'.$imgsrc.'" /></div></div><div class="jsLivePostDivmain">'.nl2br($updates[$intA]->postText).'</div></div>');
            $postOptions = json_decode($updates[$intA]->options,true);
            if(isset($postOptions["pevent"]) && intval($postOptions["pevent"])){
                $query = "SELECT me.*,e.e_name,e.e_img,CONCAT(p.first_name,' ',p.last_name) as pname FROM #__bl_match_events as me"
                        . " JOIN #__bl_players as p ON me.player_id = p.id"
                        . " JOIN #__bl_events as e ON e.id = me.e_id"
                        . " WHERE me.id=".intval($postOptions["pevent"]);
                $db->setQuery($query);
                $eventObj = $db->loadObject();
                $eventImg = $eventObj->e_img;
                if(is_file(JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'bearleague'.DIRECTORY_SEPARATOR.'thumb'.DIRECTORY_SEPARATOR.$eventImg)){
                    $eventObj->e_img = '<img class="img-responsive emblpadd3" width="24" src="'.JUri::base().'media/bearleague/thumb/'.$eventImg.'" title="'.$eventObj->e_name.'"/>';
                    $eventObj->e_img_tline = JUri::base().'media/bearleague/thumb/'.$eventImg;
                }else{
                    $eventObj->e_img = $eventObj->e_name;
                    $eventObj->e_img_tline = '';
                }
                $eventObj->pname_tline = $eventObj->pname;
                $eventObj->pname = classJsportLink::player($eventObj->pname, $eventObj->player_id, $match->s_id);
                
                $query = "SELECT COUNT(*) FROM #__bl_match_events WHERE t_id = {$eventObj->t_id} AND match_id={$eventObj->match_id} AND minutes='".$eventObj->minutes."'";
                $db->setQuery($query);
                $eventObj->tlcount = (int)$db->loadResult();
                
                
                $events[] = $eventObj;
            }
        }
        
        //get all events
        ob_start();
        global $jsConfig;
        $rows = new classJsportMatch($match_id);
        $width = $jsConfig->get('teamlogo_height');
        
        $opposite_events = $jsConfig->get('opposite_events');
        if ($opposite_events) {
            $opposite_events = json_decode($opposite_events, true);
        } else {
            $opposite_events = array();
        }
        if($rows->lists['m_events_display'] == 1){
            $rows->getPlayerObj($rows->lists['m_events_home']);
            $rows->getPlayerObj($rows->lists['m_events_away']);
        }else{
            $rows->getPlayerObj($rows->lists['m_events_all']);
            $rows->getPlayerObj($rows->lists['m_events_home']);
            $rows->getPlayerObj($rows->lists['m_events_away']);
        }

        $partic_home = $rows->getParticipantHome();
        $partic_away = $rows->getParticipantAway();
        
        
        try{
            require_once JS_PATH_VIEWS_ELEMENTS . 'player_stat' . DIRECTORY_SEPARATOR . 'match-view-player-stat.php';
        }catch (Exception $e){
            echo $e->getMessage();
        }

        $plstat = ob_get_contents();
        ob_end_clean();
        
        

        ob_start();
        if (count($rows->lists['squard1']) || count($rows->lists['squard2'])) {
            $rows->getPlayerObj($rows->lists['squard1']);
            $rows->getPlayerObj($rows->lists['squard2']);
            $rows->getPlayerObj($rows->lists['squard1_res']);
            $rows->getPlayerObj($rows->lists['squard2_res']);
        }
        try{
            require_once JS_PATH_VIEWS_ELEMENTS . 'squad-list.php';
        }catch (Exception $e){
            echo $e->getMessage();
        }
        $plsquad = ob_get_contents();
        ob_end_clean();
        
        $hmev = json_encode($rows->lists['m_events_home']);
        $awev = json_encode($rows->lists['m_events_away']);
        
        $match_options = json_decode($match->options,true);
        $duration = 0;
        $jstimeline = json_decode($jsConfig->get('jstimeline',''));
        if(isset($match_options['duration'])){
            $duration = $match_options['duration'];
        }
        
        $timer = isset($match_options["current_time"])?$match_options["current_time"]:'';
        if(intval($timer)){
            $timer = $timer; 
        }
        
        $return_json = array(
            'error' => 0,
            'html' => json_encode($html),
            'score' => array($match->score1,$match->score2),
            'timer' => $timer,
            'events' => json_encode($events),
            'part' => array($match->team1_id,$match->team2_id),
            'plstat' => $plstat,
            'plsquad' => $plsquad,
            'postsin' => $allposts,
            'hmev' => $hmev,
            'awev' => $awev,
            'duration' => $duration,
        );
        echo json_encode($return_json);
        
        exit();
    }
    
    public function setMatchTimer(){
        $db = JFactory::getDBO();
        $match_id  = JRequest::getVar('match_id', 0, 'request', 'int');
        $current_time  = JRequest::getVar('current_time', '', 'request', 'string');
        if(!$this->isSeasonAdmin($match_id)){
            exit();
        }
        ob_clean();
        
        $db->setQuery("SELECT m.options FROM #__bl_match as m"
                    . " JOIN #__bl_matchday as md ON md.id = m.m_id"
                    . " WHERE m.id={$match_id}");
        $match_options = $db->loadResult();
        $match_options = json_decode($match_options,true);
        $match_options["current_time"] = addslashes($current_time);
        
        $db->setQuery("UPDATE #__bl_match SET options = '".json_encode($match_options)."'"
                    . " WHERE id={$match_id}");
        $db->query();            
                    
        exit();
    }    
    
    public function deleteRecord(){
        $db = JFactory::getDBO();
        $rid  = JRequest::getVar('rid', 0, 'request', 'int');
        $match_id  = JRequest::getVar('match_id', 0, 'request', 'int');
        if(!$this->isSeasonAdmin($match_id)){
            exit();
        }
        ob_clean();
        
        if($rid){
            $db->setQuery("SELECT * FROM #__bl_liveposts WHERE id = '".$rid."'");
            $record = $db->loadObject();
            $postOptions = json_decode($record->options,true);
            if(isset($postOptions["pevent"]) && intval($postOptions["pevent"])){
                if(intval($postOptions["pevent"]) > 0){
                    $db->setQuery("DELETE FROM #__bl_match_events WHERE id = '".intval($postOptions["pevent"])."'");
                    $db->query();
                    $db->setQuery("DELETE FROM #__bl_match_events WHERE additional_to = '".intval($postOptions["pevent"])."'");
                    $db->query();
                }

            }
            if(isset($postOptions["psubs"]) && intval($postOptions["psubs"])){
                $db->setQuery("DELETE FROM #__bl_subsin WHERE id = '".intval($postOptions["psubs"])."'");
                $db->query();
                
            }
                
            $db->setQuery("DELETE FROM #__bl_liveposts WHERE id = '".$rid."'");
            $db->query();
            echo "1";
        }
        die();
        
    }
    public function getRecord(){
        $db = JFactory::getDBO();
        $rid  = JRequest::getVar('rid', 0, 'request', 'int');
        $match_id  = JRequest::getVar('match_id', 0, 'request', 'int');
        if(!$this->isSeasonAdmin($match_id)){
            exit();
        }
        ob_clean();
        
        if($rid){

            $db->setQuery("SELECT * FROM #__bl_liveposts WHERE id = '".$rid."'");
            $record = $db->loadObject();
            $record_options = json_decode($record->options,true);
            if(isset($record_options["pevent"]) && $record_options["pevent"]){
                $query = "SELECT * FROM #__bl_match_events WHERE id=".intval($record_options["pevent"]);
                $db->setQuery($query);
                $record->eventObj = json_encode($db->loadObject());
                
                $query = "SELECT * FROM #__bl_match_events WHERE additional_to=".intval($record_options["pevent"]);
                $db->setQuery($query);
                $record->eventSubObj = json_encode($db->loadObjectList());
            }
            if(isset($record_options["psubs"]) && $record_options["psubs"]){
                $query = "SELECT * FROM #__bl_subsin WHERE id=".intval($record_options["psubs"]);
                $db->setQuery($query);
                $record->subsObj = json_encode($db->loadObject());
            }
            echo json_encode($record);
        }
        die();
        
    }
    
    public function editPost(){
        require_once 'components/com_joomsport/sportleague/sportleague.php';
        require_once JS_PATH_OBJECTS.'class-jsport-team.php';
        require_once JS_PATH_OBJECTS.'class-jsport-player.php';
        require_once JS_PATH_OBJECTS.'class-jsport-event.php';
        
        $db = JFactory::getDBO();
        $match_id  = JRequest::getVar('match_id', 0, 'request', 'int');
        $record_id  = JRequest::getVar('edit_record', 0, 'request', 'int');
        $post_text = JRequest::getVar( 'live_post_edit', '', 'post', 'string', JREQUEST_ALLOWRAW );
        $event_id  = JRequest::getVar('event_id_edit', 0, 'request', 'int');
        $playerz_id  = JRequest::getVar('playerz_id_edit', '', 'request', 'string');
        
        $teamsq_id  = JRequest::getVar('teamsq_id_edit', 0, 'request', 'int');
        $subs_out  = JRequest::getVar('jslive_subs_out_edit', 0, 'request', 'int');
        $subs_in  = JRequest::getVar('jslive_subs_in_edit', 0, 'request', 'int');
        ob_clean();
        $additional_post = '';
        $RecOptions = array();
        
        if(!$this->isSeasonAdmin($match_id)){
            exit();
        }
        
        
        $subev = JRequest::getVar('playerzEditSub_id', array(), 'request', 'array');
        $sub_ev_id  = JRequest::getVar('sub_ev_id_edit', 0, 'request', 'int');
        
        $post_text = '<div class="jsPostText">'.$post_text.'<div class="jsclear"></div></div>';
        
        $return_json = array();
        if($record_id){
            
            $query = "SELECT * FROM #__bl_liveposts WHERE id = {$record_id}";
            $db->setQuery($query);
            $recordObj = $db->loadObject();
            
            $recordOptions = json_decode($recordObj->options, true);
            
            if(isset($recordOptions["pevent"]) && intval($recordOptions["pevent"])>0){
                $db->setQuery("DELETE FROM #__bl_match_events WHERE additional_to=".intval($recordOptions["pevent"]));
                $db->query();
            }

            
            if($event_id && $playerz_id){
                $player = explode('*', $playerz_id);
                if(isset($player[0]) && intval($player[0]) && isset($player[1]) && intval($player[1])){
                    
                    if(isset($recordOptions["pevent"]) && $recordOptions["pevent"]){
                        $query = "UPDATE #__bl_match_events SET minutes='".addslashes($_POST["js_live_post_time_edit"])."',e_id={$event_id},t_id='".intval($player[1])."',player_id='".intval($player[0])."'"
                                . " WHERE id=".intval($recordOptions["pevent"]);
                        $db->setQuery($query);
                        $db->query();
                        
                        
                        $sube_player = '';
                
                        if($sub_ev_id && count($subev)){
                            $plOrd = 0;

                            foreach($subev as $pl){
                                $plis = explode('*', $pl);
                                $query = 'INSERT INTO #__bl_match_events(e_id,player_id,match_id,ecount,minutes,t_id,eordering,additional_to) VALUES('.intval($sub_ev_id).','.intval($plis[0]).','.$match_id.',1,"'.addslashes($_POST["js_live_post_time_edit"]).'",'.intval($plis[1]).','.$plOrd.','.intval($recordOptions["pevent"]).')';
                                $db->setQuery($query);
                                $db->query();


                                $playerObjSub = new classJsportPlayer(intval($plis[0]),0);
                                $p_nameSub = $playerObjSub->getName(false);
                                if($sube_player){
                                    $sube_player .= ", ";
                                }
                                $sube_player .= $p_nameSub; 

                                $plOrd++;
                            }

                            $SubeventObj = new classJsportEvent($sub_ev_id);
                            $sube_name = $SubeventObj->getEventName();

                        }
                        
                        $eventObj = new classJsportEvent($event_id);
                        $e_name = $eventObj->getEventName();

                        //$db->setQuery("SELECT CONCAT(first_name,' ',last_name) FROM #__bl_players WHERE id=".intval($player[0]));
                        //$p_name = $db->loadResult();

                        $playerObj = new classJsportPlayer(intval($player[0]));
                        $p_name = $playerObj->getName(false);

                        //$db->setQuery("SELECT t_name FROM #__bl_teams WHERE id=".intval($player[1]));
                        //$t_name = $db->loadResult();

                        $teamObj = new classJsportTeam(intval($player[1]));
                        $t_name = $teamObj->getName(false);
                        

                        $additional_post .= '<div class="jslivepost_events">' . $e_name .', '.$p_name .($sube_player?". ".$sube_name.": ".$sube_player:"").' ('.$t_name.')</div>';

                        $RecOptions["pevent"] = intval($recordOptions["pevent"]);
                        
                    }else{
                        $query = "SELECT MAX(eordering) FROM #__bl_match_events WHERE match_id={$match_id}";
                        $db->setQuery($query);
                        $eordering = (int) $db->loadResult();
                        $eordering++;

                        $query = "INSERT INTO #__bl_match_events(match_id,player_id,minutes,e_id,t_id,ecount,eordering)"
                        . " VALUES({$match_id},'".intval($player[0])."','".($_POST["js_live_post_time"])."',{$event_id},'".intval($player[1])."',1,{$eordering})";

                        $db->setQuery($query);
                        $db->query();
                        $player_event_id = $db->insertid();

                        $eventObj = new classJsportEvent($event_id);
                        $e_name = $eventObj->getEventName();

                        //$db->setQuery("SELECT CONCAT(first_name,' ',last_name) FROM #__bl_players WHERE id=".intval($player[0]));
                        //$p_name = $db->loadResult();

                        $playerObj = new classJsportPlayer(intval($player[0]));
                        $p_name = $playerObj->getName(false);

                        //$db->setQuery("SELECT t_name FROM #__bl_teams WHERE id=".intval($player[1]));
                        //$t_name = $db->loadResult();

                        $teamObj = new classJsportTeam(intval($player[1]));
                        $t_name = $teamObj->getName(false);

                        $additional_post .= '<div class="jslivepost_events">' . $e_name .', '.$p_name .' ('.$t_name.')</div>';

                        $RecOptions["pevent"] = $player_event_id;
                    }
                    
                    
                }
            }elseif(isset($recordOptions["pevent"]) && intval($recordOptions["pevent"])>0){
                $db->setQuery("DELETE FROM #__bl_match_events WHERE id=".intval($recordOptions["pevent"]));
                $db->query();
            }

            if($teamsq_id && $subs_in && $subs_out){

                $query = "SELECT md.s_id FROM "
                        . "#__bl_match as m"
                        . " JOIN #__bl_matchday as md ON m.m_id=md.id";
                $db->setQuery($query);
                $season_id = $db->loadResult();

                $query = "INSERT INTO #__bl_subsin(match_id,team_id,player_in,player_out,minutes,season_id)"
                    . " VALUES({$match_id},'".intval($teamsq_id)."',{$subs_in},{$subs_out},'".intval($_POST["js_live_post_time_edit"])."',{$season_id})";

                $db->setQuery($query);
                $db->query();
                $player_subs_id = $db->insertid();

                 $playerObj = new classJsportPlayer(intval($subs_in));
                $p_name = $playerObj->getName(false);

                $playerObj = new classJsportPlayer(intval($subs_out));
                $p2_name = $playerObj->getName(false);

                /*$db->setQuery("SELECT CONCAT(first_name,' ',last_name) FROM #__bl_players WHERE id=".intval($subs_in));
                $p_name = $db->loadResult();
                $db->setQuery("SELECT CONCAT(first_name,' ',last_name) FROM #__bl_players WHERE id=".intval($subs_out));
                $p2_name = $db->loadResult();*/

                //$db->setQuery("SELECT t_name FROM #__bl_teams WHERE id=".intval($teamsq_id));
                //$t_name = $db->loadResult();

                $teamObj = new classJsportTeam(intval($teamsq_id));
                $t_name = $teamObj->getName(false);

                $additional_post .= '<div class="jslivepost_subs">('.$t_name.') ' . $p2_name .'<img src="'.JUri::base().'components/com_joomsport/img/ico/out-new.png" />  '.$p_name .'<img src="'.JUri::base().'components/com_joomsport/img/ico/in-new.png" /></div>';

                $RecOptions["psubs"] = $player_subs_id;
                if(isset($recordOptions["psubs"]) && $recordOptions["psubs"]){
                    $db->setQuery("DELETE FROM #__bl_subsin WHERE id=".intval($recordOptions["psubs"]));
                    $db->query();
                }

            }elseif(!$teamsq_id && isset($recordOptions["psubs"]) && $recordOptions["psubs"]){
                $db->setQuery("DELETE FROM #__bl_subsin WHERE id=".intval($recordOptions["psubs"]));
                $db->query();
            }

            $js_live_twitter = JRequest::getVar('js_live_twitter_edit', '', 'request', 'string');

            if($js_live_twitter){
                $ch = curl_init(); 

                // set url 
                curl_setopt($ch, CURLOPT_URL, "https://publish.twitter.com/oembed?url=".$js_live_twitter); 

                //return the transfer as a string 
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

                // $output contains the output string 
                $output = curl_exec($ch); 

                // close curl resource to free up system resources 
                curl_close($ch);  
                if($output){
                    $output = json_decode($output, true);
                    if(isset($output["html"])){
                        $post_text .= '<div class="jslivetwitt">'.$output["html"].'</div>';
                    }
                }
                $RecOptions["twitter"] = $js_live_twitter;
            }

            $js_live_fb = JRequest::getVar('js_live_fb_edit', '', 'request', 'string');
            if($js_live_fb){
                $post_text .= '<div class="fb-post" data-href="'.$js_live_fb.'"></div>';
                $RecOptions["facebook"] = $js_live_fb;
            }




            $post_text = $additional_post . $post_text;

            $user = JFactory::getUser();

            

            $query = "UPDATE #__bl_liveposts SET minutes='".addslashes($_POST["js_live_post_time_edit"])."',postText='".addslashes($post_text)."',"
                    . "postIcon='".addslashes($_POST["e_img_edit"])."',options='".json_encode($RecOptions)."',postTime='".gmdate('Y-m-d H:i:s')."'"
                    . " WHERE id=".intval($record_id);
                    
            $db->setQuery($query);
            $db->query();
            $lpost_id = intval($record_id);
            $isimg = '';
            if(isset($_POST["e_img_edit"]) && $_POST["e_img_edit"]){
                $isimg = '<img src="media/bearleague/events/'.addslashes($_POST["e_img_edit"]).'" />';
            }
            //$html = '<div class="jsLivePostDiv">';
            $html = '<div class="jsLivePostDivTime">'.addslashes($_POST["js_live_post_time_edit"]).'<div>'.$isimg.'</div></div><div class="jsLivePostDivmain">'.$post_text.'</div>';
            $html .= '<div class="jsLivePostDivEditing" jsdata="'.$lpost_id.'"><i class="fa fa-times" aria-hidden="true"></i><i class="fa fa-pencil-square-o" aria-hidden="true"></i></div>';
            //$html .= '</div>';

            $return_json = array(
                'error' => 0,
                'html' => $html
            );

            echo json_encode($return_json);
        }
        exit();
    }
    
    private function isSeasonAdmin($match_id){
        $db = JFactory::getDBO();
        $canEdit = false;
        if($match_id){
            $db->setQuery("SELECT md.s_id FROM #__bl_match as m JOIN #__bl_matchday as md ON md.id=m.m_id WHERE m.id= ".intval($match_id));
            $season_id = $db->loadResult();
            $user = JFactory::getUser();
            if($user->id && $season_id){
                $db->setQuery('SELECT COUNT(*) '
                    .'FROM #__bl_feadmins'
                    .' WHERE season_id = '.intval($season_id)
                    .' AND user_id = '.$user->id    );
                $isAdmin = $db->loadResult();
                if($isAdmin){
                    $canEdit = true;
                }
            }
        }
        return $canEdit;
    }
    public function getSubEvents(){
        $db = JFactory::getDBO();
        $eventid = JRequest::getVar('event_id', 0, '', 'int');
        
        $query = "SELECT sub.e_name as name,sub.id"
                . " FROM #__bl_events as e"
                . " JOIN #__bl_events_depending as de ON de.event_id = e.id"
                . " JOIN #__bl_events as sub ON de.subevent_id = sub.id"
                . " WHERE e.id = ".intval($eventid).""
                . " ORDER BY e.e_name";
        $db->setQuery($query);
        $row = $db->loadObject();
        echo json_encode($row);
        exit();
    }
}
?>