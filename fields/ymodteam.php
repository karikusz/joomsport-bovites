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
jimport('joomla.form.formfield');
class JFormFieldYmodteam extends JFormField

{


    /**
	 * Element name.
	 *
	 * @var		string
	 */
    protected $type = 'team';

    protected function getInput()

    {

        $db = JFactory::getDBO();

        $doc = JFactory::getDocument();

        $mod_id = (int) $this->form->getValue('id');


        $query = 'SELECT s.s_id as id FROM #__bl_tournament as t, #__bl_seasons as s WHERE s.t_id = t.id ORDER BY t.name LIMIT 1';

        $db->setQuery($query);

        $season_id = $db->loadResult();


        if ($mod_id) {

            $query = 'Select params FROM #__modules WHERE id='.$mod_id;

            $db->setQuery($query);

            $paramsl = $db->loadResult();


            $params = new JRegistry();

            $params->loadString($paramsl);


            $season = $params->get('sidgid');


            $ex = explode('|', $season);

            $season_id = $ex[0];
            //$gr_id = $ex[1];
            //$season_id = $params->get("season_id");

        }

        //$fieldName	= 'team['.$name.']';
        if ($season_id) {
            $query = 'SELECT t.t_single FROM #__bl_seasons as s LEFT JOIN #__bl_tournament as t ON t.id = s.t_id WHERE s.s_id='.$season_id;
            $db->setQuery($query);
            $t_single = $db->loadResult();
            if ($t_single) {
                //$query = "SELECT CONCAT(t.first_name,' ',t.last_name) as name,t.id FROM #__bl_players as t , #__bl_season_players as st WHERE st.player_id = t.id AND st.season_id = ".($season_id)." AND t.t_yteam = 1 ORDER BY t.first_name";
            } else {
                $query = 'SELECT t.id,t.t_name as name FROM #__bl_teams as t , #__bl_season_teams as st WHERE st.team_id = t.id AND st.season_id = '.($season_id).' AND t.t_yteam = 1 ORDER BY t.t_name';
                $db->setQuery($query);
                $team = $db->loadObjectList();
            }

            $teams[] = JHTML::_('select.option',  0, ($t_single ? JText::_('BLBE_NOTEAM') : JText::_('BLBE_SELTEAM')), 'id', 'name');
            if (isset($team)) {
                $teams = array_merge($teams, $team);
            }
        } else {
            $query = 'SELECT t_name as name,id FROM #__bl_teams WHERE t_yteam = 1 ';
            $db->setQuery($query);

            $rows = $db->loadObjectList();

            if (!empty($rows)) {
                $pos[] = JHTML::_('select.option',  0, JText::_('BLBE_SELTEAM'), 'id', 'name');
                $teams = array_merge($pos, $rows);
            } else {
                $teams[0] = JHTML::_('select.option',  0, 'No team', 'id', 'name');
            }
        }


        $html = "<div id='fgatemid_js'>".JHTML::_('select.genericlist',   $teams, 'jform[params][team_id]', 'class="chzn-done" size="1"', 'id', 'name', $this->value).'</div>';
        //$html .= "\n".'<input type="hidden" id="'.$name.'_id" name="'.$fieldName.'" value="'.$this->value.'" />';

        return $html;


    }


}
