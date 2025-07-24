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
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
        require_once JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_joomsport'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'tabs.php';
        $etabs = new esTabs();
        ?>
		<script type="text/javascript">
		Joomla.submitbutton = function(task) {
			submitbutton(task);
		}
		function submitbutton(pressbutton) {
			var form = document.adminForm;
                        
			 if(pressbutton == 'team_apply' || pressbutton == 'team_save' || pressbutton == 'team_save_new'){
                 var reg=/^\s+$/;
                 jQuery('input[name="jsgallery"]').val(JSON.stringify(jQuery('input[name^="filnm"]').serializeArray()));
                        
                 if(form.t_name.value != '' && !reg.test(form.t_name.value)){
					var srcListName = 'seas_all_add';
					var srcList = eval( 'form.' + srcListName );
					if(srcList){
						var srcLen = srcList.length;
					
						for (var i=0; i < srcLen; i++) {
								srcList.options[i].selected = true;
						}
					}
					submitform( pressbutton );
					return;
				}else{
					getObj('tmname').style.border = "1px solid red";
					alert("<?php echo JText::_('BLBE_JSMDNOT16');?>");
					
					
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
		
		function addplayer(){
			if(getObj('playerz_id').value != 0){
                             
				var tbl = getObj('add_pl');
				var row = tbl.insertRow(tbl.rows.length-1);
				var cell1 = document.createElement("td");
				var cell2 = document.createElement("td");
				
				var input_hd = document.createElement('input');
				input_hd.type = 'hidden';
				input_hd.name = 'teampl[]';
				input_hd.value = getObj('playerz_id').value;
						
				cell1.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this);chngPlayerList(); return false;" title="<?php echo JText::_('BLBE_DELETE');?>"><img src="components/com_joomsport/img/publish_x.png"  bOrder="0" alt="Delete"></a>';
				cell1.appendChild(input_hd);
				cell2.innerHTML = getObj('playerz_id').options[getObj('playerz_id').selectedIndex].text;
				row.appendChild(cell1);
				row.appendChild(cell2);

				var elems = getObj('playerz_id').getElementsByTagName('option');
				for(var i=0;i<elems.length;i++){
					if(elems[i].value == getObj('playerz_id').value){
						elems[i].style.display = 'none';
					}
				}
				chngPlayerList();
                                
                                
				
			}else{
				alert("<?php echo JText::_('BLBE_SELPLAYER');?>");
			}
			
		}
		function delete_logo(){
			getObj("logoiddiv").innerHTML = '';
		}
                function loadSeasonParms(){
                    jQuery.ajax({
                        url: "index.php?option=com_joomsport&task=getTeamFieldsBySeason&tmpl=component&no_html=1",
                        type: 'POST',
                        data: {sid : jQuery('#seasf_id').val(), team_id : document.adminForm.id.value},

                      }).done(function(res) {
                        jQuery("#jsAjaxContSeason").html(res);
                        jQuery("#jsAjaxContSeason ").find("select").chosen({disable_search_threshold: 10});
                        
                      });
                } 
                jQuery(document).ready(function(){
                    jQuery('#seas_all_add').on("change", function(){
                        if('<?php echo $row->id?>' != '0'){
                            jQuery("#seasrelatedmessbox").html("<div class='jswarningbox'><p><?php echo JText::_('BLBE_WARN_ASSIGNUPDATED');?></p></div>");
                        }    
                    });
                    
                    
                });
                
                
                
                function chngPlayerList(){
                    var players = jQuery( "[name^='teampl']" ).serializeArray();
                    jQuery.ajax({
                        url: "index.php?option=com_joomsport&task=getPlayerList&tmpl=component&no_html=1",
                        type: 'POST',
                        data: {players:players},

                      }).done(function(res) {
                        jQuery("#playerz_id").html(res);
                        jQuery("#playerz_id").trigger("liszt:updated");
                        
                      });
    
                }
                
		</script>
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
        echo $etabs->newTab(JText::_('BLBE_MAIN'), 'main_team', '', 'vis');
                echo $etabs->newTab(JText::_('BLBE_TABPLAYERS'), 'players_conf', '');

        ?>
		</ul>
		<div style="clear:both"></div>
            </div>    
        </div>
    </div>   

    <div id="main_team_div" class="tabdiv">
	<div class="jsrespdiv12">
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_GENERAL'); ?>
                </div>
                <div class="jsBEsettings">	
		<table>
			<tr>
				<td width="200">
					<?php echo JText::_('BLBE_TEAMNAME'); ?>
                                    <?php
                                    if(count($lists['languages'])){

                                        echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                    }?>
				</td>
				<td>
					<input type="text" maxlength="255" size="60" name="t_name" id="tmname" value="<?php echo htmlspecialchars($row->t_name)?>" />
                                        <?php
                                        if(count($lists['languages'])){

                                            echo '<div class="jsTranslationContainer">';
                                            foreach (($lists['languages']) as $value) {
                                                echo '<div class="jsTranslationDiv">';
                                                $translation = '';

                                                if(isset($lists['translation']) && isset($lists['translation'][$value]['t_name'])){
                                                    $translation = htmlspecialchars($lists['translation'][$value]['t_name'], ENT_QUOTES);
                                                }
                                                echo '<input type="text" maxlength="255" size="60" name="translation['.$value.'][t_name]" value="'.addslashes($translation).'"/>';
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
                                    <input type="text" maxlength="255" size="60" name="short_name" id="short_name" value="<?php echo htmlspecialchars($row->short_name)?>" />
                                    <?php
                                        if(count($lists['languages'])){

                                            echo '<div class="jsTranslationContainer">';
                                            foreach (($lists['languages']) as $value) {
                                                echo '<div class="jsTranslationDiv">';
                                                $translation = '';

                                                if(isset($lists['translation']) && isset($lists['translation'][$value]['t_short_name'])){
                                                    $translation = htmlspecialchars($lists['translation'][$value]['t_short_name'], ENT_QUOTES);
                                                }
                                                echo '<input type="text" maxlength="255" size="60" name="translation['.$value.'][t_short_name]" value="'.addslashes($translation).'"/>';
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
                                    <?php echo JText::_('BLBE_TEAM_MIDDLENAME'); ?> 
                                    <?php
                                    if(count($lists['languages'])){

                                        echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                    }?>
				</td>
                                <td>
                                    <input type="text" maxlength="255" size="60" name="middle_name" id="middle_name" value="<?php echo htmlspecialchars($row->middle_name)?>" />
                                    <?php
                                        if(count($lists['languages'])){

                                            echo '<div class="jsTranslationContainer">';
                                            foreach (($lists['languages']) as $value) {
                                                echo '<div class="jsTranslationDiv">';
                                                $translation = '';

                                                if(isset($lists['translation']) && isset($lists['translation'][$value]['t_middle_name'])){
                                                    $translation = htmlspecialchars($lists['translation'][$value]['t_middle_name'], ENT_QUOTES);
                                                }
                                                echo '<input type="text" maxlength="255" size="60" name="translation['.$value.'][t_middle_name]" value="'.addslashes($translation).'"/>';
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
					<?php echo JText::_('BLBE_CITY'); ?>
                                    <?php
                                    if(count($lists['languages'])){

                                        echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                    }?>
				</td>
				<td>
					<input type="text" maxlength="255" size="60" name="t_city" value="<?php echo htmlspecialchars($row->t_city)?>" />
                                        <?php
                                        if(count($lists['languages'])){

                                            echo '<div class="jsTranslationContainer">';
                                            foreach (($lists['languages']) as $value) {
                                                echo '<div class="jsTranslationDiv">';
                                                $translation = '';

                                                if(isset($lists['translation']) && isset($lists['translation'][$value]['t_city'])){
                                                    $translation = htmlspecialchars($lists['translation'][$value]['t_city'], ENT_QUOTES);
                                                }
                                                echo '<input type="text" maxlength="255" size="60" name="translation['.$value.'][t_city]" value="'.addslashes($translation).'"/>';
                                                echo '  ' . $value;
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                        }
                                        ?>
                                </td>
			</tr>
			
			
			

			<tr>
				<td valign="top">
					<?php echo JText::_('BLBE_TEAM_LOGO'); ?>
				</td>
				<td>
					<input type="file" name="t_logo" id="logo"/>
					<br />
					<div id="logoiddiv">
					<?php

                    if ($row->t_emblem && is_file('../media/bearleague/'.$row->t_emblem)) {
                        echo '<img class="thumbnail" src="'.JURI::base().'../media/bearleague/'.$row->t_emblem.'" width="100" />';
                        echo '<input type="hidden" name="istlogo" value="1" />';
                        echo '<input type="hidden" name="uplLogo" value="'.$row->t_emblem.'" />';
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
                        <?php 
                        if ($lists['unbl_venue'] == '1') {
                            ?>
			<tr>
				<td>
					<?php echo JText::_('BLBE_VENUE');
                            ?>
					
				</td>
				<td>
					<?php 
                                        if (count($lists['is_venue'])) {
                                            echo $lists['venue'];
                                        } else {
                                            echo JText::sprintf('BLBE_WARN_VENUENOCREATED', '<a href="index.php?option=com_joomsport&task=venue_list">', '</a>');
                                        }
                            ?>
				</td>
			</tr>
                        <?php

                        }
                        ?>
                        <?php if ($lists['enbl_club']) {
    ?>
			<tr>
				<td>
					<?php echo JText::_('BLBE_CLUB');
    ?>
					
				</td>
				<td>
					<?php echo $lists['club'];
    ?>
					
				</td>
			</tr>
                        <?php 
} ?>
			<tr>
				<td width="200">
					<?php echo JText::_('BLBE_ABOUT_TEAM'); ?>
                                    <?php
                                    if(count($lists['languages'])){

                                        echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                    }?>
				</td>
				<td>
					<?php echo $editor->display('t_descr',  htmlspecialchars($row->t_descr, ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore'));  ?>
                                    <?php
                                    if(count($lists['languages'])){
                                        echo '<div class="jsTranslationContainer">';
                                        foreach (($lists['languages']) as $value) {
                                            echo '<div class="jsTranslationDiv">';
                                            $translation = '';
                                            echo $value;
                                            if(isset($lists['translation']) && isset($lists['translation'][$value]['t_descr'])){
                                                $translation = $lists['translation'][$value]['t_descr'];
                                            }
                                            echo $editor->display('translation['.$value.'][t_descr]',  htmlspecialchars($translation, ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore'));
                                            
                                            echo '</div>';
                                        }
                                        echo '</div>';
                                    }
                                    ?>
				</td>
			</tr>
		</table>
		<?php 
        if (isset($this->lists['seasall'])) {
            ?>
		<table  border="0">
			<tr>
				<td width="200">
					<?php echo JText::_('BLBE_ADD_SEASON');
            ?>
					
				</td>
				<td width="150">
                                    <div class="selectedlistdescr"><?php echo JText::_('BLBE_AVAILABLE')?></div>
					<?php echo $this->lists['seasall'];
            ?>
				</td>
				<td valign="middle" width="60" align="center">
					<input class="btn" type="button" style="cursor:pointer;" value=">>" onClick="javascript:JS_addSelectedToList('adminForm','seas_all','seas_all_add');" /><br />
					<input class="btn" type="button" style="cursor:pointer;" value="<<" onClick="javascript:JS_delSelectedFromList('adminForm','seas_all_add','seas_all');" />
				</td>
				<td >
                                    <div class="selectedlistdescr"><?php echo JText::_('BLBE_SELECTED')?></div>
					<?php echo $this->lists['seasall_add'];
            ?>
				</td>
			</tr>
		</table>
               
		<?php 
        } ?>
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
    
    <div id="players_conf_div" class="tabdiv" style="display:none;">
        <div class="jsrespdiv12">
            <div style="margin-bottom: 15px;">
                <?php if ($lists['is_seas']) {
    echo JText::_('BLBE_SELSEASN').':&nbsp;&nbsp;';
} ?>
                <?php echo $lists['seasf'];?>
                <div id="seasrelatedmessbox">
                <?php
                if (!$lists['is_seas']) {
                    echo '<div class="jswarningbox">';
                    echo '<p>'.JText::sprintf('BLBE_WARN_SAVETEAM', '<a href="javascript:show_etabs(\'main_team\')">', '</a>').'</p>';
                    echo '</div>';
                }

                ?>
                </div>
            </div>
            <div id="jsAjaxContSeason">
            <div class="jsrespdiv6">   
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_MENPL'); ?>
                </div>
                
                <div class="jsBEsettings">
                <?php 
                if (!$lists['is_seas']) {
                    echo '<div class="jswarningbox">';
                    echo '<p>'.JText::_('BLBE_WARN_TEAMNASSIGN').'</p>';
                    echo '</div>';
                }
                
        echo '<table class="table table-striped" id="add_pl">';

        for ($i = 0;$i < count($lists['team_players']);++$i) {
            $pl = $lists['team_players'][$i];
            echo '<tr><td><a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this);chngPlayerList(); return false;" title="'.JText::_('BLBE_DELETE').'"><img src="components/com_joomsport/img/publish_x.png"  bOrder="0" alt="Delete"></a><input type="hidden" name="teampl[]" value="'.$pl->id.'" /></td><td>'.$pl->name.'</td></tr>';
        }
        ?>
			<tr>
				<td colspan="2" class="cntrl-newplayer">
					<?php echo $lists['player']; ?>
					<input type="button" class="btn" value="<?php echo JText::_('BLBE_ADD');?>" onclick="addplayer();" />
				</td>
			</tr>
		</table>
                </div>
            </div>
        </div>
        <div class="jsrespdiv6 jsrespmarginleft2">        
            <?php 
            if ($lists['seasf_id'] > 0) {
                ?>
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_BONUSES');
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
            ?>
            
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_FIELDSBYSEASON'); ?>
                </div>
                <div class="jsBEsettings">
                <table class="adminlistsNoBorder">
                    <?php 
                    if ($lists['ext_fields_sr'] && count($lists['ext_fields_sr'])) {
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
                            case '5':    echo $lists['ext_fields_sr'][$p]->selvals;
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
                            case '5':    echo $lists['ext_fields_sr'][$p]->selvals;
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
                    } else {
                        echo JText::_('BLBE_WARN_NOTEAMEFASTOSEAS');
                    }
        //}             
            ?>
                    </table>
                </div>
            </div>
        </div>    
            
                </div>
        </div>
    </div>    
		
		
		<input type="hidden" name="option" value="com_joomsport" />
		<input type="hidden" name="task" value="team_edit" />
		<input type="hidden" name="id" value="<?php echo $row->id?>" />
		<input type="hidden" name="cid[]" value="<?php echo $row->id?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="jscurtab" id="jscurtab" value="" />
                <input type="hidden" name="jsgallery" value="" />
		<?php echo JHTML::_('form.token'); ?>
	</form>
                
