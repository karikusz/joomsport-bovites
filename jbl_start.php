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
// no direct access
defined('_JEXEC') or die('Restricted access');

$extension = JTable::getInstance('extension');
$id = $extension->find(array('element' => 'com_joomsport'));
$extension->load($id);
$componentInfo = json_decode($extension->manifest_cache, true);

$joomsportVersion = $componentInfo['version'];

?>
<style>
    .jsBEheader{
    background: #f9f9f9;
    border-radius: 2px 2px 0 0;
    border-bottom: 1px solid #e9e9e9;
    padding: 1em;
    font-weight: bold;
    font-size: 14px;
    line-height: 1;
}
.jsBepanel {
    background: #fff;
    border: 1px solid #cfcfcf;
    -webkit-box-shadow: none;
    box-shadow: none;
    margin-bottom: 25px;
    border-radius: 3px;

}
.jsBEsettings{
    padding: 1em;
}
.jsrespdiv6 table td:first-child {
    font-weight: bold;
    padding-right: 20px;
}
.jsrespdiv6 table td {
    padding: 5px;
}
.jsBEsettings table td {
    padding-top: 5px;
    padding-bottom: 5px;
}
</style>    
<div><?php echo JText::sprintf('BLBE_JSGUIDE','<a href="https://joomsport.com/support/documentation/pro-documentation.html#quickstart_guide" target="_blank">','</a>'); ?>
                </div>
<div class="jsrespdiv6">
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_PRODUCT_INFO'); ?>
                </div>
                <div class="jsBEsettings">
                    <table>
                        <tr>
                            <td>
                                <?php echo JText::_('BLBE_EDITION');?>:
                            </td>
                            <td>
                                JoomSport Professional
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo JText::_('BLBE_ACTIVE_VERSION');?>:
                            </td>
                            <td>
                                <?php echo $joomsportVersion;?>
                            </td>
                        </tr>
                        
                        <tr>
                            <td>
                                <?php echo JText::_('BLBE_LATVERSION');?>:
                            </td>
                            <td>
                                <?php 
                                if(function_exists('curl_exec')){
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, 'https://joomsport.com/index2.php?option=com_chkversion&id=3&no_html=1&tmpl=component');
                                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                                    curl_setopt($ch, CURLOPT_POST, 1);
                                    curl_setopt($ch, CURLOPT_HEADER, 0);

                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                    echo $response = trim(curl_exec($ch));

                                    curl_close($ch);
                                }else{
                                    echo trim(@file_get_contents('https://joomsport.com/index2.php?option=com_chkversion&id=3&no_html=1&tmpl=component'));
                                }
                                ?>
                            </td>
		
                        </tr>
                        <tr>
                            <td>

				<?php echo JText::_('BLBE_COPYRIGHT');?>:

                            </td>

                            <td>

                                    &copy; BearDev

                            </td>
                        </tr>
                        <tr>

                            <td>

                                    <?php echo JText::_('BLBE_MAINSITE');?>:

                            </td>

                            <td>

                                    <a href="https://www.JoomSport.com">www.JoomSport.com</a>

                            </td>

                        </tr>
                        <tr>

                            <td>

                                    <?php echo JText::_('BLBE_DEVELOPER');?>:

                            </td>

                            <td>

                                    <a href="https://www.beardev.com" target="_blank">BearDev web development company</a>

                            </td>

                        </tr>
                        <tr>

                            <td>

                                    <?php echo JText::_('BLBE_LICENSE');?>:

                            </td>

                            <td>

                                    <a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GPLv2</a>

                            </td>

                        </tr>
                        <tr>

                            <td>

                                    <?php echo JText::_('BLBE_TRADEMARKS');?>:

                            </td>

                            <td>

                                    <a href="https://joomsport.com/joomsport-trademarks.html" target="_blank"><?php echo JText::_('BLBE_TRADEMARKS_POLICY');?></a>

                            </td>

                        </tr>
                    </table>
                </div>
            </div>
        </div>







