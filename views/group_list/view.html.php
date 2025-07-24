<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class JoomsportViewgroup_list extends JViewLegacy
{
    public $_model = null;
    public function __construct(&$model)
    {
        $this->_model = $model;
    }
    public function display($tpl = null)
    {
        global $mainframe, $option;

        $db = JFactory::getDBO();
        $uri = JFactory::getURI();

        // Get data from the model
        $items = $this->_model->_data;
        $lists = $this->_model->_lists;
        $pagination = $this->_model->_pagination;

        $this->addToolbar();

        $this->assignRef('rows',        $items);
        $this->assignRef('lists',        $lists);
        $this->assignRef('page',    $pagination);

        require_once dirname(__FILE__).'/tmpl/default'.($tpl ? '_'.$tpl : '').'.php';
    }

    protected function addToolbar()
    {
        JToolBarHelper::addNew('group_add');
        JToolBarHelper::editList('group_edit');
        JToolBarHelper::title(JText::_('BLBE_GROUPLIST'), 'group.png');
        JToolBarHelper::deleteList('', 'group_del', JText::_('BLBE_DELETE'));
    }
}
