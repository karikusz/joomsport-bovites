<?php
/* ------------------------------------------------------------------------
  # JoomSport Professional
  # ------------------------------------------------------------------------
  # BearDev development company
  # Copyright (C) 2011 JoomSport.com. All Rights Reserved.
  # @license - http://joomsport.com/news/license.html GNU/GPL
  # Websites: http://www.JoomSport.com
  # Technical Support:  Forum - http://joomsport.com/helpdesk/
  ------------------------------------------------------------------------- */
// no direct access
defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');
$lists = $this->lists;
require_once 'components/com_joomsport/helpers/jshtml.php';
JhtmlJshtml::showFeedback();
require_once JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_joomsport' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'tabs.php';
$etabs = new esTabs();
?>

<script type="text/javascript" src="components/com_joomsport/color_piker/201a.js"></script>
<script type="text/javascript">
    function delete_logo() {
        getObj("logoiddiv").innerHTML = '';
    }
    Joomla.submitbutton = function (task) {
        submitbutton(task);
    }
    function submitbutton(pressbutton) {
        var form = document.adminForm;
        if (pressbutton == 'save_config') {
            var srcListName = 'your_teams_id';
            var srcList = eval('form.' + srcListName);

            var srcLen = srcList.length;

            for (var i = 0; i < srcLen; i++) {
                srcList.options[i].selected = true;
            }
        }
        submitform(pressbutton);
        return;
    }

    function addMatchStatus() {
        if (jQuery("#custstat_name").val() && jQuery("#custstat_shortname").val()) {
            var tr = jQuery("<tr>");
            tr.append('<td><input type="hidden" name="mstatusesId[]" value="0" /><a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('BLBE_DELETE'); ?>"><img src="components/com_joomsport/img/publish_x.png"  border="0" alt="Delete"></a></td>');
            tr.append('<td><input type="text" name="mstatusesName[]" value="' + jQuery("#custstat_name").val() + '" /></td>');
            tr.append('<td><input type="text" name="mstatusesShortName[]" value="' + jQuery("#custstat_shortname").val() + '" /></td>');
            jQuery('#matchStatusesTable').append(tr);
            jQuery("#custstat_name").val("");
            jQuery("#custstat_shortname").val("");
        }
    }
    function Delete_tbl_row(element) {
        var del_index = element.parentNode.parentNode.sectionRowIndex;
        var tbl_id = element.parentNode.parentNode.parentNode.parentNode.id;
        element.parentNode.parentNode.parentNode.deleteRow(del_index);
    }

</script>
<form action="index.php?option=com_joomsport" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">

    <div class="jsrespdiv12">
        <div class="jsBepanel">
            <div class="jsBEheader">
                <?php echo JText::_('BLBE_HEAD_SETUPCONFIG'); ?>
            </div>
            <div class="jsBEsettings" style="padding:0px;">
    <!-- <tab box> -->
                <ul class="tab-box">
                    <?php
                    echo $etabs->newTab(JText::_('BLBE_GENERAL'), 'main_cfg', '', 'vis');
                    echo $etabs->newTab(JText::_('BLBE_REGISTR'), 'reg_cfg', '');
                    echo $etabs->newTab(JText::_('BLBE_ADMRIGHTS'), 'admrigh_cfg', '');
                    echo $etabs->newTab(JText::_('BLBE_ESPORTCONF'), 'esport_cfg', '');
                    echo $etabs->newTab(JText::_('BLBE_TEAMLAYOUT'), 'teamlayout_cfg', '');
                    ?>
                </ul>
                <div style="clear:both"></div>
            </div>
        </div>    
    </div>  
    <div id="main_cfg_div" class="tabdiv">

        <div class="jsrespdiv6">
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_GENERAL'); ?>
                </div>
                <div class="jsBEsettings">
                    <table class="adminlistsNoBorder">
                        <tr>
                            <td width="270">
                                <?php echo JText::_('BLBE_DATECONFIG'); ?>
                            </td>
                            <td>
                                <?php echo $lists['data_sel'] ?>
                            </td>

                        </tr>

                        <tr>
                            <td><?php echo JText::_('BLBE_UNABMATCHCOM'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'mcomments', 'class="inputbox" ', $lists['mcomments']); ?>
                                    </fieldset>
                                </div>    
                            </td>
                        </tr>


                        <tr>
                            <td><?php echo JText::_('BLBE_UNBCLUB'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'enbl_club', 'class="inputbox" ', $lists['enbl_club']); ?>
                                    </fieldset>
                                </div>

                            </td>
                        </tr>



                        <tr>
                            <td><?php echo JText::_('BLBE_UNABVENUE'); ?></td>
                            <td><?php echo $lists['unbl_venue']; ?></td>

                        </tr>



                        <tr>
                            <td><?php echo JText::_('BLBE_BRANDING_ON'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jsbrand_on', 'class="inputbox" ', $lists['jsbrand_on']); ?>
                                    </fieldset>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_EPANEL_IMG'); ?></td>
                            <td>
                                <input type="file" name="t_logo" style="width:158px;" /><input class="btn btn-small" type="button" style="cursor:pointer;" value="<?php echo JText::_('BLBE_UPLOAD'); ?>" onclick="submitbutton('save_config');" />
                                <br />
                                <br />
                                <div id="logoiddiv">
                                    <?php
                                    if ($lists['jsbrand_epanel_image'] && is_file('..' . $lists['jsbrand_epanel_image'])) {
                                        $url = JURI::root() . $lists['jsbrand_epanel_image'];
                                        echo '<img src="' . $url . '" style="height:38px;" />';
                                        echo '<input type="hidden" name="istlogo" value="1" />';
                                        ?>
                                        <a href="javascript:void(0);" title="<?php echo JText::_('BLBE_REMOVE');
                                        ?>" onClick="javascript:delete_logo();"><img src="<?php echo JURI::base();
                                        ?>components/com_joomsport/img/publish_x.png" title="Remove" /></a>
                                    </div>
                                    <?php
                                }
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_EDITCOUNTR'); ?>
                                <span class="editlinktip hasTip" title="<?php echo JText::_('BLBE_EDITCOUNTR'); ?>::<?php echo JText::_('BLBE_EDITCOUNTRV'); ?>"><img class="imgQuest" src="components/com_joomsport/img/quest.png" bOrder="0" /></span>
                            </td>
                            <td><a href="index.php?option=com_joomsport&amp;task=list_countr">&nbsp;<?php echo JText::_('BLBE_EDITCOUNTRS'); ?></a></td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_GROUP_BOXSCORE'); ?></td>
                            <td><?php echo $lists['boxExtraField']; ?></td>

                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_MULTILANGUAGE'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'multilanguage', 'class="inputbox" ', $lists['multilanguage']); ?>
                                    </fieldset>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_LIVEMATCH'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'js_livematches', 'class="inputbox" ', $lists['js_livematches']); ?>
                                    </fieldset>
                                </div>

                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_QUICK_MD_CREATION'); ?>
                </div>
                <div class="jsBEsettings">
                    <table class="">
                        <tr>
                            <th align="left"><?php echo JText::_('BLBE_FIELD_MDAY'); ?></th>
                            <th><?php echo JText::_('BLBE_SHOW_ON_PAGE'); ?></th>
                        </tr>
                        <tr>
                            <td width="280">
                                <?php echo JText::_('BLBE_ET'); ?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <div class="controls">
                                            <label for="mdf_et0" id="mdf_et0-lbl" class="radio btn">

                                                <input type="radio" name="mdf[mdf_et]" id="mdf_et0" value="0" <?php echo $lists['mday_fields']->mdf_et ? '' : 'checked="checked"'; ?> class="inputbox"><?php echo JText::_('JNO') ?>
                                            </label>
                                            <label for="mdf_et1" id="mdf_et1-lbl" class="radio btn">

                                                <input type="radio" name="mdf[mdf_et]" id="mdf_et1" value="1" <?php echo $lists['mday_fields']->mdf_et ? 'checked="checked"' : ''; ?> class="inputbox"><?php echo JText::_('JYES') ?>
                                            </label>
                                        </div>

                                    </fieldset>
                                </div>    
                            </td>

                        </tr>
                        <tr>
                            <td width="200">
                                <?php echo JText::_('JSTATUS'); ?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <div class="controls">
                                            <label for="mdf_played0" id="mdf_played0-lbl" class="radio btn">

                                                <input type="radio" name="mdf[mdf_played]" id="mdf_played0" value="0" <?php echo $lists['mday_fields']->mdf_played ? '' : 'checked="checked"'; ?> class="inputbox"><?php echo JText::_('JNO') ?>
                                            </label>
                                            <label for="mdf_played1" id="mdf_played1-lbl" class="radio btn">

                                                <input type="radio" name="mdf[mdf_played]" id="mdf_played1" value="1" <?php echo $lists['mday_fields']->mdf_played ? 'checked="checked"' : ''; ?> class="inputbox"><?php echo JText::_('JYES') ?>
                                            </label>
                                        </div>

                                    </fieldset>
                                </div> 
                            </td>

                        </tr>
                        <tr>
                            <td width="200">
                                <?php echo JText::_('BLBE_DATE'); ?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <div class="controls">
                                            <label for="mdf_date0" id="mdf_date0-lbl" class="radio btn">

                                                <input type="radio" name="mdf[mdf_date]" id="mdf_date0" value="0" <?php echo $lists['mday_fields']->mdf_date ? '' : 'checked="checked"'; ?> class="inputbox"><?php echo JText::_('JNO') ?>
                                            </label>
                                            <label for="mdf_date1" id="mdf_date1-lbl" class="radio btn">

                                                <input type="radio" name="mdf[mdf_date]" id="mdf_date1" value="1" <?php echo $lists['mday_fields']->mdf_date ? 'checked="checked"' : ''; ?> class="inputbox"><?php echo JText::_('JYES') ?>
                                            </label>
                                        </div>

                                    </fieldset>
                                </div> 
                            </td>

                        </tr>
                        <tr>
                            <td width="200">
                                <?php echo JText::_('BLBE_TIME'); ?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <div class="controls">
                                            <label for="mdf_time0" id="mdf_time0-lbl" class="radio btn">

                                                <input type="radio" name="mdf[mdf_time]" id="mdf_time0" value="0" <?php echo $lists['mday_fields']->mdf_time ? '' : 'checked="checked"'; ?> class="inputbox"><?php echo JText::_('JNO') ?>
                                            </label>
                                            <label for="mdf_time1" id="mdf_time1-lbl" class="radio btn">

                                                <input type="radio" name="mdf[mdf_time]" id="mdf_time1" value="1" <?php echo $lists['mday_fields']->mdf_time ? 'checked="checked"' : ''; ?> class="inputbox"><?php echo JText::_('JYES') ?>
                                            </label>
                                        </div>

                                    </fieldset>
                                </div> 
                            </td>

                        </tr>
                        <tr>
                            <td width="200">
                                <?php echo JText::_('BLBE_VENUE'); ?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <div class="controls">
                                            <label for="mdf_venue0" id="mdf_venue0-lbl" class="radio btn">

                                                <input type="radio" name="mdf[mdf_venue]" id="mdf_venue0" value="0" <?php echo $lists['mday_fields']->mdf_venue ? '' : 'checked="checked"'; ?> class="inputbox"><?php echo JText::_('JNO') ?>
                                            </label>
                                            <label for="mdf_venue1" id="mdf_venue1-lbl" class="radio btn">

                                                <input type="radio" name="mdf[mdf_venue]" id="mdf_venue1" value="1" <?php echo $lists['mday_fields']->mdf_venue ? 'checked="checked"' : ''; ?> class="inputbox"><?php echo JText::_('JYES') ?>
                                            </label>
                                        </div>

                                    </fieldset>
                                </div> 
                            </td>

                        </tr>
                        <?php
                        if (count($lists['mday_extra'])) {
                            foreach ($lists['mday_extra'] as $extra) {
                                $extraname = 'extra_' . $extra->id;
                                ?>
                                <tr>
                                    <td width="200">
                                        <?php echo $extra->name;
                                        ?>
                                    </td>
                                    <td>
                                        <div class="controls">
                                            <fieldset class="radio btn-group">
                                                <div class="controls">

                                                    <label for="mdf_extra<?php echo $extra->id ?>0" id="mdf_extra<?php echo $extra->id ?>0-lbl" class="radio btn">

                                                        <input type="radio" name="mdf[extra_<?php echo $extra->id ?>]" id="mdf_extra<?php echo $extra->id ?>0" value="0" <?php echo (isset($lists['mday_fields']->{$extraname}) && $lists['mday_fields']->{$extraname}) ? '' : 'checked="checked"';
                                        ?> class="inputbox"><?php echo JText::_('JNO') ?>
                                                    </label>
                                                    <label for="mdf_extra<?php echo $extra->id ?>1" id="mdf_extra<?php echo $extra->id ?>1-lbl" class="radio btn">

                                                        <input type="radio" name="mdf[extra_<?php echo $extra->id ?>]" id="mdf_extra<?php echo $extra->id ?>1" value="1" <?php echo (isset($lists['mday_fields']->{$extraname}) && $lists['mday_fields']->{$extraname}) ? 'checked="checked"' : '';
                                        ?> class="inputbox"><?php echo JText::_('JYES') ?>
                                                    </label>
                                                </div>

                                            </fieldset>
                                        </div> 
                                    </td>

                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </table>    
                </div>    
            </div>
        </div>
        <div class="jsrespdiv6 jsrespmarginleft2">
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_HEAD_TEAM_HGL'); ?>
                </div>
                <div class="jsBEsettings">
                    <table class="adminlistsNoBorder">
                        <tr>
                            <td width="30%">
                                <?php echo JText::_('BLBE_HIGHLIGHT_TEAMS'); ?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'highlight_team', 'class="inputbox" ', $lists['highlight_team']); ?>
                                    </fieldset>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="jsEnblHGL">
                                <?php echo JText::_('BLBE_YTEAMCOLOR'); ?>
                            </td>
                            <td class="jsEnblHGL">
                                <div id="colorpicker201" class="colorpicker201"></div>
                                <input class="btn btn-small" type="button" style="cursor:pointer;" onclick="showColorGrid2('yteam_color', 'sample_1');" value="...">&nbsp;<input type="text" name="yteam_color" id="yteam_color" size="5" style="width:70px;margin-bottom: 0px;" maxlength="30" value="<?php echo $lists['yteam_color']; ?>" /><input type="text" id="sample_1" size="1"  style="margin-bottom: 0px;" value="" style="background-color:<?php echo $lists['yteam_color'] ?>" class="color-kind" />
                            </td>

                        </tr>
                        <tr>
                            <th colspan="2" class="jsEnblHGL">
                                <?php echo JText::_('BLBE_YTEAM'); ?>
                            </th>
                        </tr>
                        <tr>
                            <td colspan="2" class="jsEnblHGL">
                                <table  border="0">
                                    <tr>

                                        <td width="150">
                                            <div class="selectedlistdescr"><?php echo JText::_('BLBE_AVAILABLE') ?></div>
                                            <?php echo $this->lists['teams']; ?>
                                        </td>
                                        <td valign="middle" width="60" align="center">
                                            <input class="btn" type="button" style="cursor:pointer;" value=">>" onClick="javascript:JS_addSelectedToList('adminForm', 'teams_id', 'your_teams_id');" /><br />
                                            <input class="btn" type="button" style="cursor:pointer;" value="<<" onClick="javascript:JS_delSelectedFromList('adminForm', 'your_teams_id', 'teams_id');" />
                                        </td>
                                        <td >
                                            <div class="selectedlistdescr"><?php echo JText::_('BLBE_SELECTED') ?></div>
                                            <?php echo $this->lists['yteams']; ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <script>
                        if ('<?php echo $lists['highlight_team']; ?>' != '1') {
                            jQuery(".jsEnblHGL").hide();
                        }
                    </script>    
                </div>
            </div>
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_CUSTOM_MATCH_STATUSES'); ?>
                </div>
                <div class="jsBEsettings">
                    <table class="tblStatuses">
                        <thead>
                            <tr>
                                <th width="50"></th>
                                <th><?php echo JText::_('BLBE_CUSTOM_MATCH_NAME'); ?></th>
                                <th><?php echo JText::_('BLBE_CUSTOM_MATCH_SHORTNAME'); ?></th>
                            </tr>
                        </thead>
                        <tbody id="matchStatusesTable">
                            <?php
                            if (count($this->lists['mstatuses'])) {
                                foreach ($this->lists['mstatuses'] as $mstat) {
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="mstatusesId[]" value="<?php echo $mstat->id;
                                    ?>" />
                                            <a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('BLBE_DELETE');
                                    ?>">
                                                <img src="components/com_joomsport/img/publish_x.png"  border="0" alt="Delete"></a>
                                        </td>
                                        <td>
                                            <input type="text" name="mstatusesName[]" value="<?php echo $mstat->stName;
                                    ?>" />
                                        </td>
                                        <td>
                                            <input type="text" name="mstatusesShortName[]" value="<?php echo $mstat->stShort;
                                    ?>" />
                                        </td>

                                        <?php
                                    }
                                }
                                ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>

                                </td>
                                <td>
                                    <input type="text" class="text" name="custstat_name" id="custstat_name" value="" />
                                </td>
                                <td>
                                    <input type="text" class="text" name="custstat_shortname" id="custstat_shortname" value="" />
                                    <input type="button" class="btn" style="margin-bottom:9px;margin-left:6px;" onclick="addMatchStatus();" value="<?php echo JText::_('BLBE_ADD'); ?>" />
                                </td>

                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>   

        </div>      
    </div>
    <div id="reg_cfg_div" class="tabdiv" style="display:none;">
        <div class="jsrespdiv12">
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_GENERAL'); ?>
                </div>
                <div class="jsBEsettings">
                    <table style="width:auto;" class="adminlistsNoBorder">


                        <tr>
                            <td width="250"><?php echo JText::_('BLBE_UNABLEPLREG'); ?></td>

                            <td colspan='2' width="200">
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'player_reg', 'class="inputbox" ', $lists['player_reg']); ?>
                                    </fieldset>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo JText::_('BLBE_PLAYERCANJOIN'); ?>
                            </td>
                            <td colspan='2'>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'esport_join_team', 'class="inputbox" ', $lists['esport_join_team']); ?>
                                    </fieldset>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <th><?php echo JText::_('BLBE_FIELD'); ?></th>
                            <th width="100"><?php echo JText::_('BLBE_ONREGPAGE'); ?></th>
                            <th><?php echo JText::_('BLBE_REQUIRED'); ?></th>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_LASTNAME'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'reg_lastname', 'class="inputbox" ', $lists['reg_lastname']); ?>
                                    </fieldset>
                                </div>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'reg_lastname_rq', 'class="inputbox" ', $lists['reg_lastname_rq']); ?>
                                    </fieldset>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_NICKNAME'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'nick_reg', 'class="inputbox" ', $lists['nick_reg']); ?>
                                    </fieldset>
                                </div>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'nick_reg_rq', 'class="inputbox" ', $lists['nick_reg_rq']); ?>
                                    </fieldset>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_COUNTRY'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'country_reg', 'class="inputbox" ', $lists['country_reg']); ?>
                                    </fieldset>
                                </div>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'country_reg_rq', 'class="inputbox" ', $lists['country_reg_rq']); ?>
                                    </fieldset>
                                </div>
                            </td>
                        </tr>


                    </table>
                </div>    
            </div>

            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_PLAYEREXTRAFIELDS'); ?>
                </div>
                <div class="jsBEsettings">
                    <table  style="width:auto;" class="adminlistsNoBorder">
                        <?php
                        if (count($lists['adf_player'])) {
                            ?>
                            <tr>
                                <th><?php echo JText::_('BLBE_FIELD');
                            ?></th>
                                <th><?php echo JText::_('BLBE_ONREGPAGE');
                            ?></th>
                                <th><?php echo JText::_('BLBE_REQUIRED');
                            ?></th>
                            </tr>
                            <?php
                        }
                        ?>
                        <?php
                        for ($i = 0; $i < count($lists['adf_player']); ++$i) {
                            $regpl = $lists['adf_player'][$i];

                            echo '<tr><td  width="250"><input type="hidden" name="adf_pl[]" value="' . $regpl->id . '" />' . $regpl->name . '</td>';
                            echo '<td  width="100">';
                            ?>
                            <div class="controls">
                                <fieldset class="radio btn-group">
                                    <?php echo JHTML::_('select.booleanlist', 'adfpl_reg_' . $regpl->id, 'class="inputbox" ', $regpl->reg_exist);
                                    ?>
                                </fieldset>
                            </div>
                            <?php
                            echo '</td>';
                            echo '<td>';
                            ?>
                            <div class="controls">
                                <fieldset class="radio btn-group">
                                    <?php echo JHTML::_('select.booleanlist', 'adfpl_rq_' . $regpl->id, 'class="inputbox" ' . (($regpl->field_type == 2) ? 'DISABLED' : ''), $regpl->reg_require);
                                    ?>
                                </fieldset>
                            </div>
                            <?php
                            echo '</td>';

                            if ($regpl->published == 0) {
                                echo '<td>' . JText::_('BLBE_ATTEF') . '</td>';
                            }
                            echo '</tr>';
                        }
                        ?>
                        <?php
                        if (!count($lists['adf_player'])) {
                            echo JText::_('BLBE_EXTRAFIELDS_NOTIF');
                        }
                        ?>
                    </table>
                </div>
            </div>    

        </div>            
    </div>
    <div id="admrigh_cfg_div" class="tabdiv" style="display:none;overflow:hidden;">
        <div class="jsrespdiv6">
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_MODERRIGHTS'); ?>
                </div>
                <div class="jsBEsettings">

                    <table class="adminlistsNoBorder">

                        <tr>
                            <td><?php echo JText::_('BLBE_MODEREDITPLAYER'); ?></td>
                            <td colspan="2">
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'moder_addplayer', 'class="inputbox" ', $lists['moder_addplayer']); ?>
                                    </fieldset>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_TEAMPERACCOUNT'); ?></td>
                            <td colspan="2"><input type="text" name="teams_per_account" value="<?php echo $lists['teams_per_account']; ?>" /></td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_PLAYERSRPEACCOUNT'); ?></td>
                            <td colspan="2"><input type="text" name="players_per_account" value="<?php echo $lists['players_per_account']; ?>" /></td>
                        </tr>


                        <!--UPDATE-->
                        <tr>
                            <td><?php echo JText::_('BLBE_UNBNOTJSPL_TEAMREG'); ?></td>
                            <td colspan="2">
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'player_team_reg', 'class="inputbox" ', $lists['player_team_reg']); ?>
                                    </fieldset>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo JText::_('BLBE_INVITEPL'); ?>
                            </td>
                            <td colspan="2">
                                <?php echo $lists['esport_invite_player']; ?>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <?php echo JText::_('BLBE_INVITEPLUNREG'); ?>
                            </td>
                            <td colspan="2">
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'esport_invite_unregister', 'class="inputbox" ', $lists['esport_invite_unregister']); ?>
                                    </fieldset>
                                </div>
                            </td>

                        </tr>

                        <tr>
                            <td>
                                <?php echo JText::_('BLBE_INVITEMATCH'); ?>
                            </td>
                            <td colspan="2">
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'esport_invite_match', 'class="inputbox" ', $lists['esport_invite_match']); ?>
                                    </fieldset>
                                </div>
                            </td>

                        </tr>

                        <tr>
                            <td>
                                <?php echo JText::_('BLBE_CANCREATEMATCH'); ?>

                            </td>
                            <td colspan="2">
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'moder_create_match', 'class="inputbox" ', $lists['moder_create_match']); ?>
                                    </fieldset>
                                </div>
                            </td>

                        </tr>


                        <tr>
                            <td>
                                <?php echo JText::_('BLBE_JSMR_MARK_PLAYED'); ?>
                                <span class="editlinktip hasTip" title="<?php echo JText::_('BLBE_JSMR_MARK_PLAYED'); ?>::<?php echo JText::_('BLBE_TT_JSMR_MARK_PLAYED'); ?>"><img class="imgQuest" src="components/com_joomsport/img/quest.png" bOrder="0" /></span>
                            </td>
                            <td colspan="2">
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jsmr_mark_played', 'class="inputbox" ', $lists['jsmr_mark_played']); ?>
                                    </fieldset>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                &nbsp;
                            </td>
                            <th>
                                <?php echo JText::_('BLBE_JSMR_OWNTEAM'); ?>
                            </th>
                            <th align="center">
                                <?php echo JText::_('BLBE_JSMR_OPPOSITETEAM'); ?>
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <?php echo JText::_('BLBE_JSMR_EDIT_MATCHRES'); ?>
                            </td>
                            <td align="">
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jsmr_editresult_yours', 'class="inputbox" ', $lists['jsmr_editresult_yours']); ?>
                                    </fieldset>
                                </div>
                            </td>
                            <td align="center">
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jsmr_editresult_opposite', 'class="inputbox" ', $lists['jsmr_editresult_opposite']); ?>
                                    </fieldset>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo JText::_('BLBE_JSMR_EDIT_PLEVENTS'); ?>
                            </td>
                            <td align="">
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jsmr_edit_playerevent_yours', 'class="inputbox" ', $lists['jsmr_edit_playerevent_yours']); ?>
                                    </fieldset>
                                </div>

                            </td>
                            <td align="center">
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jsmr_edit_playerevent_opposite', 'class="inputbox" ', $lists['jsmr_edit_playerevent_opposite']); ?>
                                    </fieldset>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo JText::_('BLBE_JSMR_EDIT_MATCHEVENTS'); ?>
                            </td>
                            <td align="">
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jsmr_edit_matchevent_yours', 'class="inputbox" ', $lists['jsmr_edit_matchevent_yours']); ?>
                                    </fieldset>
                                </div>

                            </td>
                            <td align="center">
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jsmr_edit_matchevent_opposite', 'class="inputbox" ', $lists['jsmr_edit_matchevent_opposite']); ?>
                                    </fieldset>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo JText::_('BLBE_JSMR_EDIT_SQUAD'); ?>
                            </td>
                            <td align="">
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jsmr_edit_squad_yours', 'class="inputbox" ', $lists['jsmr_edit_squad_yours']); ?>
                                    </fieldset>
                                </div>
                            </td>
                            <td align="center">
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jsmr_edit_squad_opposite', 'class="inputbox" ', $lists['jsmr_edit_squad_opposite']); ?>
                                    </fieldset>
                                </div>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
        <div class="jsrespdiv6 jsrespmarginleft2">
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_REGTEAMFLD'); ?>
                </div>
                <div class="jsBEsettings">
                    <table style="width:auto;" class="adminlistsNoBorder">
                        <tr>
                            <td width="200"><?php echo JText::_('BLBE_UNBTEAMREG'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'team_reg', 'class="inputbox" ', $lists['team_reg']); ?>
                                    </fieldset>
                                </div>

                            </td>
                        </tr>
                    </table>
                    <br />
                    <table style="width:auto;" class="adminlistsNoBorder">
                        <tr>
                            <th><?php echo JText::_('BLBE_FIELD'); ?></th>
                            <th><?php echo JText::_('BLBE_ONREGPAGE'); ?></th>
                            <th><?php echo JText::_('BLBE_REQUIRED'); ?></th>
                        </tr>
                        <tr>
                            <td width="200"><?php echo JText::_('BLBE_CITY'); ?></td>
                            <td width="100">
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'cf_team_city_enabled', 'class="inputbox" ', $lists['cf_team_city']['enabled']); ?>
                                    </fieldset>
                                </div>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'cf_team_city_required', 'class="inputbox" ', $lists['cf_team_city']['required']); ?>
                                    </fieldset>
                                </div>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_HEAD_TEAMEF'); ?>
                </div>
                <div class="jsBEsettings">
                    <table style="width:auto;" class="adminlistsNoBorder">
                        <?php
                        if (count($lists['adf_team'])) {
                            ?>
                            <tr>
                                <th><?php echo JText::_('BLBE_FIELD');
                            ?></th>
                                <th><?php echo JText::_('BLBE_ONREGPAGE');
                            ?></th>
                                <th><?php echo JText::_('BLBE_REQUIRED');
                            ?></th>
                            </tr>
                            <?php
                        }
                        ?>
                        <?php
                        for ($i = 0; $i < count($lists['adf_team']); ++$i) {
                            $regpl = $lists['adf_team'][$i];

                            echo '<tr><td width="200"><input type="hidden" name="adf_tm[]" value="' . $regpl->id . '" />' . $regpl->name . '</td>';
                            echo '<td width="100">';
                            ?>
                            <div class="controls">
                                <fieldset class="radio btn-group">
                                    <?php echo JHTML::_('select.booleanlist', 'adf_reg_' . $regpl->id, 'class="inputbox" ', $regpl->reg_exist);
                                    ?>
                                </fieldset>
                            </div>
                            <?php
                            echo '</td>';
                            echo '<td>';
                            ?>
                            <div class="controls">
                                <fieldset class="radio btn-group">
                                    <?php echo JHTML::_('select.booleanlist', 'adf_rq_' . $regpl->id, 'class="inputbox" ' . (($regpl->field_type == 2) ? 'DISABLED' : ''), $regpl->reg_require);
                                    ?>
                                </fieldset>
                            </div>
                            <?php
                            echo '</td>';
                            if ($regpl->published == 0) {
                                echo '<td>' . JText::_('BLBE_ATTEF') . '</td>';
                            }
                            echo '</tr>';
                        }
                        if (!count($lists['adf_team'])) {
                            echo '<tr><td colspan="3">' . JText::_('BLBE_EXTRAFIELDS_NOTIF') . '</td></tr>';
                        }
                        ?>
                    </table>
                </div>
            </div>    
        </div>

    </div>    
    <div id="esport_cfg_div" class="tabdiv" style="display:none;">

        <div class="jsrespdiv6">
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_SEASADMLEFT'); ?>
                </div>
                <div class="jsBEsettings">

                    <table class="adminlistsNoBorder">

                        <tr>
                            <td><?php echo JText::_('BLBE_ADMEDITPL'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jssa_editplayer', 'class="inputbox" ', $lists['jssa_editplayer']); ?>
                                    </fieldset>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_ADMEDITTM'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jssa_editteam', 'class="inputbox" ', $lists['jssa_editteam']); ?>
                                    </fieldset>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_ADMDELPL'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jssa_deleteplayers', 'class="inputbox" ', $lists['jssa_deleteplayers']); ?>
                                    </fieldset>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_ADMDELTM'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jssa_delteam', 'class="inputbox" ', $lists['jssa_delteam']); ?>
                                    </fieldset>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_ADMADDEXTEAM'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jssa_addexteam', 'class="inputbox" ', $lists['jssa_addexteam']); ?>
                                    </fieldset>
                                </div>
                            </td>
                        </tr>
                    </table>

                </div>    
            </div>
        </div>
        <div class="jsrespdiv6 jsrespmarginleft2">
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_SEASADMRIGHTS'); ?>
                </div>
                <div class="jsBEsettings">

                    <table class="adminlistsNoBorder">

                        <tr>
                            <td><?php echo JText::_('BLBE_ADMADDEXPAR'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jssa_addexteam_single', 'class="inputbox" ', $lists['jssa_addexteam_single']); ?>
                                    </fieldset>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_ADMEDITPLSINGLE'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jssa_editplayer_single', 'class="inputbox" ', $lists['jssa_editplayer_single']); ?>
                                    </fieldset>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_ADMDELPLS'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jssa_deleteplayers_single', 'class="inputbox" ', $lists['jssa_deleteplayers_single']); ?>
                                    </fieldset>
                                </div>
                            </td>
                        </tr>
                    </table>

                </div>
            </div>
        </div>
    </div>



    <div id="teamlayout_cfg_div" class="tabdiv" style="display:none;">
        <div class="jsrespdiv6">
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_HEAD_TEAMPAGE'); ?>
                </div>
                <div class="jsBEsettings">
                    <table class="adminlistsNoBorder">

                        <tr>
                            <td width="250"><?php echo JText::_('BLBE_PLLISTORDER'); ?></td>
                            <td><?php echo $lists['pllist_order']; ?></td>
                        </tr>
                    </table>    
                    <h4>
                        <?php echo JText::_('BLBE_HEAD_CFGTEAMLAYOUT'); ?>
                    </h4>
                    <table class="adminlistsNoBorder">

                        <tr>
                            <td width="250">
                                <?php echo JText::_('BLBE_TEAMLAYOUT_LPOS'); ?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'tlb_position', 'class="inputbox" ', $lists['tlb_position']); ?>
                                    </fieldset>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <?php echo JText::_('BLBE_TEAMLAYOUT_FORM'); ?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'tlb_form', 'class="inputbox" ', $lists['tlb_form']); ?>
                                    </fieldset>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td width="200">
                                <?php echo JText::_('BLBE_TEAMLAYOUT_LATEST'); ?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'tlb_latest', 'class="inputbox" ', $lists['tlb_latest']); ?>
                                    </fieldset>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td width="200">
                                <?php echo JText::_('BLBE_TEAMLAYOUT_NEXT'); ?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'tlb_next', 'class="inputbox" ', $lists['tlb_next']); ?>
                                    </fieldset>
                                </div>
                            </td>

                        </tr>
                    </table>
                    <h4>
                        <?php echo JText::_('BLBE_HEAD_PLTABSETT'); ?>
                    </h4>
                    <table class="adminlistsNoBorder">
                        <tr>
                            <td width="250">
                                <?php echo JText::_('BLBE_SHOW_PLAYERSTATTAB'); ?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'show_playerstattab', 'class="inputbox" ', $lists['show_playerstattab']); ?>
                                    </fieldset>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td width="250">
                                <?php echo JText::_('BLBE_SHOW_EMPTY_PLTAB'); ?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'show_playertab', 'class="inputbox" ', $lists['show_playertab']); ?>
                                    </fieldset>
                                </div>
                            </td>

                        </tr>
                        
                    </table>
                    
                    <h4>
                        <?php echo JText::_('BLBE_HEAD_ROSTERTABSET'); ?>
                    </h4>
                    <table class="adminlistsNoBorder">
                        <tr>
                            <td width="250">
                                <?php echo JText::_('BLBE_SHOW_ROSTERTAB'); ?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'show_rostertab', 'class="inputbox" ', $lists['show_rostertab']); ?>
                                    </fieldset>
                                </div>
                            </td>

                        </tr>
                        
                        <tr>
                            <td width="250">
                                <?php echo JText::_('BLBE_GROUPPLAYERSROSTER'); ?>
                            </td>
                            <td>
                                <?php echo $lists['set_teampgplayertab_groupby']; ?>
                            </td>

                        </tr>
                        <tr>
                            <td width="250">
                                <?php echo JText::_('BLBE_ROSTERFIELDFORNUMBER'); ?>
                            </td>
                            <td>
                                <?php echo $lists['set_playerfieldnumber']; ?>
                            </td>

                        </tr>
                        <tr>
                            <td width="250">
                                <?php echo JText::_('BLBE_ROSTEREXTRACARDFIELD'); ?>
                            </td>
                            <td>
                                <?php echo $lists['set_playercardef']; ?>
                            </td>

                        </tr>
                            
                    </table>

                </div>    
            </div>
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_HEAD_PLAYERPAGE'); ?>
                    <?php
                        $jsTL = json_decode($lists['jstimeline'],true);
                    ?>
                </div>
                <div class="jsBEsettings">
                    <table class="adminlistsNoBorder">
                        <tr>
                            <td width="250"><?php echo JText::_('BLBE_CAREER_BLOCK'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jsblock_career[enable]', 'class="inputbox jsShowBlockFieldsBtn" ', $lists['jsblock_career_enable'], JText::_('JYES'),JText::_('JNO'),'jsblock_career_enable'); ?>
                                    </fieldset>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td class="jsShowBlockFields" width="250">
                                <?php echo JText::_('BLBE_CAREER_BLOCK_FIELDS'); ?>
                            </td>
                            <td class="jsShowBlockFields"><?php echo $lists['jsblock_career_fields']; ?></td>
                        </tr>
                        <tr>
                            <td width="250"><?php echo JText::_('BLBE_MATCHSTAT_BLOCK'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jsblock_matchstat', 'class="inputbox" ', $lists['jsblock_matchstat'], JText::_('JYES'),JText::_('JNO'),'jsblock_matchstat'); ?>
                                    </fieldset>
                                </div>

                            </td>
                        </tr>
                        <!--tr>
                            <td><?php //echo JText::_('BLBE_SHOWPLAYEDMATCHES'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php //echo JHTML::_('select.booleanlist', 'played_matches', 'class="inputbox" ', $lists['played_matches']); ?>
                                    </fieldset>
                                </div>
                            </td>
                        </tr-->
                    </table>
                </div>
            </div>
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_HEAD_CALENDARPAGE'); ?>
                </div>
                <div class="jsBEsettings">
                    <table class="adminlistsNoBorder">

                        <tr>
                            <td width="250"><?php echo JText::_('BLBE_CALVENUE'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'cal_venue', 'class="inputbox" ', $lists['cal_venue']); ?>
                                    </fieldset>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_PLLIST_LINK'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'enbl_linktoplayerlistcal', 'class="inputbox" ', $lists['enbl_linktoplayerlistcal']); ?>
                                    </fieldset>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_ENBL_SEARCH_MATCHES'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'enbl_calmatchsearch', 'class="inputbox" ', $lists['enbl_calmatchsearch']); ?>
                                    </fieldset>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_ENBL_MDNAME_ONCAL'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'enbl_mdnameoncalendar', 'class="inputbox" ', $lists['enbl_mdnameoncalendar']); ?>
                                    </fieldset>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_CAL_THEME'); ?></td>
                            <td>
                                <?php
                                    echo $lists['jscalendar_theme'];
                                ?>

                            </td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_ENBL_MATCH_TOOLTIP'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'enbl_matchtooltip', 'class="inputbox" ', $lists['enbl_matchtooltip']); ?>
                                    </fieldset>
                                </div>

                            </td>
                        </tr>

                    </table>
                </div>
            </div>
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_HEAD_STANDINGSPAGE'); ?>
                </div>
                <div class="jsBEsettings">
                    <table class="adminlistsNoBorder">
                        <tr>
                            <td width="250"><?php echo JText::_('BLBE_PLLIST_LINK'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'enbl_linktoplayerlist', 'class="inputbox" ', $lists['enbl_linktoplayerlist']); ?>
                                    </fieldset>
                                </div>

                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_HEAD_MATCHPAGE'); ?>
                    <?php
                        $jsTL = json_decode($lists['jstimeline'],true);
                    ?>
                </div>
                <div class="jsBEsettings">
                    <table class="adminlistsNoBorder">
                        <tr>
                            <td width="250">
                                <?php echo JText::_('BLBE_CHOOSEPE'); ?>
                                <span class="editlinktip hasTip" title="<?php echo JText::_('BLBE_CHOOSEPE'); ?>::<?php echo JText::_('BLBE_TT_CHOOSEPE'); ?>"><img class="imgQuest" src="components/com_joomsport/img/quest.png" bOrder="0" /></span>
                            </td>
                            <td><?php echo $lists['pllist_order_se']; ?></td>
                        </tr>
                        <tr>
                            <td width="250">
                                <?php echo JText::_('BLBE_CNFG_LINEUP_FIRST');?>
                            </td>
                            <td>
                                <?php echo $lists["jsmatch_squad_firstcol"];?>

                            </td>
                        </tr>
                        <tr>
                            <td width="250">
                                <?php echo JText::_('BLBE_CNFG_LINEUP_ADDIT');?>
                            </td>
                            <td>
                                <?php echo $lists["jsmatch_squad_lastcol"];?>

                            </td>
                        </tr>
                        <tr>
                            <td width="250"><?php echo JText::_('BLBE_ML_DURATION'); ?></td>
                            <td>
                                <input type="text" name="jstimeline[duration]"style="width:50px;" maxlength="5" class="inputbox" value="<?php echo isset($jsTL['duration'])?$jsTL['duration']:0?>" onblur="extractNumber(this, 0, false);" onkeyup="extractNumber(this, 0, false);" onkeypress="return blockNonNumbers(this, event, false, false);" />
   
                            </td>
                        </tr>
                        <tr>
                            <td width="250"><?php echo JText::_('BLBE_ML_TIMLINE'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jstimeline[tldisplay]', 'class="inputbox" ', isset($jsTL['tldisplay'])?$jsTL['tldisplay']:0, JText::_('JYES'),JText::_('JNO'),'tldisplay'); ?>
                                    </fieldset>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td width="250"><?php echo JText::_('BLBE_KICKOUT_EVENT'); ?></td>
                            <td>
                                <?php echo $lists['kick_events'];?>
   
                            </td>
                        </tr>
                        
                        <tr>
                            <td width="250"><?php echo JText::_('BLBE_OPPOSITE_TEAM_EVENTS'); ?></td>
                            <td>
                                <?php echo $lists['opposite_events'];?>
   
                            </td>
                        </tr>
                        
                        <tr>
                            <td width="250"><?php echo JText::_('BLBE_MDNAME_ON_MATCH'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo $lists['enbl_mdname_on_match'];?>
                                    </fieldset>
                                </div>    
                            </td>
                        </tr>
                        
                        
                    </table>
                    <h4><?=JText::_("BLBE_CNFG_MATCH_UPCOMING");?></h4>
                    <table class="adminlistsNoBorder">
                        <tr>
                            <td width="250">
                                <?=JText::_("BLBE_CNFG_MATCH_ANALYTICS_BLOCK");?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'enbl_match_analytics_block', 'class="inputbox" ', $lists['enbl_match_analytics_block']); ?>
                                    </fieldset>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="250">
                                <?=JText::_("BLBE_CNFG_MATCH_AVG_EVENTS");?>
                            </td>
                            <td>
                                <?php echo $lists['avgevents_events'];?>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_CONF_PLAYERLISTPAGE'); ?>
                </div>
                <div class="jsBEsettings">

                    <table class="adminlistsNoBorder">
                        <tr>
                            <td width="250"><?php echo JText::_('BLBE_PLLISTORDER');?></td>
                            <td>
                                <?php
                                echo $lists['pllistpage_order'];
                                ?>

                            </td>
                        </tr>
                    </table>


                </div>
            </div>



        </div>
        <div class="jsrespdiv6 jsrespmarginleft2">
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_HEAD_IMGSETTINGS'); ?>
                </div>
                <div class="jsBEsettings">
                    <table class="adminlistsNoBorder">
                        <tr>
                            <td width="250"><?php echo JText::_('BLBE_TEAMLOGOTBL'); ?></td>
                            <td>

                                <input type="text" maxlength="5" name="teamlogo_height" style="width:50px;" value="<?php echo $lists['teamlogo_height']; ?>" onblur="extractNumber(this, 0, false);" onkeyup="extractNumber(this, 0, false);" onkeypress="return blockNonNumbers(this, event, false, false);" />
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_MATCHLOGOHG'); ?></td>
                            <td>

                                <input type="text" maxlength="5" name="set_emblemhgonmatch" style="width:50px;" value="<?php echo $lists['set_emblemhgonmatch']; ?>" onblur="extractNumber(this, 0, false);" onkeyup="extractNumber(this, 0, false);" onkeypress="return blockNonNumbers(this, event, false, false);" />
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('BLBE_DEFPHOTOWIDTH'); ?></td>
                            <td>

                                <input type="text" maxlength="5" name="set_defimgwidth" style="width:50px;" value="<?php echo $lists['set_defimgwidth']; ?>" onblur="extractNumber(this, 0, false);" onkeyup="extractNumber(this, 0, false);" onkeypress="return blockNonNumbers(this, event, false, false);" />
                            </td>
                        </tr>
                    </table>   
                </div>
            </div>
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_HEAD_PLAYERSETTINGS'); ?>
                </div>
                <div class="jsBEsettings">
                    <table class="adminlistsNoBorder">
                        <tr>
                            <td width="250"><?php echo JText::_('BLBE_NAMEDONFE'); ?></td>
                            <td><?php echo $lists['player_name']; ?></td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo JText::_('BLBE_DISPLAYPROFILE'); ?>
                                <span class="editlinktip hasTip" title="<?php echo JText::_('BLBE_TT_DISPLAYPROFILE'); ?>"><img class="imgQuest" src="components/com_joomsport/img/quest.png" bOrder="0" /></span>
                            </td>
                            <td><?php echo $lists['display_profile']; ?></td>
                        </tr>
                        <tr>
                            <td width="250"><?php echo JText::_('BLBE_ENBL_LINKS_PLAYERLOGO'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'enbl_playerlogolinks', 'class="inputbox" ', $lists['enbl_playerlogolinks']); ?>
                                    </fieldset>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="250"><?php echo JText::_('BLBE_ENBL_LINKS_PLAYER'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'enbl_playerlinks', 'class="inputbox" ', $lists['enbl_playerlinks']); ?>
                                    </fieldset>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="250" class="hdn_div_enblink_player">
                                <?php echo JText::_('BLBE_ENBL_PLAYERLINKS_HGLT_TEAMS');?>
                            </td>
                            <td class="hdn_div_enblink_player">
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'enbl_playerlinks_hglteams', 'class="inputbox" ', $lists['enbl_playerlinks_hglteams']); ?>
                                    </fieldset>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="250"><?php echo JText::_('BLBE_SYSTEM_PLAYER_NUMBER'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'enbl_player_system_num', 'class="inputbox" ', $lists['enbl_player_system_num']); ?>
                                    </fieldset>
                                </div>
                            </td>
                        </tr>
                        

                    </table>
                </div>
            </div> 
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_HEAD_TEAMSETTINGS'); ?>
                </div>
                <div class="jsBEsettings">
                    <table class="adminlistsNoBorder">
                        <tr>
                            <td width="250"><?php echo JText::_('BLBE_ENBL_LINKS_TEAMLOGO'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'enbl_teamlogolinks', 'class="inputbox" ', $lists['enbl_teamlogolinks']); ?>
                                    </fieldset>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="250"><?php echo JText::_('BLBE_ENBL_LINKS_TEAM'); ?></td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'enbl_teamlinks', 'class="inputbox" ', $lists['enbl_teamlinks']); ?>
                                    </fieldset>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="250" class="hdn_div_enblink"><?php echo JText::_('BLBE_ENBL_LINKS_HGLTEAMS'); ?></td>
                            <td class="hdn_div_enblink">
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'enbl_teamhgllinks', 'class="inputbox" ', $lists['enbl_teamhgllinks']); ?>
                                    </fieldset>
                                </div>
                            </td>
                        </tr>


                    </table>
                </div>
            </div> 
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_SOCIALCONF'); ?>
                </div>
                <div class="jsBEsettings">
                    <table class="adminlistsNoBorder">

                        <tr>
                            <td  width="250">
                                <?php echo JText::_('BLBE_TWITBUTTON'); ?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jsb_twitter', 'class="inputbox" ', $lists['jsb_twitter']); ?>
                                    </fieldset>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <?php echo JText::_('BLBE_GPLUSBUTTON'); ?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jsb_gplus', 'class="inputbox" ', $lists['jsb_gplus']); ?>
                                    </fieldset>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <?php echo JText::_('BLBE_FBSHAREBUTTON'); ?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jsb_fbshare', 'class="inputbox" ', $lists['jsb_fbshare']); ?>
                                    </fieldset>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <?php echo JText::_('BLBE_FBLIKEBUTTON'); ?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jsb_fblike', 'class="inputbox" ', $lists['jsb_fblike']); ?>
                                    </fieldset>
                                </div>
                            </td>

                        </tr>
                    </table>

                    <h4>
                        <?php echo JText::_('BLBE_CHECKPAGES'); ?>
                    </h4>


                    <table class="adminlistsNoBorder">

                        <tr>
                            <td  width="250">
                                <?php echo JText::_('BLBE_TABLE_LAYOUT'); ?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jsbp_season', 'class="inputbox" ', $lists['jsbp_season']); ?>
                                    </fieldset>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <?php echo JText::_('BLBE_TEAM_LAYOUT'); ?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jsbp_team', 'class="inputbox" ', $lists['jsbp_team']); ?>
                                    </fieldset>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <?php echo JText::_('BLBE_PLAYER_LAYOUT'); ?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jsbp_player', 'class="inputbox" ', $lists['jsbp_player']); ?>
                                    </fieldset>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <?php echo JText::_('BLBE_MATCH_LAYOUT'); ?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jsbp_match', 'class="inputbox" ', $lists['jsbp_match']); ?>
                                    </fieldset>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <?php echo JText::_('BLBE_VENUE_LAYOUT'); ?>
                            </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo JHTML::_('select.booleanlist', 'jsbp_venue', 'class="inputbox" ', $lists['jsbp_venue']); ?>
                                    </fieldset>
                                </div>
                            </td>

                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </div>    

    <input type="hidden" name="option" value="com_joomsport" />
    <input type="hidden" name="task" value="config" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="jscurtab" id="jscurtab" value="" />
    <?php echo JHTML::_('form.token'); ?>
</form>