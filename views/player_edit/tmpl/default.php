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
defined('_JEXEC') or die;
$row = $this->row;
$lists = $this->lists;
JHTML::_('behavior.tooltip');
        require_once JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_joomsport'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'tabs.php';
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');        $etabs = new esTabs();

        ?>
		<script type="text/javascript">
		<!--
		Joomla.submitbutton = function(task) {
			submitbutton(task);
		}
		function submitbutton(pressbutton) {
			var form = document.adminForm;
            var reg = /^\s+$/;
			 if(pressbutton == 'player_apply' || pressbutton == 'player_save' || pressbutton == 'player_save_new'){
                    jQuery('input[name="jsgallery"]').val(JSON.stringify(jQuery('input[name^="filnm"]').serializeArray()));

                        if(form.first_name.value == '' || (form.last_name.value == '' && '<?php echo $this->lists['req_lastname'];?>' == 1)){
			 		alert('<?php echo JText::_('BLBE_JSNOTICEPL'); ?>');
			 	}else{
					var srcListName2 = 'in_teams';
					var srcList2 = eval( 'form.' + srcListName2 );
                    if(srcList2){
                        var srcLen2 = srcList2.length;
                        /*if(!srcLen2 && '<?php echo $lists['t_single']?>' == 0 && '<?php echo $lists['seasf_id']?>' != -1){
                            alert('<?php echo JText::_('BLBE_ADDTEAMS'); ?>');
                            return false;
                        }*/
                        for (var i=0; i < srcLen2; i++) {
                                srcList2.options[i].selected = true;
                        }
                    }
                     if(reg.test(form.first_name.value) || reg.test(form.last_name.value)){
                         alert('<?php echo JText::_('BLBE_JSNOTICEPL'); ?>');
                     }else{
                         submitform( pressbutton );
                         return;
                     }
			 	}
			 }else{
				submitform( pressbutton );
					return;
			 }		
		}	
		
		function Delete_tbl_row(element) {
			var del_index = element.parentNode.parentNode.sectionRowIndex;
			var tbl_id = element.parentNode.parentNode.parentNode.parentNode.id;
			element.parentNode.parentNode.parentNode.deleteRow(del_index);
		}
                function loadSeasonParms(){
                    jQuery.ajax({
                        url: "index.php?option=com_joomsport&task=getPlayerFieldsBySeason&tmpl=component&no_html=1",
                        type: 'POST',
                        data: {sid : jQuery('#seasf_id').val(), player_id : document.adminForm.id.value},

                      }).done(function(res) {
                        jQuery("#jsAjaxContSeason").html(res);
                        jQuery(".efieldsseas").find("select").chosen({disable_search_threshold: 10});
                      });
                }    
		//-->
		</script>
		<?php
        
        ?>
<form action="index.php?option=com_joomsport" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    
    <div class="jsrespdiv12">
        <div class="jsBepanel">
            <div class="jsBEheader">
                <?php echo JText::_('BLBE_GENERAL'); ?>
            </div>
            <div class="jsBEsettings" style="padding:0px;">
		<!-- <tab box> -->
		<ul class="tab-box">
		<?php
        echo $etabs->newTab(JText::_('BLBE_MAIN'), 'main_pl', '', 'vis');
        echo $etabs->newTab(JText::_('BLBE_TABPLAYERS'), 'bonuses_conf', '');
        ?>
		</ul>
		<div style="clear:both"></div>
            </div>    
        </div>
    </div>    
    <div id="main_pl_div" class="tabdiv">
        <div class="jsrespdiv12">
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_GENERAL'); ?>
                </div>
                <div class="jsBEsettings">
                    <table class="jsbetable">
			<tr>
				<td width="200">
					<?php echo JText::_('User'); ?>
				</td>
				<td>
					<?php echo $lists['usrid'];?>
				</td>
			</tr>
			<tr>
				<td width="200">
					<?php echo JText::_('BLBE_FIRSTNAME'); ?>
                                    <?php
                                    if(count($lists['languages'])){

                                        echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                    }?>
                                </td>
				<td>
					<input type="text" maxlength="255" size="60" name="first_name" value="<?php echo htmlspecialchars($row->first_name)?>" />
                                        <?php
                                        if(count($lists['languages'])){

                                            echo '<div class="jsTranslationContainer">';
                                            foreach (($lists['languages']) as $value) {
                                                echo '<div class="jsTranslationDiv">';
                                                $translation = '';

                                                if(isset($lists['translation']) && isset($lists['translation'][$value]['first_name'])){
                                                    $translation = htmlspecialchars($lists['translation'][$value]['first_name'], ENT_QUOTES);
                                                }
                                                echo '<input type="text" maxlength="255" size="60" name="translation['.$value.'][first_name]" value="'.addslashes($translation).'"/>';
                                                echo '  ' . $value;
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                        }
                                        ?>
                                </td>
			</tr>
			<tr>
				<td width="200">
					<?php echo JText::_('BLBE_LASTNAME'); ?>
                                    <?php
                                    if(count($lists['languages'])){

                                        echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                    }?>
					
                                </td>
				<td>
					<input type="text" maxlength="255" size="60" name="last_name" value="<?php echo htmlspecialchars($row->last_name)?>" />
                                        <?php
                                        if(count($lists['languages'])){

                                            echo '<div class="jsTranslationContainer">';
                                            foreach (($lists['languages']) as $value) {
                                                echo '<div class="jsTranslationDiv">';
                                                $translation = '';

                                                if(isset($lists['translation']) && isset($lists['translation'][$value]['last_name'])){
                                                    $translation = htmlspecialchars($lists['translation'][$value]['last_name'], ENT_QUOTES);
                                                }
                                                echo '<input type="text" maxlength="255" size="60" name="translation['.$value.'][last_name]" value="'.addslashes($translation).'"/>';
                                                echo '  ' . $value;
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                        }
                                        ?>
                                </td>
			</tr>
			<tr>
				<td width="200">
					<?php echo JText::_('BLBE_NAME'); ?>
                                    <?php
                                    if(count($lists['languages'])){

                                        echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                    }?>
					
                                </td>
				<td>
					<input type="text" maxlength="255" size="60" name="fullname" value="<?php echo htmlspecialchars($row->fullname)?>" />
                                        <?php
                                        if(count($lists['languages'])){

                                            echo '<div class="jsTranslationContainer">';
                                            foreach (($lists['languages']) as $value) {
                                                echo '<div class="jsTranslationDiv">';
                                                $translation = '';

                                                if(isset($lists['translation']) && isset($lists['translation'][$value]['full_name'])){
                                                    $translation = htmlspecialchars($lists['translation'][$value]['full_name'], ENT_QUOTES);
                                                }
                                                echo '<input type="text" maxlength="255" size="60" name="translation['.$value.'][full_name]" value="'.addslashes($translation).'"/>';
                                                echo '  ' . $value;
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                        }
                                        ?>
                                </td>
			</tr>
                        <tr>
				<td width="200">
					<?php echo JText::_('BLBE_PLAYER_SHORTNAME'); ?>
                                    <?php
                                    if(count($lists['languages'])){

                                        echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                    }?>
					
                                </td>
				<td>
					<input type="text" maxlength="255" size="60" id="shortname" name="shortname" value="<?php echo htmlspecialchars($row->shortname)?>" />
                                        <?php
                                        if(count($lists['languages'])){

                                            echo '<div class="jsTranslationContainer">';
                                            foreach (($lists['languages']) as $value) {
                                                echo '<div class="jsTranslationDiv">';
                                                $translation = '';

                                                if(isset($lists['translation']) && isset($lists['translation'][$value]['short_name'])){
                                                    $translation = htmlspecialchars($lists['translation'][$value]['short_name'], ENT_QUOTES);
                                                }
                                                echo '<input type="text" maxlength="255" size="60" name="translation['.$value.'][short_name]" value="'.addslashes($translation).'"/>';
                                                echo '  ' . $value;
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                        }
                                        ?>
                                </td>
			</tr>
                        
                        <tr>
				<td width="200">
					<?php echo JText::_('BLBE_PLAYER_NAMEINHC'); ?>
                                    
					
                                </td>
				<td>
					<input type="text" maxlength="255" size="60" id="name_in_hc" name="name_in_hc" value="<?php echo htmlspecialchars($row->name_in_hc)?>" />
                                        
                                </td>
			</tr>
                        
			<tr>
				<td width="200">
					<?php echo JText::_('BLBE_NICKNAME'); ?>
                                    <?php
                                    if(count($lists['languages'])){

                                        echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                    }?>
                                </td>
				<td>
					<input type="text" maxlength="255" size="60" name="nick" value="<?php echo htmlspecialchars($row->nick)?>" />
                                        <?php
                                        if(count($lists['languages'])){

                                            echo '<div class="jsTranslationContainer">';
                                            foreach (($lists['languages']) as $value) {
                                                echo '<div class="jsTranslationDiv">';
                                                $translation = '';

                                                if(isset($lists['translation']) && isset($lists['translation'][$value]['nickname'])){
                                                    $translation = htmlspecialchars($lists['translation'][$value]['nickname'], ENT_QUOTES);
                                                }
                                                echo '<input type="text" maxlength="255" size="60" name="translation['.$value.'][nickname]" value="'.addslashes($translation).'"/>';
                                                echo '  ' . $value;
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                        }
                                        ?>
				</td>
			</tr>
			
			<tr>
				<td width="200">
					<?php echo JText::_('BLBE_COUNTRY'); ?>
				</td>
				<td>
					<?php echo $lists['country']?>
				</td>
			</tr>
			
			
			<tr>
				<td width="200">
					<?php echo JText::_('BLBE_ABPLAYER'); ?>
                                    <?php
                                    if(count($lists['languages'])){

                                        echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                    }?>
                                </td>
				<td>
					<?php echo $editor->display('about',  htmlspecialchars($row->about, ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore'));  ?>
                                        <?php
                                    if(count($lists['languages'])){
                                        echo '<div class="jsTranslationContainer">';
                                        foreach (($lists['languages']) as $value) {
                                            echo '<div class="jsTranslationDiv">';
                                            $translation = '';
                                            echo $value;
                                            if(isset($lists['translation']) && isset($lists['translation'][$value]['about'])){
                                                $translation = $lists['translation'][$value]['about'];
                                            }
                                            echo $editor->display('translation['.$value.'][about]',  htmlspecialchars($translation, ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore'));
                                            
                                            echo '</div>';
                                        }
                                        echo '</div>';
                                    }
                                    ?>
                                </td>
			</tr>
		</table>
            </div>
        </div>
        <div class="jsBepanel">
            <div class="jsBEheader">
                <?php echo JText::_('BLBE_MENAF'); ?>
            </div>
            <div class="jsBEsettings">
                <?php
                if (count($this->lists['ext_fields'])) {
                    ?>
                <table  class='jsTableEqual'>
                    <?php
                    for ($p = 0;$p < count($this->lists['ext_fields']);++$p) {
                        if ($this->lists['ext_fields'][$p]->field_type == '3' && !isset($this->lists['ext_fields'][$p]->selvals)) {
                        } else {
                            ?>
                    <tr>
                            <td width="200">
                                    <?php echo $this->lists['ext_fields'][$p]->name;
                            ?>
                            </td>
                            <td>
                                    <?php

                                            switch ($lists['ext_fields'][$p]->field_type) {

                                                    case '1':    echo $lists['ext_fields'][$p]->selvals;
                                                                            break;
                                                    case '2':    echo $editor->display('extraf['.$lists['ext_fields'][$p]->id.']',  htmlspecialchars(isset($lists['ext_fields'][$p]->fvalue_text) ? ($lists['ext_fields'][$p]->fvalue_text) : '', ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore'));
                                                                            break;
                                                    case '5':  
                                                    case '6': 
                                                    case '3':    echo $lists['ext_fields'][$p]->selvals;
                                                                            break;
                                                    case '0':
                                                    default:    echo '<input type="text" maxlength="255" size="60" name="extraf['.$lists['ext_fields'][$p]->id.']" value="'.(isset($lists['ext_fields'][$p]->fvalue) ? htmlspecialchars($lists['ext_fields'][$p]->fvalue) : '').'" />';
                                                                            break;

                                            }
                            ?>
                                    <input type="hidden" name="extra_ftype[<?php echo $lists['ext_fields'][$p]->id;
                            ?>]" value="<?php echo $lists['ext_fields'][$p]->field_type?>" />
                                    <input type="hidden" name="extra_id[<?php echo $lists['ext_fields'][$p]->id;
                            ?>]" value="<?php echo $lists['ext_fields'][$p]->id?>" />
                            </td>
                    </tr>
                    <?php	
                        }
                    }
                    ?>
                </table>
                <?php

                } else {
                    echo JText::_('BLBE_EXTRAFIELDS_NOTIF');
                }
                ?>
            </div>
        </div>
        <div class="jsBepanel">
            <div class="jsBEheader">
                <?php echo JText::_('BLBE_HEAD_IMAGES'); ?>
            </div>
            <div class="jsBEsettings">

		<?php
                    require_once 'components/com_joomsport/helpers/images.php';
                    echo ImagesHelper::loaderUI($lists['photos'], $row->def_img);
                ?>   
            </div>
        </div>
        </div> 
    </div>    
    <div id="bonuses_conf_div" class="tabdiv" style="display:none;">
        <div class="jsrespdiv12">
            <div style="margin-bottom: 15px;">
                <?php if ($lists['is_seas']) {
    echo JText::_('BLBE_SELSEASN').':&nbsp;&nbsp;';
} ?>
                <?php echo $lists['seasf'];?>
            </div>
            <div id="jsAjaxContSeason">
                <?php
                $db = JFactory::getDBO();
                $sid = $lists['seasf_id'];
                $player_id = $row->id;
                if ($sid == -1) {
                    $query = "SELECT DISTINCT(team_id) FROM #__bl_players_team WHERE confirmed='0' AND player_id='".$player_id."' AND season_id='".$sid."'";
                    $db->setQuery($query);
                    $plars = $db->loadColumn();
                    $error = $db->getErrorMsg();
                    if ($error) {
                        return JError::raiseError(500, $error);
                    }

                    $query = "SELECT t.* FROM #__bl_players_team as p,#__bl_teams as t WHERE p.confirmed='0' AND p.player_join='0' AND t.id=p.team_id AND p.player_id='".$player_id."' AND p.season_id=".$sid;
                    $db->setQuery($query);
                    $f_inteams = $db->loadObjectList();
                    $error = $db->getErrorMsg();
                    if ($error) {
                        return JError::raiseError(500, $error);
                    }
                    $lists['in_teams'] = @JHTML::_('select.genericlist',   $f_inteams, 'in_teams[]', 'class="chzn-done" size="10" multiple ondblclick="javascript:JS_addSelectedToList(\'adminForm\',\'in_teams\',\'allteams\');"', 'id', 't_name', 0);

                    $selected = array();
                    for ($intA = 0; $intA < count($f_inteams); ++$intA) {
                        $selected[] = $f_inteams[$intA]->id;
                    }
                    $query = 'SELECT * FROM #__bl_teams as t '
                        .' WHERE 1=1'
                        .(count($plars) ? ' AND t.id NOT IN ('.implode(',', $plars).')' : '')
                        .(count($selected) ? ' AND t.id NOT IN ('.implode(',', $selected).')' : '')
                        .' ORDER BY t.t_name';
                    $db->setQuery($query);
                    $f_teams = $db->loadObjectList();
                    $error = $db->getErrorMsg();
                    if ($error) {
                        return JError::raiseError(500, $error);
                    }

                    $lists['allteams'] = @JHTML::_('select.genericlist',   $f_teams, 'allteams', 'class="chzn-done" size="10" multiple ondblclick="javascript:JS_addSelectedToList(\'adminForm\',\'allteams\',\'in_teams\');"', 'id', 't_name', 0);

                    ?>
                <div class="jsBepanel">
                    <div class="jsBEheader">
                        <?php echo JText::_('BLBE_GENERAL');
                    ?>
                    </div>
                    <div class="jsBEsettings">
                        <table class="adminlistsNoBorder">
               
                            <tr>
                                <td width="150">
                                        <?php echo JText::_('BLBE_ASSIGNPLAYERS');
                    ?>
                                </td>

                                <td width="150">
                                    <div class="selectedlistdescr"><?php echo JText::_('BLBE_AVAILABLE')?></div>
                                        <?php echo $lists['allteams'];
                    ?>
                                </td>
                                <td valign="middle" width="60" align="center">
                                        <input class="btn" type="button" style="cursor:pointer;" value=">>" onClick="javascript:JS_addSelectedToList('adminForm','allteams','in_teams');" /><br />
                                        <input class="btn" type="button" style="cursor:pointer;" value="<<" onClick="javascript:JS_delSelectedFromList('adminForm','in_teams','allteams');" />
                                </td>
                                <td >
                                    <div class="selectedlistdescr"><?php echo JText::_('BLBE_SELECTED')?></div>
                                        <?php echo $lists['in_teams'];
                    ?>
                                </td>

                            </tr>
                
                        </table>
                    </div>
                </div>
                <?php

                }?>
                <?php if ($lists['t_single'] == 0 && $lists['seasf_id'] != -1):?>
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_GENERAL'); ?>
                </div>
                <div class="jsBEsettings">
                <table class="adminlistsNoBorder">
                
                            <tr>
                                <td width="150" style="vertical-align: middle;">
                                            <?php echo JText::_('BLBE_ASSIGNPLAYERS'); ?>
                                    </td>

                                    <td width="150">
                                        <div class="selectedlistdescr"><?php echo JText::_('BLBE_AVAILABLE')?></div>
                                            <?php echo $lists['allteams'];?>
                                    </td>
                                    <td valign="middle" width="60" style="vertical-align: middle; text-align: center;">
                                            <input class="btn" type="button" style="cursor:pointer;" value=">>" onClick="javascript:JS_addSelectedToList('adminForm','allteams','in_teams');" /><br />
                                            <input class="btn" type="button" style="cursor:pointer;" value="<<" onClick="javascript:JS_delSelectedFromList('adminForm','in_teams','allteams');" />
                                    </td>
                                    <td >
                                        <div class="selectedlistdescr"><?php echo JText::_('BLBE_SELECTED')?></div>
                                            <?php echo $lists['in_teams'];?>
                                    </td>

                            </tr>
                
                            </table>
                </div>
            </div>
           <?php endif;?>     
        <?php 
        if ($lists['seasf_id'] > 0 && $lists['t_single'] == 1) {
            ?>
        <div class="jsBepanel">
            <div class="jsBEheader">
                <?php echo JText::_('BLBE_GENERAL');
            ?>
            </div>
            <div class="jsBEsettings">
                <table class="adminlistsNoBorder">
                        
                <?php
                    echo '<tr><td width="100">'.JText::_('BLBE_BONUS').'</td><td><input type="text" name="bonuses" value="'.floatval($lists['bonuses']).'" />'.'</td></tr>';
            ?>
                </table>
            </div>
        </div>
        <?php

        }
        if ($lists['enbl_player_system_num']) { ?>
            <div class="jsBepanel efieldsseas">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_PLAYER_NUMBER'); ?>
                </div>
                <div class="jsBEsettings">
                    
                    <table class="adminlistsNoBorder" id="playerNumbers">
                        <?php if((isset($lists['player_numbers']) && count($lists['player_numbers']))){?>
                        <?php foreach($lists['player_numbers'] as $number) {?>
                        <tr id="row<?php echo $number->id;?>">
                            <td width="100"><?php echo $number->t_name;?></td>
                            <td><input size="10" type="text" name="numbers[<?php echo $number->id;?>]" value="<?php echo $number->number;?>" /></td>
                        </tr>
                        <?php } ?>
                        <?php } ?>
                    </table>
                    
                </div>
            </div>            
        <?php }
        if (isset($lists['ext_fields_sr']) && count($lists['ext_fields_sr'])) { ?>
            <div class="jsBepanel efieldsseas">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_FIELDSBYSEASON'); ?>
                </div>
                <div class="jsBEsettings">
                <table class="adminlistsNoBorder">
                    <?php
                    for ($p = 0;$p < count($lists['ext_fields_sr']);++$p) {
                        if ($lists['ext_fields_sr'][$p]->field_type == '3' && !isset($lists['ext_fields_sr'][$p]->selvals)) {
                        } else {
                            if ($lists['ext_fields_sr'][$p]->season_related == 1 && $row->id && $lists['seasf_id'] != -1) { //update, instead of $lists["bonuses"]
            ?>
			<tr>
				<td width="100">
					<?php echo $lists['ext_fields_sr'][$p]->name;
                                ?>
				</td>
				<td>
					<?php

                        switch ($lists['ext_fields_sr'][$p]->field_type) {

                            case '1':    echo $lists['ext_fields_sr'][$p]->selvals;
                                        break;
                            case '2':    echo $editor->display('extraf['.$lists['ext_fields_sr'][$p]->id.']',  htmlspecialchars(isset($lists['ext_fields_sr'][$p]->fvalue_text) ? ($lists['ext_fields_sr'][$p]->fvalue_text) : '', ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore'));
                                        break;
                            case '3':    echo $lists['ext_fields_sr'][$p]->selvals;
                                        break;
                            case '0':
                            default:    echo '<input type="text" maxlength="255" size="60" name="extraf['.$lists['ext_fields_sr'][$p]->id.']" value="'.(isset($lists['ext_fields_sr'][$p]->fvalue) ? htmlspecialchars($lists['ext_fields_sr'][$p]->fvalue) : '').'" />';
                                        break;

                        }
                                ?>
					<input type="hidden" name="extra_ftype[<?php echo $lists['ext_fields_sr'][$p]->id?>]" value="<?php echo $lists['ext_fields_sr'][$p]->field_type?>" />
					<input type="hidden" name="extra_id[<?php echo $lists['ext_fields_sr'][$p]->id?>]" value="<?php echo $lists['ext_fields_sr'][$p]->id?>" />
				</td>
			</tr>
			
			<?php	
                            }
                            if ($lists['ext_fields_sr'][$p]->season_related == 0) { //update
                    ?>
					<tr>
						<td width="100">
							<?php echo $lists['ext_fields_sr'][$p]->name;
                                ?>
						</td>
						<td>
						<?php

                        switch ($lists['ext_fields_sr'][$p]->field_type) {

                            case '1':    echo $lists['ext_fields_sr'][$p]->selvals;
                                        break;
                            case '2':    echo $editor->display('extraf['.$lists['ext_fields_sr'][$p]->id.']',  htmlspecialchars(isset($lists['ext_fields_sr'][$p]->fvalue_text) ? ($lists['ext_fields_sr'][$p]->fvalue_text) : '', ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore'));
                                        break;
                            case '3':    echo $lists['ext_fields_sr'][$p]->selvals;
                                        break;
                            case '0':
                            default:    echo '<input type="text" maxlength="255" size="60" name="extraf['.$lists['ext_fields_sr'][$p]->id.']" value="'.(isset($lists['ext_fields_sr'][$p]->fvalue) ? htmlspecialchars($lists['ext_fields_sr'][$p]->fvalue) : '').'" />';
                                        break;

                        }
                                ?>
							<input type="hidden" name="extra_ftype[<?php echo $lists['ext_fields_sr'][$p]->id?>]" value="<?php echo $lists['ext_fields_sr'][$p]->field_type?>" />
							<input type="hidden" name="extra_id[<?php echo $lists['ext_fields_sr'][$p]->id?>]" value="<?php echo $lists['ext_fields_sr'][$p]->id?>" />
						</td>
					</tr>
				<?php

                            }
                        }
                    }
        //}
            ?>
                    </table>
                </div>
            </div>
            <?php

}
        ?>
                </div>
        </div>
    </div>    
		<input type="hidden" name="option" value="com_joomsport" />
		<input type="hidden" name="task" value="player_edit" />
		<input type="hidden" name="id" value="<?php echo $row->id?>" />
		<input type="hidden" name="cid[]" value="<?php echo $row->id?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="jscurtab" id="jscurtab" value="" />
                <input type="hidden" name="jsgallery" value="" />
		<?php echo JHTML::_('form.token'); ?>
	</form>