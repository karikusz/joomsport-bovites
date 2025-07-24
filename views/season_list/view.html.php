<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class JoomsportViewseason_list extends JViewLegacy
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
        $total = $this->_model->_total;
        $pagination = $this->_model->_pagination;
        $this->_model->getFilter();
        $lists = $this->_model->_lists;
        $this->addToolbar();

        $this->assignRef('lists',        $lists);
        $this->assignRef('rows',        $items);
        $this->assignRef('page',    $pagination);

        require_once dirname(__FILE__).'/tmpl/default'.($tpl ? '_'.$tpl : '').'.php';
    }

    protected function addToolbar()
    {
        JToolBarHelper::title(JText::_('BLBE_SEASONLIST'), 'season.png');
        if (JFactory::getUser()->authorise('core.edit', 'com_joomsport')) {
            JToolBarHelper::addNew('season_add');
            JToolBarHelper::editList('season_edit');
            JToolBarHelper::publishList('season_publish');
            JToolBarHelper::unpublishList('season_unpublish');
        }
        if (JFactory::getUser()->authorise('core.delete', 'com_joomsport')) {
            JToolBarHelper::deleteList('', 'season_del', JText::_('BLBE_DELETE'));
        }
        if (JFactory::getUser()->authorise('core.edit', 'com_joomsport')) {
            JToolBarHelper::custom('season_copy', 'copy', '', JText::_('BLBE_COPY'));
        }
    }
}
