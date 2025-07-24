<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class JoomsportViewtemplate_edit extends JView
{
    public $_model = null;
    public $labels = array();
    public function __construct(&$model)
    {
        $this->_model = $model;
        $this->labels['win'] = JText::_('BLBE_BET_WINNER');
        $this->labels['lose'] = JText::_('BLBE_BET_LOSER');
        $this->labels['draw'] = JText::_('BLBE_BET_DRAW');
    }
    public function display($tpl = null)
    {
        global $mainframe, $option;

        $db = &JFactory::getDBO();
        $uri = &JFactory::getURI();

        // Get data from the model
        $items = $this->_model->_data;
        $lists = $this->_model->_lists;

        $this->addToolbar($this->_model->_mode);

        $editor = JEditor::getInstance();
        $this->assignRef('editor',        $editor);
        $this->assignRef('lists',        $lists);
        $this->assignRef('row',        $items);
        $this->assignRef('labels',    $this->labels);

        require_once dirname(__FILE__).'/tmpl/default'.($tpl ? '_'.$tpl : '').'.php';
    }

    protected function addToolbar($edit)
    {
        $text = ($edit ? JText::_('Edit') : JText::_('New'));
        JToolBarHelper::title(JText::_('Template').': <small><small>[ '.$text.' ]</small></small>', 'team.png');
        JToolBarHelper::save('template_save');
        JToolBarHelper::apply('template_apply');
        if ($edit) {
            JToolBarHelper::cancel('template_list', 'Close');
        } else {
            JToolBarHelper::cancel('template_list');
        }
    }
}
