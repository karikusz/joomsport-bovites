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
        ?>
		<script type="text/javascript">
		Joomla.submitbutton = function(task) {
			submitbutton(task);
		}
		function submitbutton(pressbutton) {
			var form = document.adminForm;
            var reg = /^\s+$/;
			 if(pressbutton == 'fields_apply' || pressbutton == 'fields_save' || pressbutton == 'fields_save_new'){
                 if(form.name.value != '' && !reg.test(form.name.value)){
					
					var selnames = eval("document.adminForm['selnames[]']");
					
				
						
					if(!selnames && getObj('field_type').value==3){
						getObj('addsel').style.border = "1px solid red";
					}else{
						submitform( pressbutton );
						return;
					}	
				}else{
					getObj('fldname').style.border = "1px solid red";
					alert("<?php echo JText::_('BLBE_JSMDNOT19');?>");
					
					
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
                        ReAnalize_tbl_Rows("seltable");
		}	
		
		function add_selval(){
			if(!getObj("addsel").value){
				return false;
			}
			var tbl_elem = getObj("seltable");
			var row = tbl_elem.insertRow(tbl_elem.rows.length - 2);
			var cell1 = document.createElement("td");
			var cell2 = document.createElement("td");
			var cell3 = document.createElement("td");
			var cell4 = document.createElement("td");
			
			cell1.innerHTML = '<input type="hidden" name="adeslid[]" value="0" /><a href="javascript:void(0);" title="Remove" onClick="javascript:Delete_tbl_row(this);"><input type="hidden" value="0" name="selid[]" /><img src="<?php echo JURI::base()?>components/com_joomsport/img/publish_x.png" title="Remove" /></a>';
			
			var inp = document.createElement('input');
			inp.type="text";
			inp.setAttribute('maxlength',255);
			inp.value = getObj("addsel").value;
			inp.name = 'selnames[]';
			inp.setAttribute('size',50);
			cell2.appendChild(inp);
			row.appendChild(cell1);
			row.appendChild(cell2);
			row.appendChild(cell3);
			row.appendChild(cell4);
			
			getObj("addsel").value = '';
			
			ReAnalize_tbl_Rows("seltable");
		}
////		
function ReAnalize_tbl_Rows( tbl_id ) {
			start_index =1;
			var tbl_elem = getObj(tbl_id);
			if (tbl_elem.rows[start_index]) {
				for (var i=start_index; i<tbl_elem.rows.length-2; i++) {
					
					
					
					if (i > 1) { 
						tbl_elem.rows[i].cells[2].innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_joomsport/img/up.gif"  border="0" alt="Move Up"></a>';
					} else { tbl_elem.rows[i].cells[2].innerHTML = ''; }
					if (i < (tbl_elem.rows.length - 3)) {
						tbl_elem.rows[i].cells[3].innerHTML = '<a href="javascript: void(0);" onClick="javascript:Down_tbl_row(this); return false;" title="Move Down"><img src="components/com_joomsport/img/down.gif"  border="0" alt="Move Down"></a>';
					} else { tbl_elem.rows[i].cells[3].innerHTML = ''; }

				}
			}
		}
		
		

		
		function Up_tbl_row(element) { 
			if (element.parentNode.parentNode.sectionRowIndex > 1) {
				var sec_indx = element.parentNode.parentNode.sectionRowIndex;
				var table = element.parentNode.parentNode.parentNode;
				var tbl_id = table.parentNode.id; 
				
				var row = table.insertRow(sec_indx - 1);

				row.appendChild(element.parentNode.parentNode.cells[0]);
				row.appendChild(element.parentNode.parentNode.cells[0]);
				//row.appendChild(element.parentNode.parentNode.cells[0]);
				//row.appendChild(element.parentNode.parentNode.cells[0]);
				//row.appendChild(element.parentNode.parentNode.cells[0]);
				//row.appendChild(element.parentNode.parentNode.cells[0]);
				
				var cell3 = document.createElement("td");
				var cell4 = document.createElement("td");
				row.appendChild(cell3);
				row.appendChild(cell4);
				element.parentNode.parentNode.parentNode.deleteRow(element.parentNode.parentNode.sectionRowIndex);
				
				ReAnalize_tbl_Rows(tbl_id);
			}
		}

		function Down_tbl_row(element) { 
			if (element.parentNode.parentNode.sectionRowIndex < element.parentNode.parentNode.parentNode.rows.length - 1) {
				var sec_indx = element.parentNode.parentNode.sectionRowIndex;
				var table = element.parentNode.parentNode.parentNode;
				var tbl_id = table.parentNode.id;
				
				var row = table.insertRow(sec_indx + 2);

				row.appendChild(element.parentNode.parentNode.cells[0]);
				row.appendChild(element.parentNode.parentNode.cells[0]);
				//row.appendChild(element.parentNode.parentNode.cells[0]);
				//row.appendChild(element.parentNode.parentNode.cells[0]);
				//row.appendChild(element.parentNode.parentNode.cells[0]);
				//row.appendChild(element.parentNode.parentNode.cells[0]);
				
				var cell3 = document.createElement("td");
				var cell4 = document.createElement("td");
				row.appendChild(cell3);
				row.appendChild(cell4);
				element.parentNode.parentNode.parentNode.deleteRow(element.parentNode.parentNode.sectionRowIndex);
				
				ReAnalize_tbl_Rows(tbl_id);
			}

			
		}	
		
////		
		function shide(){
			if(getObj('field_type').value==3){
				getObj("seltable").style.display='block';
			}else{
				getObj("seltable").style.display='none';
			}
                        if(getObj('field_type').value==5){
                            jQuery('.shpersoncat').show();
                        }else{
                            jQuery('.shpersoncat').hide();
                        }    
                        if(getObj('field_type').value==6){
                            jQuery('.shdateopt').show();
                        }else{
                            jQuery('.shdateopt').hide();
                        }  
                        if(jQuery('select[name="field_type"]').val() == 5 && jQuery('select[name="type"]').val() == 1){
                                jQuery(".jsw_personroster_ef").show();
                        }else{
                                jQuery(".jsw_personroster_ef").hide();
                        }
		}
		function tblview_hide(){
			if(getObj('type').value < 2){
				getObj("tbl_fv_1").style.visibility='visible';
				getObj("tbl_fv_2").style.visibility='visible';
				getObj("tbl_seasr_1").style.visibility='visible';
				getObj("tbl_seasr_2").style.visibility='visible';
			}else{
				getObj("tbl_fv_1").style.visibility='hidden';
				getObj("tbl_fv_2").style.visibility='hidden';
				getObj("tbl_seasr_1").style.visibility='hidden';
				getObj("tbl_seasr_2").style.visibility='hidden';
			}
                        if(getObj('type').value == '0'){
                            jQuery(".pllistdiv").show();
                        }else{
                            jQuery(".pllistdiv").hide();
                        }    
                        if(jQuery('select[name="field_type"]').val() == 5 && jQuery('select[name="type"]').val() == 1){
                                jQuery(".jsw_personroster_ef").show();
                        }else{
                                jQuery(".jsw_personroster_ef").hide();
                        }
		}
		
		function getClick(){
			getClick.count++;
			getIdex = <?php echo $row->id ? $row->id : 0;?>;
			if(getClick.count == 1 && getIdex){alert("<?php echo addslashes(JText::_('SEASRELEXTRA'));?>");}
		}
		getClick.count = 0;
		</script>
		<form action="index.php?option=com_joomsport" method="post" name="adminForm" id="adminForm">
<div class="jsrespdiv8">
    <div class="jsBepanel">
        <div class="jsBEheader">
            <?php echo JText::_('BLBE_GENERAL'); ?>
        </div>
        <div class="jsBEsettings">		
		<table>
			<tr>
				<td width="250">
					<?php echo JText::_('BLBE_FIELDNAME'); ?>
                                    <?php
                                    if(count($lists['languages'])){

                                        echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                    }?>
				</td>
				<td>
					<input type="text" maxlength="255" size="60" name="name" id="fldname" value="<?php echo htmlspecialchars($row->name)?>" onKeyPress="return disableEnterKey(event);" />
                                        <?php
                                        if(count($lists['languages'])){

                                            echo '<div class="jsTranslationContainer">';
                                            foreach (($lists['languages']) as $value) {
                                                echo '<div class="jsTranslationDiv">';
                                                $translation = '';

                                                if(isset($lists['translation']) && isset($lists['translation'][$value]['name'])){
                                                    $translation = htmlspecialchars($lists['translation'][$value]['name'], ENT_QUOTES);
                                                }
                                                echo '<input type="text" maxlength="255" size="60" name="translation['.$value.'][name]" value="'.addslashes($translation).'"/>';
                                                echo '  ' . $value;
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                        }
                                        ?>
                                </td>
			</tr>
			
			<tr>
				<td width="250">
					<?php echo JText::_('BLBE_FIELDTYP'); ?>
				</td>
				<td>
					<?php echo $lists['field_type'];?>
				</td>
			</tr>
			<tr>
				<td width="250">
					<?php echo JText::_('BLBE_FEVENTTYPE'); ?>
				</td>
				<td>
					<?php echo $lists['is_type'];?>
				</td>
			</tr>
                        <tr class="shpersoncat">
				<td width="250">
					<?php echo JText::_('BLBE_PERSON_CATEGORY'); ?>
				</td>
				<td>
					<?php echo $lists['category'];?>
				</td>
			</tr>
                        <?php
                        $stf = '';
                        if ($row->type != 1) {
                            $stf = 'style="display:none;"';
                        }
                        ?>
                        <tr class="jsw_personroster_ef" <?php echo $stf;?>>
                            <td width="250">
                                    <?php echo JText::_('BLBE_PERSON_IN_ROSTER'); ?>
                            </td>
                            <td>
                                <div class="controls"><fieldset class="radio btn-group"><?php echo $this->lists['in_roster'];?></fieldset></div>
                                
                            </td>
                            
                        </tr>
                        <tr class="shdateopt">
				<td width="250">
					<?php echo JText::_('BLBE_DATE_DISPLAY'); ?>
				</td>
				<td>
					<?php echo $lists['dateage'];?>
				</td>
			</tr>
                        <script>
                            if(getObj('field_type').value==5){
                                jQuery('.shpersoncat').show();
                            }else{
                                jQuery('.shpersoncat').hide();
                            }   
                            if(getObj('field_type').value==6){
                                jQuery('.shdateopt').show();
                            }else{
                                jQuery('.shdateopt').hide();
                            } 
                        </script>    
			<?php
            $stf = 'style="visibility:visible;"';
            if ($row->type >= 2) {
                $stf = 'style="visibility:hidden;"';
            }
            ?>
			<tr>
				<td width="250" id="tbl_fv_1" <?php echo $stf;?>>
					<?php echo JText::_('BLBE_FIELDTABVIEW'); ?>
					<span class="editlinktip hasTip" title="<?php echo JText::_('BLBE_FIELDTABVIEW'); ?>::<?php echo JText::_('BLBE_TT_FIELDTABVIEW');?>"><img class="imgQuest" src="components/com_joomsport/img/quest.png" bOrder="0" /></span>
				</td>
				<td id="tbl_fv_2" <?php echo $stf;?>>
                    <div class="controls"><fieldset class="radio btn-group"><?php echo $this->lists['t_view'];?></fieldset></div>

				</td>
			</tr>
			<?php
            $stf = 'style="visibility:visible;"';
            if ($row->type >= 2) {
                $stf = 'style="visibility:hidden;"';
            }
            ?>
			<tr>
				<td width="250" id="tbl_seasr_1" <?php echo $stf;?>>
					<?php echo JText::_('BLBE_SEASON_RELATED'); ?>
					<span class="editlinktip hasTip" title="<?php echo JText::_('BLBE_SEASON_RELATED'); ?>::<?php echo JText::_('BLBE_TT_SEASON_RELATED');?>"><img class="imgQuest" src="components/com_joomsport/img/quest.png" bOrder="0" /></span>
				</td>
				<td id="tbl_seasr_2" <?php echo $stf;?>>
                    <div class="controls"><fieldset class="radio btn-group"><?php echo $this->lists['season_related'];?></fieldset></div>
				</td>
			</tr>
                        <tr>
                            <?php
                            $stf = 'style="display:none;"';
                            if (!$row->type) {
                                $stf = 'style="display:table-cell;"';
                            }
                            ?>
                            <td width="250" class="pllistdiv" <?php echo $stf;?>>
                                <?php echo JText::_('BLBE_DISPLAY_PLAYERLIST'); ?>
                            </td>
                            <td class="pllistdiv" <?php echo $stf;?>>
                                <div class="controls"><fieldset class="radio btn-group"><?php echo $this->lists['display_playerlist'];?></fieldset></div>
                            </td>
			</tr>
                        
			
		</table>
		<br />
		<?php
        $st = 'style="display:none;"';
        if ($row->field_type == '3') {
            $st = 'style="display:block;"';
        }
        ?>
		<table id="seltable" <?php echo $st?>>
			<tr>
				<th></th>
				<th></th>
			</tr>
			<?php
            for ($i = 0;$i < count($lists['selval']);++$i) {
                echo '<tr>';
                echo '<td><input type="hidden" name="adeslid[]" value="'.$lists['selval'][$i]->id.'" /><a href="javascript:void(0);" title="Remove" onClick="javascript:Delete_tbl_row(this);"><img src="'.JURI::base().'components/com_joomsport/img/publish_x.png" title="Remove" /></a></td>';
                echo "<td>";
                echo "<input type='text' name='selnames[]' size='50' value='".htmlspecialchars($lists['selval'][$i]->sel_value, ENT_QUOTES)."' />";
                
                if(count($lists['languages'])){

                    echo '<span><img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" /></span>';
                }
                if(count($lists['languages'])){
                    echo '<div class="jsTranslationContainer">';
                    foreach (($lists['languages']) as $value) {
                        echo '<div class="jsTranslationDiv">';
                        $translation = '';

                        if(isset($lists['translation']) && isset($lists['translation'][$value]['selectfield_'.$lists['selval'][$i]->id])){
                            $translation = htmlspecialchars($lists['translation'][$value]['selectfield_'.$lists['selval'][$i]->id], ENT_QUOTES);
                        }
                        echo '<input type="text" maxlength="255" size="50" name="translation['.$value.'][selectfield_'.$lists['selval'][$i]->id.']" value="'.addslashes($translation).'"/>';
                        echo '  ' . $value;
                        echo '</div>';
                    }
                    echo '</div>';
                }
                echo "</td>";
                echo '<td>';
                if ($i > 0) {
                    echo '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_joomsport/img/up.gif"  border="0" alt="Move Up"></a>';
                }
                echo '</td>';
                echo '<td>';
                if ($i < count($lists['selval']) - 1) {
                    echo '<a href="javascript: void(0);" onClick="javascript:Down_tbl_row(this); return false;" title="Move Down"><img src="components/com_joomsport/img/down.gif"  border="0" alt="Move Down"></a>';
                }
                echo '</td>';
                echo '</tr>';
            }
            ?>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<th><input class="btn btn-small" type="button" style="cursor:pointer;" value="<?php echo JText::_('BLBE_ADDCHOICE');?>" onclick="add_selval();" /></th>
                                <th><input style="margin:0px;" type="text" maxlength="255" size="50" name="addsel" value="" id="addsel" /></th>
			</tr>
		</table>
            </div>
        </div>
    </div>
    <div class="jsrespdiv4 jsrespmarginleft2">
        <div class="jsBepanel">
            <div class="jsBEheader">
                <?php echo JText::_('JGLOBAL_FIELDSET_PUBLISHING'); ?>
            </div>
            <div class="jsBEsettings">
                <table>
                    <tr>
                        <td width="250">
                                <?php echo JText::_('JSTATUS'); ?>
                        </td>
                        <td>
                            <div class="controls"><fieldset class="radio btn-group"><?php echo $this->lists['published'];?></fieldset></div>
                        </td>
                    </tr>
                    <tr>
                        <td width="250">
                                <?php echo JText::_('BLBE_VISFOR'); ?>
                        </td>
                        <td>
                                <?php echo $lists['faccess'];?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>    
    </div> 
		
		<input type="hidden" name="option" value="com_joomsport" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo $row->id?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_('form.token'); ?>
	</form>