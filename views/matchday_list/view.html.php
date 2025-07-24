<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class JoomsportViewmatchday_list extends JViewLegacy
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
        $total = $this->_model->_total;
        $pagination = $this->_model->_pagination;

        $this->addToolbar();
        $user = JFactory::getUser();

        $this->assignRef('user',        $user);
        $this->assignRef('lists',        $lists);
        $this->assignRef('rows',        $items);
        $this->assignRef('page',    $pagination);
        $this->assignRef('model',    $this->_model);

        require_once dirname(__FILE__).'/tmpl/default'.($tpl ? '_'.$tpl : '').'.php';
    }

    protected function addToolbar()
    {
        if (JFactory::getUser()->authorise('core.edit', 'com_joomsport')) {
            JToolBarHelper::addNew('matchday_add');
            JToolBarHelper::editList('matchday_edit');
        }
        JToolBarHelper::title(JText::_('BLBE_MATCHDAYLIST'), 'match.png');
        if (JFactory::getUser()->authorise('core.delete', 'com_joomsport')) {
            JToolBarHelper::deleteList('', 'matchday_del', JText::_('BLBE_DELETE'));
        }
        JToolbarHelper::divider();

        JToolbarHelper::custom('autogeneration', 'new', '', JText::_('BLBE_AUTOGENERATION'), false);
    }
}
