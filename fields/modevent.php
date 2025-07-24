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
class JFormFieldModEvent extends JFormField

{


    /**
	 * Element name.
	 *
	 * @var		string
	 */
     protected $type = 'event';

    protected function getInput()

    {
        // Load the modal behavior script.
        JHtml::_('behavior.modal', 'a.modal');

        $db = JFactory::getDBO();
        // Build the script.
        $script = array();

        $script[] = '	function jSelectJS_'.$this->id.'(id, title, catid, object) {';

        $script[] = '		document.id("'.$this->id.'_id").value = id;';

        $script[] = '		document.id("'.$this->id.'_name").value = title;';

        $script[] = '		SqueezeBox.close();';

        $script[] = '	}';

        // Add the script to the document head.
        JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));


        $query = "SELECT * FROM #__bl_events WHERE (player_event = '1' OR player_event = '2') ORDER BY e_name";


        $db->setQuery($query);

        $events = $db->loadObjectList();


        if (!empty($events)) {

            $events_sel[] = JHTML::_('select.option',  '', JText::_('MOD_JS_TT_SELEVENT'), 'id', 'e_name');

            if (count($events)) {

                $events_sel = array_merge($events_sel, $events);


            }


        } else {

            $events_sel[0] = JHTML::_('select.option',  0, 'No events', 'id', 'e_name');


        }


        $html = JHTML::_('select.genericlist',   $events_sel, 'jform[params][event_id]', 'class="chzn-done" size="1"', 'id', 'e_name', $this->value);
        //$html .= "\n".'<input type="hidden" id="'.$name.'_id" name="'.$fieldName.'" value="'.$this->value.'" />';

        return $html;


    }


}
