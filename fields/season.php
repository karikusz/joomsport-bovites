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
class JFormFieldSeason extends JFormField

{


    /**
	 * Element name.
	 *
	 * @var		string
	 */
    protected $type = 'season';

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


        $article = new stdClass();
        // Add the script to the document head.
        JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

        if ($this->id == 'jform_request_sid') {

            if ($this->value) {

                $query = "SELECT CONCAT(t.name,' ',s.s_name) as name FROM #__bl_tournament as t, #__bl_seasons as s WHERE s.t_id = t.id AND s.s_id = ".$this->value;

                $db->setQuery($query);


                $rows = $db->loadObjectList();

                if (isset($rows[0])) {

                    $row = $rows[0];

                    $article->title = $row->name;


                }


            } else {

                $article->title = JText::_('BLBE_SELSEASN');


            }

            $task = 'season_menu';


        } else {

            if ($this->value) {

                $query = 'SELECT group_name FROM #__bl_groups  WHERE id = '.$this->value;

                $db->setQuery($query);


                $rows = $db->loadObjectList();

                if (isset($rows[0])) {

                    $row = $rows[0];

                    $article->title = $row->group_name;


                }


            } else {

                $article->title = JText::_('BLBE_SELGROUP');


            }

            $task = 'group_menu';


        }

        // Setup variables for display.
        $html = array();

        $link = 'index.php?option=com_joomsport&amp;task='.$task.'&amp;tmpl=component&amp;function=jSelectJS_'.$this->id;


        $title = htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8');

        // The current user display field.
        $html[] = '<div class="fltlft">';

        $html[] = '  <input type="text" id="'.$this->id.'_name" value="'.$title.'" disabled="disabled" size="35" />';

        $html[] = '</div>';

        // The user select button.
        $html[] = '<div class="button2-left">';

        $html[] = '  <div class="blank">';

        $html[] = '	<a class="modal" title="'.JText::_('BLBE_SELECT').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 450}}">'.JText::_('BLBE_SELECT').'</a>';

        $html[] = '  </div>';

        $html[] = '</div>';

        // The active article id field.
        if (0 == (int) $this->value) {
            $value = '';
        } else {
            $value = (int) $this->value;
        }

        // class='required' for client side validation
        $class = '';

        if ($this->required) {

            $class = ' class="required modal-value"';


        }


        $html[] = '<input type="hidden" id="'.$this->id.'_id"'.$class.' name="'.$this->name.'" value="'.$value.'" />';


        return implode("\n", $html);


    }


}
