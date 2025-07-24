<?php

/* ------------------------------------------------------------------------
  # JoomSport Professional
  # ------------------------------------------------------------------------
  # BearDev development company
  # Copyright (C) 2011 JoomSport.com. All Rights Reserved.
  # @license - http://joomsport.com/news/license.html GNU/GPL
  # Websites: http://www.JoomSport.com
  # Technical Support:  Forum - http://joomsport.com/helpdesk/
  ------------------------------------------------------------------------- */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.form.formfield');

class JFormFieldModEventBox extends JFormField {

    /**
     * Element name.
     *
     * @var		string
     */
    protected $type = 'event';

    protected function getInput() {
        // Load the modal behavior script.
        JHtml::_('behavior.modal', 'a.modal');

        $db = JFactory::getDBO();
        // Build the script.
        $script = array();

        $script[] = '	function jSelectJS_' . $this->id . '(id, title, catid, object) {';

        $script[] = '		document.id("' . $this->id . '_id").value = id;';

        $script[] = '		document.id("' . $this->id . '_name").value = title;';

        $script[] = '		SqueezeBox.close();';

        $script[] = '	}';

        // Add the script to the document head.
        JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));


        $query = "SELECT * FROM #__bl_events WHERE player_event IN ('1', '2')  ORDER BY e_name";

        $db->setQuery($query);

        $events1 = $db->loadObjectList();

        $query = "SELECT * FROM #__bl_box_fields WHERE complex=0 AND published=1 ORDER BY name";

        $db->setQuery($query);

        $boxes = $db->loadObjectList();

        $events_sel = array();

        if (count($events1)) {
            $events_sel[1]['items'] = JHTML::_('select.option', '', JText::_('MOD_JS_TT_SELEVENT'), 'id', 'e_name');
            
            $group = array();
            $group['value'] = 'Player events';
            $group['text'] = 'Player events';
            $group['items'] = $events1;
            
            $events_sel['Player events'] = $group;
            }

        
        if (!count($events_sel)) {
            $events_sel[0] = JHTML::_('select.option', 0, 'No events', 'id', 'e_name');
        }
        
        $boxesA = array();
        for ($intA = 0; $intA < count($boxes); $intA++) {
            $options = json_decode($boxes[$intA]->options, true);
            $add = false;
            if ($boxes[$intA]->ftype != '1') {
                $add = true;
            } else {
                if ($options['calc'] != 4 && $options['depend1'] && $options['depend2']) {
                    $add = true;
                }
            }

            if ($add) {
                $boxesA[] = JHTML::_('select.option', 'box_' . $boxes[$intA]->id, $boxes[$intA]->name, 'id', 'e_name');
            }
        }
        
        if (count($boxesA)) {
            $group = array();
            $group['value'] = 'Box Score';
            $group['text'] = 'Box Score';
            $group['items'] = $boxesA;
            
            $events_sel['Box Score'] = $group;            
        }

        $attr = array(
            'id'          => 'jform_event_id',
            'list.select' => $this->value,
            'list.attr'   => 'class="chzn-done" size="1" ',
            'option.key' => 'id',
            'option.text' => 'e_name'
        );
        $html = JHtml::_('select.groupedlist', $events_sel, 'jform[params][event_id]', $attr);

        return $html;
    }

}
