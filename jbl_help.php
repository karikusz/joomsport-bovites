<?php
/*
BearDev.com
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
require_once JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_joomsport'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'tabs.php';
$etabs = new esTabs();
global $joomsportVersion;
require_once 'components/com_joomsport/helpers/jshtml.php';
JhtmlJshtml::showFeedback();
?>
<div class='jsrespdiv10'>
    <div class="jsrespdiv12">
        <div class="jsBepanel">
            <div class="jsBEheader">
                <?php echo JText::_('BLBE_MENHLP'); ?>
            </div>
            <div class="jsBEsettings" style="padding:0px;">
        <!-- <tab box> -->
                <ul class="tab-box">
                        <?php
                        echo $etabs->newTab(JText::_('BLBE_SUPPORT'), 'main_conf', '', 'vis');
                        echo $etabs->newTab(JText::_('BLBE_ABOUT'), 'about_conf', '');

                        ?>
                </ul>	
                <div style="clear:both"></div>
            </div>
        </div>    
    </div>
    <div id="main_conf_div" class="tabdiv">
        <div class="jsrespdiv6">
            <div class="jsBepanel">
                
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_TITLE_DOCUMENTATION'); ?>
                </div>

                <div class="jsBEsettings jsLinks" onclick="location.href='https://joomsport.com/support/documentation/pro-documentation.html'">
                    <div class="jhelpicons"><img src="components/com_joomsport/img/documentation.png"></div>
                    <div class="jhelpdescr"><?php echo JText::_('BLBE_DESCR_DOCUMENTATION'); ?></div>
                </div>
   
            </div>
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_TITLE_FAQ'); ?>
                </div>
                <div class="jsBEsettings jsLinks" onclick="location.href='https://joomsport.com/support/faq.html'">
                    <div class="jhelpicons"><img src="components/com_joomsport/img/faq.png"></div>
                    <div class="jhelpdescr"><?php echo JText::_('BLBE_DESCR_FAQ'); ?></div>
                </div>
            </div>
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_TITLE_LIVECHAT'); ?>
                </div>
                <div class="jsBEsettings jsLinks"  onclick="location.href='https://joomsport.com'">
                    <div class="jhelpicons"><img src="components/com_joomsport/img/chat.png"></div>
                    <div class="jhelpdescr"><?php echo JText::_('BLBE_DESCR_LIVECHAT'); ?></div>
                </div>
            </div>
        </div>
        <div class="jsrespdiv6 jsrespmarginleft2">
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_TITLE_FORUM'); ?>
                </div>
                <div class="jsBEsettings jsLinks"  onclick="location.href='https://joomsport.com/support/forum.html'">
                    <div class="jhelpicons"><img src="components/com_joomsport/img/forum.png"></div>
                    <div class="jhelpdescr"><?php echo JText::_('BLBE_DESCR_FORUM'); ?></div>
                </div>
            </div>
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_TITLE_HELPDESK'); ?>
                </div>
                <div class="jsBEsettings jsLinks"  onclick="location.href='https://joomsport.com/support/helpdesk.html'">
                    <div class="jhelpicons"><img src="components/com_joomsport/img/support.png"></div>
                    <div class="jhelpdescr"><?php echo JText::_('BLBE_DESCR_HELPDESK'); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div id="about_conf_div" class="tabdiv"  style="display:none;">
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
                                    curl_setopt($ch, CURLOPT_URL, 'http://joomsport.com/index2.php?option=com_chkversion&id=3&no_html=1&tmpl=component');
                                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                                    curl_setopt($ch, CURLOPT_POST, 1);
                                    curl_setopt($ch, CURLOPT_HEADER, 0);

                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                    echo $response = trim(curl_exec($ch));

                                    curl_close($ch);
                                }else{
                                    echo trim(@file_get_contents('http://joomsport.com/index2.php?option=com_chkversion&id=3&no_html=1&tmpl=component'));
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

                                    <a href="http://www.JoomSport.com">www.JoomSport.com</a>

                            </td>

                        </tr>
                        <tr>

                            <td>

                                    <?php echo JText::_('BLBE_DEVELOPER');?>:

                            </td>

                            <td>

                                    <a href="http://www.beardev.com" target="_blank">BearDev web development company</a>

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

                                    <a href="http://joomsport.com/joomsport-trademarks.html" target="_blank"><?php echo JText::_('BLBE_TRADEMARKS_POLICY');?></a>

                            </td>

                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <br /><br />
        <div style="clear:both;"></div>
        <div style="clear:both; margin-top:40px; text-align:center;">
       Do you like our product? Please, <a href="http://extensions.joomla.org/extensions/extension/sports-a-games/sports/joomsport-pro" target="_blank">post a review on Joomla! extensions directory.</a> We benefit from your 
            <a style="margin-right: 15px;" href="http://extensions.joomla.org/extensions/extension/sports-a-games/sports/joomsport-pro" target="_blank">feedback!</a><br />
<br />            Follow us on  
            <a style="margin-right: 15px;" href="https://twitter.com/beardev" target="_blank">Twitter</a>
            become a fan on 
            <a style="margin-right: 15px;" href="https://www.facebook.com/pages/BearDev/130697180026" target="_blank"> Facebook</a>
              or subscribe to our Blog 
            <a href=" http://beardev.com/blog" target="_blank"> <img src=" http://beardev.com/images/130x130-logo_beardev-latest.png " style="height:21px;" /></a>
        </div>

    <input type="hidden" name="jscurtab" id="jscurtab" value="" />
</div>