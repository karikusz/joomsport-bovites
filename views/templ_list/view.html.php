<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class JoomsportViewtempl_list extends JView
{
    public $_model = null;
    public function __construct(&$model)
    {
        $this->_model = $model;
    }
    public function display($tpl = null)
    {
        global $mainframe, $option;

        $db = &JFactory::getDBO();
        $uri = &JFactory::getURI();

        // Get data from the model
        $items = $this->_model->_data;
        $total = $this->_model->_total;
        $pagination = $this->_model->_pagination;

        $this->addToolbar();

        $this->assignRef('user',        JFactory::getUser());
        $this->assignRef('lists',        $lists);
        $this->assignRef('rows',        $items);
        $this->assignRef('page',    $pagination);

        require_once dirname(__FILE__).'/tmpl/default'.($tpl ? '_'.$tpl : '').'.php';
    }

    protected function addToolbar()
    {
        JToolBarHelper::title(JText::_('Templates List'), 'tourn.png');
        JToolBarHelper::deleteList('', 'templ_del', 'Delete');
        JToolBarHelper::editList('templ_edit');
        JToolBarHelper::addNew('templ_add');
    }
}
