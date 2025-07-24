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

class group_menuJSModel extends JSPRO_Models
{
    public $_data = null;

    public $_total = null;
    public $_lists = null;
    public $_pagination = null;
    public $limit = null;
    public $limitstart = null;

    public function __construct()
    {
        parent::__construct();
        JHtml::_('behavior.framework');
        $mainframe = JFactory::getApplication();

        // Get the pagination request variables
        $this->limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $this->limitstart = $mainframe->getUserStateFromRequest('com_joomsport.limitstart', 'limitstart', 0, 'int');

        // In case limit has been changed, adjust limitstart accordingly
        $this->limitstart = ($this->limit != 0 ? (floor($this->limitstart / $this->limit) * $this->limit) : 0);
        $this->getPagination();
        $this->getData();
    }

    public function getData()
    {
        if (empty($this->_data)) {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->limitstart, $this->limit);
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

    public function _getList($query)
    {
        $this->db->setQuery($query);
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
        $query = "SELECT g.*,CONCAT(t.name,' ',s.s_name) as name
		            FROM #__bl_groups as g, #__bl_tournament as t, #__bl_seasons as s
		            WHERE s.t_id = t.id AND s.s_id=g.s_id
		            ORDER BY g.group_name";

        return $query;
    }
}
