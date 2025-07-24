<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class JoomsportViewadons extends JViewLegacy
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
        $this->_model->getData();
        $items = $this->_model->_data;

        $this->addToolbar();

        $this->assignRef('row',        $items);

        require_once dirname(__FILE__).'/tmpl/default'.($tpl ? '_'.$tpl : '').'.php';
    }

    protected function addToolbar()
    {
        JToolBarHelper::title(JText::_('BLBE_ADDONSLIST'), 'config.png');
        JToolBarHelper::deleteList('', 'del_adons', JText::_('BLBE_DELETE'));
    }
}
