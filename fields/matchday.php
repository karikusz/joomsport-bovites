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
class JFormFieldMatchday extends JFormField
{
    /**
      * Element name.
      *
      * @var		string
      */
     protected $type = 'matchday';
    protected function getInput()
    {
        $db = JFactory::getDBO();
        $doc = JFactory::getDocument();

        //$fieldName	= $control_name.'['.$name.']';
        $article = new stdClass();
        $article->title = '';
        if ($this->value) {
            $query = 'SELECT * FROM #__bl_matchday WHERE id = '.$this->value;
            $db->setQuery($query);

            $rows = $db->loadObjectList();
            if (isset($rows[0])) {
                $row = $rows[0];
                $article->title = $row->m_name;
            }
        } else {
            $article->title = JText::_('BLBE_SELMATCHDAY');
        }
        $script = array();
        $script[] = '	function jSelectArticle(id, title, catid, object) {';
        $script[] = '		document.id("'.$this->id.'_id").value = id;';
        $script[] = '		document.id("'.$this->id.'_name").value = title;';
        $script[] = '		SqueezeBox.close();';
        $script[] = '	}';

        // Add the script to the document head.
        JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
        $link = 'index.php?option=com_joomsport&amp;task=matchday_menu&amp;tmpl=component';
        JHTML::_('behavior.modal', 'a.modal');
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
