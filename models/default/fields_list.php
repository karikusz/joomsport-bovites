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
// No direct access.
defined('_JEXEC') or die;

require dirname(__FILE__).'/../models.php';

class fields_listJSModel extends JSPRO_Models
{
    public $_data = null;
    public $_lists = null;
    public $_total = null;

    public $_pagination = null;
    public $limit = null;
    public $limitstart = null;
    public $filtr_type = null;

    public function __construct()
    {
        parent::__construct();

        $mainframe = JFactory::getApplication();

        // Get the pagination request variables
        $this->limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $this->limitstart = $mainframe->getUserStateFromRequest('com_joomsport.limitstart_fields', 'limitstart', 0, 'int');
        $this->filtr_type = $mainframe->getUserStateFromRequest('com_joomsport.filtr_type', 'filtr_type', -1, 'int');
        // In case limit has been changed, adjust limitstart accordingly
        $this->limitstart = ($this->limit != 0 ? (floor($this->limitstart / $this->limit) * $this->limit) : 0);
        if ($this->getTotal() <= $this->limitstart) {
            $this->limitstart = 0;
        }
        $this->getPagination();

        $this->getData();
        $query = 'SELECT DISTINCT (p.id)
					FROM #__bl_extra_filds AS p';
        $this->db->setQuery($query);
        $this->_lists['totteams'] = $this->db->loadResult();
    }

    public function getData()
    {
        if (empty($this->_data)) {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->limitstart, $this->limit);
            $error = $this->db->getErrorMsg();
            if ($error) {
                return JError::raiseError(500, $error);
            }

            $is_field[] = JHTML::_('select.option',  -1, JText::_('BLBE_SELTYPE'), 'id', 't_name');
            $is_field[] = JHTML::_('select.option',  0, JText::_('BLBE_PLAYER'), 'id', 't_name');
            $is_field[] = JHTML::_('select.option',  1, JText::_('BLBE_TEAM'), 'id', 't_name');
            $is_field[] = JHTML::_('select.option',  2, JText::_('BLBE_MATCH'), 'id', 't_name');
            $is_field[] = JHTML::_('select.option',  3, JText::_('BLBE_SEASON'), 'id', 't_name');
            $is_field[] = JHTML::_('select.option',  4, JText::_('BLBE_CLUB'), 'id', 't_name');
            $is_field[] = JHTML::_('select.option',  6, JText::_('BLBE_PERSON'), 'id', 't_name');
            $javascript = " onChange='document.adminForm.limitstart.value=0;javascript:document.adminForm.submit();'";
            $this->_lists['is_type'] = JHTML::_('select.genericlist',   $is_field, 'filtr_type', 'class="inputbox" size="1"'.$javascript, 'id', 't_name', $this->filtr_type);
        }

        return $this->_data;
    }

    public function getTotal()
    {
        if (empty($this->_total)) {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }

        return $this->_total;
    }
    public function _getListCount($query)
    {
        $this->db->setQuery($query);
        $tot = $this->db->loadObjectList();

        return count($tot);
    }

    public function _getList($query, $limitstart, $limit)
    {
        $this->db->setQuery($query, $limitstart, $limit);
        $tot = $this->db->loadObjectList();

        return $tot;
    }

    public function getPagination()
    {
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->limitstart, $this->limit);
        }

        return $this->_pagination;
    }

    public function _buildQuery()
    {
        $orderby = $this->_buildContentOrderBy();

        $query = 'SELECT * FROM #__bl_extra_filds '.(($this->filtr_type != -1) ? ('WHERE type='.$this->filtr_type) : '');

        $query .= $orderby;

        return $query;
    }

    public function _buildContentOrderBy()
    {
        $mainframe = JFactory::getApplication();

        $this->_lists['sortfield'] = $mainframe->getUserStateFromRequest('com_joomsport.field_list_field', 'sortfield', 'ordering', 'string');
        $this->_lists['sortway'] = $mainframe->getUserStateFromRequest('com_joomsport.field_list_way', 'sortway', 'ASC', 'string');

        $orderby = ' ORDER BY '.$this->_lists['sortfield'].' '.$this->_lists['sortway'];

        return $orderby;
    }
}
