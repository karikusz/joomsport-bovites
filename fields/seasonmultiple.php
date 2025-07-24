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
class JFormFieldSeasonMultiple extends JFormField

{


    /**
	 * Element name.
	 *
	 * @var		string
	 */
    protected $type = 'seasonmultiple';

    protected function getInput()

    {

        $db = JFactory::getDBO();

        $query = "SELECT CONCAT(t.name,' ',s.s_name) as name, s.s_id as id FROM #__bl_tournament as t, #__bl_seasons as s WHERE s.t_id = t.id ORDER BY t.name,s.s_name";

        $db->setQuery($query);


        $seasons = $db->loadObjectList();



        // Setup variables for display.
        $html = array();


        // The current user display field.
        $html[] = '<div class="fltlft">';

        $html[] = '<select id="'.$this->id.'_id" name="'.$this->name.'[]" multiple>';
        for($intA=0;$intA<count($seasons);$intA++){
            $html[] = '<option value="'.$seasons[$intA]->id.'" '.(is_array($this->value) && in_array($seasons[$intA]->id,$this->value) ? " selected":"").'>'.$seasons[$intA]->name.'</option>';
        }
        $html[] = '</select>';

       $html[] = '</div>';



        return implode("\n", $html);


    }


}
