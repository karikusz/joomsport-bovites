<?php
defined('_JEXEC') or die;

class jsHelperStages
{
    public static function getStagesManual($seasonID){
        $db = JFactory::getDBO();

        $db->setQuery("SELECT m.* FROM #__bl_maps as m "
            ." JOIN #__bl_seas_maps as sm ON m.id = sm.map_id WHERE sm.season_id = {$seasonID} AND m.separate_events = '1'");

        $stages = $db->loadObjectList();
        return $stages;


    }

    public static function getStagesAutomatic($seasonID){
        $db = JFactory::getDBO();

        $db->setQuery("SELECT m.* FROM #__bl_maps as m "
            ." JOIN #__bl_seas_maps as sm ON m.id = sm.map_id WHERE sm.season_id = {$seasonID} AND m.separate_events = '2' AND time_to > time_from ORDER BY time_from,time_to");

        $stages = $db->loadObjectList();
        return $stages;

    }

    public static function getEventsByStages($matchID, $stageID){
        $db = JFactory::getDBO();

        $query = "SELECT me.*,me.id as meid,ev.*,p.id as playerid,CONCAT(p.first_name,' ',p.last_name) as p_name"
            ." FROM  #__bl_match_events as me"
            . " JOIN #__bl_events as ev ON me.e_id = ev.id AND me.match_id = ".$matchID
            . ' JOIN #__bl_players as p ON me.player_id = p.id'
            ." WHERE ev.player_event = '1' AND ev.dependson=''"
            ." AND me.stage_id = " . $stageID

            //.' '.(count($pl_list) ? 'AND me.player_id IN('.implode(',', $pl_list).')' : '')
            .' GROUP BY me.id'
            .' ORDER BY CAST(me.minutes AS UNSIGNED), me.eordering';
        $db->setQuery($query);
        $pevents = $db->loadObjectList();

        return $pevents;
    }

    public static function getEventsByTime($matchID, $stageObj){
        $db = JFactory::getDBO();

        $query = "SELECT me.*,me.id as meid,ev.*,p.id as playerid,CONCAT(p.first_name,' ',p.last_name) as p_name"
            ." FROM  #__bl_match_events as me"
            . " JOIN #__bl_events as ev ON me.e_id = ev.id AND me.match_id = ".$matchID
            . ' JOIN #__bl_players as p ON me.player_id = p.id'
            ." WHERE ev.player_event = '1' AND ev.dependson=''"
            ." AND me.stage_id = 0"
            ." AND CAST(me.minutes AS UNSIGNED) >= ".$stageObj->time_from
            ." AND CAST(me.minutes AS UNSIGNED) <= ".$stageObj->time_to

            //.' '.(count($pl_list) ? 'AND me.player_id IN('.implode(',', $pl_list).')' : '')
            .' GROUP BY me.id'
            .' ORDER BY CAST(me.minutes AS UNSIGNED), me.eordering';
        $db->setQuery($query);
        $pevents = $db->loadObjectList();

        return $pevents;
    }
    public static function getNotStageEvents($matchID, $not_in_obj){
        $db = JFactory::getDBO();
        $not_in = array();
        for($intA=0;$intA<count($not_in_obj);$intA++){

            for ($intB=0;$intB<count($not_in_obj[$intA]['events']);$intB++){
                $not_in[] = $not_in_obj[$intA]['events'][$intB]->meid;
            }

        }

        $query = "SELECT me.*,me.id as meid,ev.*,p.id as playerid,CONCAT(p.first_name,' ',p.last_name) as p_name"
            ." FROM  #__bl_match_events as me"
            . " JOIN #__bl_events as ev ON me.e_id = ev.id AND me.match_id = ".$matchID
            . ' JOIN #__bl_players as p ON me.player_id = p.id'

            ." WHERE ev.player_event = '1' AND ev.dependson=''"
            .(count($not_in)?" AND me.id NOT IN (".implode(",",$not_in).")":"")


            //.' '.(count($pl_list) ? 'AND me.player_id IN('.implode(',', $pl_list).')' : '')
            .' GROUP BY me.id'
            .' ORDER BY CAST(me.minutes AS UNSIGNED), me.eordering';
        $db->setQuery($query);
        $pevents = $db->loadObjectList();

        return $pevents;
    }

    public static function getMatchEvents($matchID, $seasonID){
        $eventsByStages = array();
        $auto = self::getStagesAutomatic($seasonID);
        $manual = self::getStagesManual($seasonID);
        if($auto && count($auto)){
            foreach ($auto as $stage){
                $eventsByStages[] = array("events" => self::getEventsByTime($matchID, $stage), "stage" => $stage);
            }
        }
        if($manual && count($manual)){
            foreach ($manual as $stage){
                $eventsByStages[] = array("events" => self::getEventsByStages($matchID, $stage->id), "stage" => $stage);
            }
        }

        return $eventsByStages;

    }

    public static function getSubEvents($eventID){
        $db = JFactory::getDBO();

        $query = "SELECT (subev.e_id) as subevID, GROUP_CONCAT(subev.player_id) as subevPl,evSub.e_name as subEn, GROUP_CONCAT(CONCAT(pSub.first_name,' ',pSub.last_name) SEPARATOR ', ') as plFM"
            ." FROM  #__bl_match_events as subev"
            . ' JOIN #__bl_players as pSub ON subev.player_id = pSub.id '
            . " JOIN #__bl_events as evSub ON subev.e_id = evSub.id "
            ." WHERE subev.additional_to = " . $eventID
            //.' GROUP BY subev.id'
            .' ORDER BY subev.eordering, CAST(subev.minutes AS UNSIGNED)';
        $db->setQuery($query);
        $pevents = $db->loadObject();

        return $pevents;
    }
}
