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

class list_countrJSModel extends JSPRO_Models
{
    public $_data = null;
    public $_lists = null;
    public $_total = null;

    public $_pagination = null;
    public $limit = null;
    public $limitstart = null;

    public $cid = null;
    public function __construct()
    {
        parent::__construct();

        $mainframe = JFactory::getApplication();

        $this->cid = JRequest::getVar('countryid', '', 'GET', 'int');

        // Get the pagination request variables
        $this->limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $this->limitstart = $mainframe->getUserStateFromRequest('com_joomsport.limitstart_countr', 'limitstart', 0, 'int');

        // In case limit has been changed, adjust limitstart accordingly
        $this->limitstart = ($this->limit != 0 ? (floor($this->limitstart / $this->limit) * $this->limit) : 0);
        $this->getPagination();

        $this->getData();

        $this->_lists['languages'] = $this->getLanguages();
        $this->_lists['translation'] = array();
        if(count($this->_lists['languages']) && $this->cid){
            $this->_lists['translation'] = $this->getTranslation('country_'.$this->cid);

        }
    }

    public function getData()
    {
        if ($this->cid) {
            $query = 'SELECT * FROM #__bl_countries WHERE id = '.$this->cid;
            $this->db->setQuery($query);
            $this->_lists['country'] = $this->db->loadObject();
        }

        if (empty($this->_data)) {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->limitstart, $this->limit);
            $error = $this->db->getErrorMsg();
            if ($error) {
                return JError::raiseError(500, $error);
            }
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
        $this->db->setQuery($query, $this->limitstart, $this->limit);
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

        $query = 'SELECT * FROM #__bl_countries ';

        $query .= $orderby;

        return $query;
    }

    public function _buildContentOrderBy()
    {
        $mainframe = JFactory::getApplication();

        $this->_lists['sortfield'] = $mainframe->getUserStateFromRequest('com_joomsport.list_countr_field', 'sortfield', 'country', 'string');
        $this->_lists['sortway'] = $mainframe->getUserStateFromRequest('com_joomsport.list_countr_way', 'sortway', 'ASC', 'string');

        $orderby = ' ORDER BY '.$this->_lists['sortfield'].' '.$this->_lists['sortway'];

        return $orderby;
    }

    public function saveCountr()
    {
        $countryid = JRequest::getVar('countryid', '', 'POST', 'int');
        $country = JRequest::getVar('country', '', 'POST', 'string');
        $code = JRequest::getVar('ccode', '', 'POST', 'string');

        if ($countryid) { //update
            $query = "UPDATE #__bl_countries SET ccode = '".$code."',country = '".$country."' WHERE id = ".$countryid;
            $this->db->setQuery($query);
            $this->db->query();
        } elseif (!$countryid && $country) { //insert
            $query = "INSERT INTO #__bl_countries(ccode,country) VALUES('".$code."','".$country."')";
            $this->db->setQuery($query);
            $this->db->query();
            $countryid = $this->db->insertid();
        }

        //translation
        if(isset($_POST['translation']) && count($_POST['translation'])){
            $this->db->setQuery(
                "DELETE FROM #__bl_translations WHERE jsfield='country_".$countryid."'"
            );
            $this->db->query();
            foreach ($_POST['translation'] as $key => $value) {
                $value['c_name'] = str_replace("\r\n", "", $value['c_name']);
                $translation = json_encode($value);
                $translation = nl2br($translation);
                $translation = str_replace("\r\n", "", $translation);

                $this->db->setQuery(
                    "INSERT INTO #__bl_translations(jsfield,translation,languageID)"
                    ." VALUES('country_".$countryid."','".addslashes($translation)."','".$key."')"
                );
                $this->db->query();
            }
        }
    }
    public function deleteCountr()
    {
        $cid = JRequest::getVar('cid', array(0), '', 'array');

        $cids = implode(',', $cid);
        $query = 'DELETE FROM #__bl_countries WHERE id IN('.$cids.')';
        $this->db->setQuery($query);
        $this->db->query();
    }
}
