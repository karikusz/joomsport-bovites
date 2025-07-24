<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class JoomsportViewplayer_list extends JViewLegacy
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
        $season_id = $this->_model->season_id;
        $total = $this->_model->_total;
        $pagination = $this->_model->_pagination;

        $this->addToolbar();
        $user = JFactory::getUser();
        $this->assignRef('user',        $user);
        $this->assignRef('lists',        $lists);
        $this->assignRef('rows',        $items);
        $this->assignRef('page',    $pagination);
        $this->assignRef('season_id',        $season_id);

        require_once dirname(__FILE__).'/tmpl/default'.($tpl ? '_'.$tpl : '').'.php';
    }

    protected function addToolbar()
    {
        if (JFactory::getUser()->authorise('core.edit', 'com_joomsport')) {
            JToolBarHelper::addNew('player_add');
            JToolBarHelper::editList('player_edit');
        }
        JToolBarHelper::title(JText::_('BLBE_PLAYER_LIST'), 'player.png');
        if (JFactory::getUser()->authorise('core.delete', 'com_joomsport')) {
            JToolBarHelper::deleteList('', 'player_del', JText::_('BLBE_DELETE'));
        }
        require_once JPATH_COMPONENT_SITE.DIRECTORY_SEPARATOR.'sportleague'.DIRECTORY_SEPARATOR.'sportleague.php';
        classJsportPlugins::get("addButtonPlayerList", array());
    }
}
