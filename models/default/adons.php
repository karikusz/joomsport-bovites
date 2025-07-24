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

class adonsJSModel extends JSPRO_Models
{
    public $_data = null;

    public function __construct()
    {
        parent::__construct();
    }
    public function getData()
    {
        $query = 'SELECT * FROM #__bl_addons';
        $this->db->setQuery($query);
        $this->_data = $this->db->loadObjectList();
    }
    public function addonInstall()
    {
        jimport('joomla.installer.helper');
        jimport('joomla.filesystem.path');
        $filename = $_FILES['addon_installer']['name'];
        $baseDir = JPATH_ROOT.'/tmp/';
        if (file_exists($baseDir)) {
            if (is_writable($baseDir)) {
                if (move_uploaded_file($_FILES['addon_installer']['tmp_name'], $baseDir.$filename)) {
                    if (JPath::setPermissions($baseDir.$filename)) {
                        $msg = '';
                    } else {
                        $msg = JText::_('BLBE_UPL_PERM');
                    }
                } else {
                    $msg = JText::_('BLBE_UPL_MOVE');
                }
            } else {
                $msg = JText::_('BLBE_UPL_TMP');
            }
        } else {
            $msg = JText::_('BLBE_UPL_TMPEX');
        }
        if ($msg != '') {
            JError::raiseError(500, $msg);
        }
        $retval = JInstallerHelper::unpack($baseDir.$filename);
        if (count($retval)) {
            if (is_dir($retval['dir'].'/BE/')) {
                $this->_copy_directory($retval['dir'].'/BE/', JPATH_ROOT.'/administrator/components/com_joomsport/');
            }
            if (is_dir($retval['dir'].'/LanguageBE/')) {
                $this->_copy_directory($retval['dir'].'/LanguageBE/', JPATH_ROOT.'/administrator/language/');
            }
            if (is_dir($retval['dir'].'/FE/')) {
                $this->_copy_directory($retval['dir'].'/FE/', JPATH_ROOT.'/components/com_joomsport/');
            }
            if (is_dir($retval['dir'].'/LanguageFE/')) {
                $this->_copy_directory($retval['dir'].'/LanguageFE/', JPATH_ROOT.'/language/');
            }
            

            $xml = JFactory::getXML($retval['dir'].'/addon.xml');

            if (file_exists($retval['dir'].'/addon.sql.xml')) {
                $sql = JFactory::getXML($retval['dir'].'/addon.sql.xml');
                $queries = $sql->query;
                if ($queries) {
                    foreach ($queries as $q) {
                        $this->db->setQuery($q);
                        $this->db->query();
                    }
                }
            }

            if ($xml) {
                $xmlopt = json_encode($xml->options);

                $query = "INSERT INTO #__bl_addons(name,title,description,version,published,options) VALUES('{$xml->name}','{$xml->title}','{$xml->description}','{$xml->version}','0','".addslashes($xmlopt)."')";
                $this->db->setQuery($query);
                $this->db->query();
            }
        }
    }
    public function _copy_directory($source, $destination)
    {
        if (is_dir($source)) {
            @mkdir($destination);
            $directory = dir($source);
            while (false !== ($readdirectory = $directory->read())) {
                if ($readdirectory == '.' || $readdirectory == '..') {
                    continue;
                }
                $PathDir = $source.'/'.$readdirectory;
                if (is_dir($PathDir)) {
                    $this->_copy_directory($PathDir, $destination.'/'.$readdirectory);
                    continue;
                }
                copy($PathDir, $destination.'/'.$readdirectory);
            }

            $directory->close();
        } else {
            copy($source, $destination);
        }
    }
    public function addonDel()
    {
        $cid = JRequest::getVar('cid', array(0), '', 'array');
        JArrayHelper::toInteger($cid, array(0));
        if (count($cid)) {
            $cids = implode(',', $cid);
            $query = 'DELETE FROM `#__bl_addons` WHERE id IN ('.$cids.')';
            $this->db->setQuery($query);
            $this->db->query();
        }
    }
}
