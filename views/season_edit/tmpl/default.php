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
require_once JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_joomsport'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'tabs.php';
        $etabs = new esTabs();
        ?>
		<script type="text/javascript" src="components/com_joomsport/color_piker/201a.js"></script>
		<script type="text/javascript">
                var tournament_type = '<?php echo $this->lists['tournament_type']?>';   
		var colors_count = parseInt('<?php echo count($this->lists['colors']) ? count($this->lists['colors']) : 1?>');
		Joomla.submitbutton = function(task) {
			submitbutton(task);
		}
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'season_save' || pressbutton == 'season_apply' || pressbutton == 'season_save_new') {
                var reg = /^\s+$/;
                if(form.s_name.value && !reg.test(form.s_name.value)){
				
					if( tournament_type == 0 && (form.s_win_point.value == '' || form.s_draw_point.value == '' || form.s_lost_point.value == '' || (getObj('s_enbl_extra1').checked && (form.s_extra_win.value == '' || form.s_extra_lost.value == '')))){
						alert("<?php echo JText::_('BLBE_JSMDNOT8'); ?>");

					}else
					if(form.t_id.value != "0"){
						if( getObj("s_reg0").checked || form.reg_start.value == '0000-00-00 00:00:00' || form.reg_end.value == '0000-00-00 00:00:00' || form.reg_end.value == '' || form.reg_start.value == '' || form.reg_start.value < form.reg_end.value){
								
									var srcListName = 'teams_season';
									var srcList = eval( 'form.' + srcListName );
								
									var srcLen = srcList.length;
								
									for (var i=0; i < srcLen; i++) {
											srcList.options[i].selected = true;
									} 
									
									var srcListName2 = 'usr_admins';
									var srcList2 = eval( 'form.' + srcListName2 );
								
									var srcLen2 = srcList2.length;
								
									for (var i=0; i < srcLen2; i++) {
											srcList2.options[i].selected = true;
									} 
									
														
									submitform( pressbutton );
									return;
								
						}else{
							alert("<?php echo JText::_('BLBE_JSMDNOT99'); ?>");	
						}						
					}else{	
						alert("<?php echo JText::_('BLBE_SELTOURNAMENT'); ?>");	
					}
				}else{
					getObj("easname").style.border = "1px solid red";
					alert("<?php echo JText::_('BLBE_JSMDNOT10'); ?>");	
				}				
			}else{
				submitform( pressbutton );
					return;
			}
		}	
		
		function showopt(){
			if(getObj('s_enbl_extra1').checked){
				getObj('extraoptions').style.display = 'block';
			}else{
				getObj('extraoptions').style.display = 'none';
			}
		}
		
		function add_colors(){
			var cell = document.createElement("div");
                        cell.className = 'jscolordivcont';
			colors_count++;
			var input_hidden = document.createElement("input");
			input_hidden.type = "text";
			input_hidden.name = 'input_field_'+colors_count;
			input_hidden.id = 'input_field_'+colors_count;
			input_hidden.value = '';
                        input_hidden.className = 'jscolorinp';
			input_hidden.size = 9;
			var input_hidden2 = document.createElement("input");
			input_hidden2.type = "text";
			input_hidden2.id = 'sample_'+colors_count;
			input_hidden2.value = '';
			input_hidden2.size = 1;
            input_hidden2.style.width = '30px';
			var input_hidden3 = document.createElement("input");
			input_hidden3.type = "text";
			input_hidden3.name = 'place_'+colors_count;
			input_hidden3.value = '';
			input_hidden3.size = 5;
            input_hidden3.style.width = '30px';
			
			input_hidden3.onblur = function(){extractNumber2(this,0,false);}; 
			input_hidden3.onkeyup = function(){extractNumber2(this,0,false);};
			input_hidden3.onkeypress = function(){return blockNonNumbers2(this, event, true, false);};
			
                        var input_hidden4 = document.createElement("input");
			input_hidden4.type = "text";
			input_hidden4.name = 'legend_'+colors_count;
			input_hidden4.value = '';
			
                        input_hidden4.style.width = '100px';
                        
			cell.innerHTML = '<input class="btn btn-small" type="button" style="cursor:pointer;" onclick="showColorGrid2(\'input_field_'+colors_count+'\',\'sample_'+colors_count+'\');" value="..." class="color-kind">&nbsp;';
			
			var txtnode2 = document.createTextNode(" <?php echo JText::_('BLBE_PLACE');?>:  ");
			var txtnode4 = document.createTextNode(" <?php echo JText::_('BLBE_PLACE_LEGEND');?>:  ");
			
                        cell.appendChild(input_hidden);
			cell.appendChild(input_hidden2);
			cell.appendChild(txtnode2);
			
			cell.appendChild(input_hidden3);
                        cell.appendChild(txtnode4);
                        cell.appendChild(input_hidden4);
			
			getObj('app_newcol').appendChild(cell);
			document.adminForm.col_count.value = colors_count;
		}
		
		
		function bl_add_map(){
			var cur_map = getObj('maps_id');
			
			if (cur_map.value == 0) {
				alert("<?php echo JText::_('BLBE_JSMDNOT201')?>");return;
			}
		
			
			var tbl_elem = getObj('map_tbl');
			var row = tbl_elem.insertRow(tbl_elem.rows.length);
			var cell1 = document.createElement("td");
			var cell2 = document.createElement("td");
			
			cell1.style.width="50px";
			
			cell1.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this);chngPlayerList(); return false;" title="<?php echo JText::_('BLBE_DELETE');?>"><img src="components/com_joomsport/img/publish_x.png"  bOrder="0" alt="Delete"></a>';
			
			var input_hidden = document.createElement("input");
			input_hidden.type = "hidden";
			input_hidden.name = "maps_s[]";
			input_hidden.value = cur_map.value;
			cell2.innerHTML = cur_map.options[cur_map.selectedIndex].text;
			cell2.appendChild(input_hidden);
			
			cur_map.options[cur_map.selectedIndex] = null;
			
			row.appendChild(cell1);
			row.appendChild(cell2);
			
			getObj('maps_id').value =  0;
                        jQuery("#maps_id").trigger("liszt:updated");
		
		}
		
		function Delete_tbl_row(element) {
			var del_index = element.parentNode.parentNode.sectionRowIndex;
			var tbl_id = element.parentNode.parentNode.parentNode.parentNode.id;
			element.parentNode.parentNode.parentNode.deleteRow(del_index);
		}
		
		function ReAnalize_tbl_Rows( tbl_id ) {
			start_index = 2;
			var tbl_elem = getObj(tbl_id);
			if (tbl_elem.rows[start_index]) {
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					
					
					
					if (i > 2) { 
						tbl_elem.rows[i].cells[0].innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('BLBE_MOVEUP');?>"><img src="components/com_joomsport/img/up.gif"  border="0" alt="<?php echo JText::_('BLBE_MOVEUP');?>"></a>';
					} else { tbl_elem.rows[i].cells[0].innerHTML = ''; }
					if (i < (tbl_elem.rows.length - 1)) {
						tbl_elem.rows[i].cells[1].innerHTML = '<a href="javascript: void(0);" onClick="javascript:Down_tbl_row(this); return false;" title="<?php echo JText::_('BLBE_MOVEDOWN');?>"><img src="components/com_joomsport/img/down.gif"  border="0" alt="<?php echo JText::_('BLBE_MOVEDOWN');?>"></a>';
					} else { tbl_elem.rows[i].cells[1].innerHTML = ''; }

				}
			}
		}
		
		

		
		function Up_tbl_row(element) {
			if (element.parentNode.parentNode.sectionRowIndex > 1) {
				var sec_indx = element.parentNode.parentNode.sectionRowIndex;
				var table = element.parentNode.parentNode.parentNode;
				var tbl_id = table.parentNode.id;
				
				var row = table.insertRow(sec_indx - 1);
				
				var cell5 = document.createElement("td");
				var cell6 = document.createElement("td");
				row.appendChild(cell5);
				row.appendChild(cell6);
				
				row.appendChild(element.parentNode.parentNode.cells[2]);
				row.appendChild(element.parentNode.parentNode.cells[2]);

				element.parentNode.parentNode.parentNode.deleteRow(element.parentNode.parentNode.sectionRowIndex);
				
				ReAnalize_tbl_Rows('id_column_seas');
			}
		}

		function Down_tbl_row(element) {
			if (element.parentNode.parentNode.sectionRowIndex < element.parentNode.parentNode.parentNode.rows.length - 1) {
				var sec_indx = element.parentNode.parentNode.sectionRowIndex;
				var table = element.parentNode.parentNode.parentNode;
				var tbl_id = table.parentNode.id;
				
				var row = table.insertRow(sec_indx + 2);
				
				var cell5 = document.createElement("td");
				var cell6 = document.createElement("td");
				row.appendChild(cell5);
				row.appendChild(cell6);

				row.appendChild(element.parentNode.parentNode.cells[2]);
				row.appendChild(element.parentNode.parentNode.cells[2]);

				element.parentNode.parentNode.parentNode.deleteRow(element.parentNode.parentNode.sectionRowIndex);
				
				ReAnalize_tbl_Rows('id_column_seas');
			}	
		}

        function date_publ(){
            //alert(elem.checked);
            if(document.adminForm.s_reg1.checked){
                document.getElementById('reg_start_img').removeAttribute('disabled');
                document.getElementById('reg_end_img').removeAttribute('disabled');
                document.getElementById('reg_start').removeAttribute('disabled');
                document.getElementById('reg_end').removeAttribute('disabled');

            }else{
                document.getElementById('reg_start_img').setAttribute('disabled','');
                document.getElementById('reg_end_img').setAttribute('disabled','');
                document.getElementById('reg_start').setAttribute('disabled','');
                document.getElementById('reg_end').setAttribute('disabled','');
            }
        }
        
       
        function showPointsAdditionalOptions(val){
            switch(val){
                
                case '1':
                    jQuery("#js_div_ptsbyresult").hide();
                    jQuery("#js_div_ptsbyrank").show();
                    break;
                case '2':
                    jQuery("#js_div_ptsbyresult").show();
                    jQuery("#js_div_ptsbyrank").hide();
                    break;
                default:
                    jQuery("#js_div_ptsbyresult").hide();
                    jQuery("#js_div_ptsbyrank").hide();
                    
            }    
        }    
        
        jQuery( document ).ready(function() {
            jQuery("#main_conf_div").on("change",".cls_race_points", function(){
                showPointsAdditionalOptions(this.value);
            });
        });
        
        
        function addRankPts(){
            var rank_add_js = jQuery("#rank_add_js").val();
            var points_add_js = jQuery("#points_add_js").val();
            
            if(rank_add_js && points_add_js){
                var tbl = jQuery("#js_div_ptsbyrank  table  tbody");
                var tr = document.createElement("tr");
                var html = '<td><input type="hidden" name="roundopt[rnkpts_rank][]" value="'+rank_add_js+'" />'+rank_add_js+'</td>'
                    +'<td><input type="hidden" name="roundopt[rnkpts_pts][]" value="'+points_add_js+'" />'+points_add_js+'</td>'
            +'<td><a href="javascript:void(0);" onclick="javascript:(this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode));">'
                    +'<span class="icon-cancel"></span>'
                    +'</a></td>';
                tr.innerHTML = html;
                tbl.append(tr);
            }    
            
        }    
        
        function addResultPts(){
            var fromresult_add_js = jQuery("#fromresult_add_js").val();
            var toresult_add_js = jQuery("#toresult_add_js").val();
            var points_result_add_js = jQuery("#points_result_add_js").val();
            
            if(fromresult_add_js && toresult_add_js && points_result_add_js){
                var tbl = jQuery("#js_div_ptsbyresult  table  tbody");
                var tr = document.createElement("tr");
                var html = '<td><input type="hidden" name="roundopt[rnkpts_fromres][]" value="'+fromresult_add_js+'" />'+fromresult_add_js+'</td>'
                    +'<td><input type="hidden" name="roundopt[rnkpts_tores][]" value="'+toresult_add_js+'" />'+toresult_add_js+'</td>'
                    +'<td><input type="hidden" name="roundopt[rnkpts_results][]" value="'+points_result_add_js+'" />'+points_result_add_js+'</td>'
                    +'<td><a href="javascript:void(0);" onclick="javascript:(this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode));">'
                    +'<span class="icon-cancel"></span>'
                    +'</a></td>';
                tr.innerHTML = html;
                tbl.append(tr);
            }  
        }
        
        function groupClose(){
            jQuery.ajax({
                url: "index.php?option=com_joomsport&task=group_list&tmpl=component&no_html=1&s_id=<?php echo $this->row->s_id?>",
                type: 'POST'
            }).done(function(res) {
                if(res){
                    jQuery('.showjsthead').css('display','table-header-group');
                }else{
                    jQuery('.showjsthead').css('display','none');
                }    
              jQuery("#jsGroupList").html(res);
                jQuery(function(jQuery) {
			SqueezeBox.initialize({});
			SqueezeBox.assign(jQuery('#jsGroupList a.modal').get(), {
				parse: 'rel'
			});
		});
            });
            
        }
        jQuery( document ).ready(function() {
            jQuery("#jsGroupList").sortable(
                    {stop: function( event, ui ) {
                            var cids = jQuery('input[name^="groupId"]');
                            cids = cids.serializeArray();
                            
                            jQuery.ajax({
                                url: "index.php?option=com_joomsport&task=group_ordering&tmpl=component&no_html=1&s_id=<?php echo $this->row->s_id?>",
                                type: 'POST',
                                data : cids
                            });
                            
                        }
                    }
            );
            
            jQuery( "#jsGroupList" ).disableSelection();
            jQuery("#id_column_seas").sortable(
                    
            );
            
            
            
        });
        function jsGroupDel(){
            if (document.adminForm.boxchecked.value==0){
                alert('<?php echo addslashes(JText::_('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST'))?>');
                return false;
            }else{ 
                var cids = jQuery('input[name^="cid"]');
                            cids = cids.serializeArray();
                jQuery.ajax({
                    url: "index.php?option=com_joomsport&task=group_del&tmpl=component&no_html=1&s_id=<?php echo $this->row->s_id?>",
                    type: 'POST',
                    data : cids
                }).done(function(res) {
                    groupClose();
                });
                
                return false;
            }
        }
        function chngPlayerList(){
                    var players = jQuery( "[name^='maps_s']" ).serializeArray();
                    jQuery.ajax({
                        url: "index.php?option=com_joomsport&task=getMapsList&tmpl=component&no_html=1",
                        type: 'POST',
                        data: {players:players},

                      }).done(function(res) {
                        jQuery("#maps_id").html(res);
                        jQuery("#maps_id").trigger("liszt:updated");
                        
                      });
    
                }
        //-->
        </script>
<?php
JHTML::_('behavior.modal', 'a.modal');
?>       
<form action="index.php?option=com_joomsport" method="post" name="adminForm" id="adminForm">
<div class="jsrespdiv12">
    <div class="jsBepanel">
        <div class="jsBEheader">
            <?php echo JText::_('BLBE_HEAD_EDITSEASON'); ?>
        </div>
        <div class="jsBEsettings" style="padding:0px;">
    <!-- <tab box> -->
            <ul class="tab-box">
                    <?php
                    echo $etabs->newTab(JText::_('BLBE_MAIN'), 'main_conf', '', 'vis');

                    //if($this->lists['t_type'] == 0){
                    echo $etabs->newTab(JText::_('BLBE_TTCOLOR'), 'col_conf', '');
                    echo $etabs->newTab(JText::_('BLBE_JOOMSOPT'), 'esport_conf', '');
                    //}
                    if (count($lists['teams_regs'])) {
                        echo $etabs->newTab(JText::_('BLBE_PARTREGFROMFE'), 'partr_conf', '');
                    }
                    if ($lists['is_betting']) {
                        echo $etabs->newTab(JText::_('BLBE_BET_OPTIONS'), 'bet_option', '');
                    }
                    if ($this->lists['tournament_type'] != 1) {
                        echo $etabs->newTab(JText::_('BLBE_MENGROUPS'), 'groups', '');
                    }
                    ?>
            </ul>	
            <div style="clear:both"></div>
        </div>
    </div>    
</div>    
<div id="main_conf_div" class="tabdiv">
    <div class="jsrespdiv8">
        <div class="jsBepanel">
            <div class="jsBEheader">
                <?php echo JText::_('BLBE_GENERAL'); ?>
            </div>
            <div class="jsBEsettings">
                <table class="jsTableEqual">
                        <tr>
                                <td width="150">
                                        <?php echo JText::_('BLBE_SEASONNAME'); ?>
                                        <?php
                                        if(count($lists['languages'])){
                                        
                                            echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                        }?>
                                </td>
                                <td>
                                        <input type="text" maxlength="255" size="60" name="s_name" id="easname" value="<?php echo htmlspecialchars($this->row->s_name)?>" />
                                        <?php
                                    if(count($lists['languages'])){
                                        
                                        echo '<div class="jsTranslationContainer">';
                                        foreach (($lists['languages']) as $value) {
                                            echo '<div class="jsTranslationDiv">';
                                            $translation = '';
                                            
                                            if(isset($lists['translation']) && isset($lists['translation'][$value]['s_name'])){
                                                $translation = htmlspecialchars($lists['translation'][$value]['s_name'], ENT_QUOTES);
                                            }
                                            echo '<input type="text" maxlength="255" size="60" name="translation['.$value.'][s_name]" value="'.addslashes($translation).'" />';
                                            echo '  ' . $value;
                                            echo '</div>';
                                        }
                                        echo '</div>';
                                    }
                                    ?>
                                </td>
                        </tr>
                        
                        <?php if ($this->lists['tournament_type'] == 0) {
    ?>
                        
                        <tr>
                                <td width="150">
                                        
                                </td>
                                <td>
                                    <table class="tblforpoints">
                                        <tr>
                                            <td>
                                                <?php echo JText::_('BLBE_HOME');
    ?>
                                            </td>
                                            <td>
                                                <?php echo JText::_('BLBE_AWAY');
    ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                        </tr>
                        <tr>
                            <td width="150">
                                    <?php echo JText::_('BLBE_WPH');
    ?>
                                                                    </td>
                            <td>
                                <table class="tblforpoints">
                                    <tr>
                                        <td>
                                            <input type="text" maxlength="5" size="10" style="width:50px;" name="s_win_point" value="<?php echo floatval($this->row->s_win_point)?>" onblur="extractNumber(this,2,true);" onkeyup="extractNumber(this,2,true);" onkeypress="return blockNonNumbers(this, event, true, true);" />
                                        </td>
                                        <td>
                                            <input type="text" maxlength="5" size="10" style="width:50px;" name="s_win_away" value="<?php echo floatval($this->row->s_win_away)?>" onblur="extractNumber(this,2,true);" onkeyup="extractNumber(this,2,true);" onkeypress="return blockNonNumbers(this, event, true, true);" />
                                        </td>
                                    </tr>
                                </table>

                            </td>
                        </tr>
                        <tr>
                            <td width="150">
                                    <?php echo JText::_('BLBE_DPH');
    ?>
                                                                    </td>
                            <td>
                                <table class="tblforpoints">
                                    <tr>
                                        <td>
                                            <input type="text" maxlength="5" size="10" style="width:50px;" name="s_draw_point" value="<?php echo floatval($this->row->s_draw_point)?>" onblur="extractNumber(this,2,true);" onkeyup="extractNumber(this,2,true);" onkeypress="return blockNonNumbers(this, event, true, true);" />
                                        </td>
                                        <td>
                                            <input type="text" maxlength="5" size="10" style="width:50px;" name="s_draw_away" value="<?php echo floatval($this->row->s_draw_away)?>" onblur="extractNumber(this,2,true);" onkeyup="extractNumber(this,2,true);" onkeypress="return blockNonNumbers(this, event, true, true);" />
                                        </td>
                                    </tr>
                                </table>

                            </td>
                        </tr>
                        <tr>
                            <td width="150">
                                    <?php echo JText::_('BLBE_LPH');
    ?>
                            </td>
                            <td>
                                <table class="tblforpoints">
                                    <tr>
                                        <td>
                                            <input type="text" maxlength="5" size="10" style="width:50px;" name="s_lost_point" value="<?php echo floatval($this->row->s_lost_point)?>" onblur="extractNumber(this,2,true);" onkeyup="extractNumber(this,2,true);" onkeypress="return blockNonNumbers(this, event, true, true);" />
                                        </td>
                                        <td>
                                            <input type="text" maxlength="5" size="10" style="width:50px;" name="s_lost_away" value="<?php echo floatval($this->row->s_lost_away)?>" onblur="extractNumber(this,2,true);" onkeyup="extractNumber(this,2,true);" onkeypress="return blockNonNumbers(this, event, true, true);" />
                                        </td>
                                    </tr>
                                </table>

                            </td>
                        </tr>
                        
                        
                        
                        <tr>
                                <td width="150">
                                        <?php echo JText::_('BLBE_EXTIME');
    ?>
                                                                        </td>
                                <td>
                                    <div class="controls"><fieldset class="radio btn-group"><?php echo $this->lists['s_enbl_extra'];
    ?></fieldset></div>

                                </td>
                        </tr>
                        <tr>
                                <td colspan="2">
                                    <table class="jsTableEqual" cellpadding="1" cellspacing="0" id="extraoptions" <?php if (!$this->row->s_enbl_extra) {
    echo "style='display:none'";
}
    ?>>
                                                <tr>
                                                        <td width="150">
                                                                <div style="width:150px;">
                                    <?php echo JText::_('BLBE_WPEXTIME');
    ?>
                                                                                                                            </div>
                            </td>
                                                        <td>
                                                                <input type="text" maxlength="5" style="width:50px;" size="10" name="s_extra_win" value="<?php echo floatval($this->row->s_extra_win)?>" onblur="extractNumber(this,2,true);" onkeyup="extractNumber(this,2,true);" onkeypress="return blockNonNumbers(this, event, true, true);" />
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="150">
                                                                <?php echo JText::_('BLBE_LPEXTIME');
    ?>
                                                                                                                        </td>
                                                        <td>
                                                                <input type="text" maxlength="5" style="width:50px;" size="10" name="s_extra_lost" value="<?php echo floatval($this->row->s_extra_lost)?>" onblur="extractNumber(this,2,true);" onkeyup="extractNumber(this,2,true);" onkeypress="return blockNonNumbers(this, event, true, true);" />
                                                        </td>
                                                </tr>
                                        </table>		
                                </td>		
                        </tr>

                        <?php 
} ?>
                        
                        
                </table>

                <table  border="0">
                        <tr>
                                <td width="150">
                                        <?php echo $this->lists['tourntype'] ? JText::_('BLBE_ADDPARTIC') : JText::_('BLBE_ADDTEAMS'); ?>
                                                                        </td>
                                <td width="150">
                                    <div class="selectedlistdescr"><?php echo JText::_('BLBE_AVAILABLE')?></div>
                                    <?php echo $this->lists['teams'];?>
                                </td>
                                <td valign="middle" width="60" align="center">
                                    
                                        <input class="btn" type="button" style="cursor:pointer;" value=">>" onClick="javascript:JS_addSelectedToList('adminForm','teams_id','teams_season');" /><br />
                                        <input class="btn" type="button" style="cursor:pointer;" value="<<" onClick="javascript:JS_delSelectedFromList('adminForm','teams_season','teams_id');" />
                                </td>
                                <td >
                                    <div class="selectedlistdescr"><?php echo JText::_('BLBE_SELECTED')?></div>
                                    <?php echo $this->lists['teams2'];?>
                                </td>
                        </tr>
                </table>
                <br />
                <table  border="0">
                        <tr>
                                <td width="150">
                                        <?php echo JText::_('BLBE_ADD_MOD'); ?>
                                        <span class="editlinktip hasTip" title="<?php echo JText::_('BLBE_ADD_MOD'); ?>::<?php echo JText::_('BLBE_TT_ADD_MOD');?>"><img class="imgQuest" src="components/com_joomsport/img/quest.png" bOrder="0" /></span>

                                </td>
                                <td width="150">
                                    <div class="selectedlistdescr"><?php echo JText::_('BLBE_AVAILABLE')?></div>
                                        <?php echo $this->lists['usrlist'];?>
                                </td>
                                <td valign="middle" width="60" align="center">
                                        <input class="btn" type="button" style="cursor:pointer;" value=">>" onClick="javascript:JS_addSelectedToList('adminForm','usracc_id','usr_admins');" /><br />
                                        <input class="btn" type="button" style="cursor:pointer;" value="<<" onClick="javascript:JS_delSelectedFromList('adminForm','usr_admins','usracc_id');" />
                                </td>
                                <td >
                                    <div class="selectedlistdescr"><?php echo JText::_('BLBE_SELECTED')?></div>
                                        <?php echo $this->lists['usrlist_vyb'];?>
                                </td>
                        </tr>
                </table>
                <br />
                <table>
                    <tr>
                            <td width="150">
                                    <?php echo JText::_('BLBE_RULES'); ?>
                                        <?php
                                        if(count($lists['languages'])){
                                        
                                            echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                        }?>
                            </td>
                            <td>
                                    <?php echo $editor->display('s_rules',  htmlspecialchars($this->row->s_rules, ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore'));  ?>
                                    <?php
                                    if(count($lists['languages'])){
                                        echo '<div class="jsTranslationContainer">';
                                        foreach (($lists['languages']) as $value) {
                                            echo '<div class="jsTranslationDiv">';
                                            $translation = '';
                                            echo $value;
                                            if(isset($lists['translation']) && isset($lists['translation'][$value]['s_rules'])){
                                                $translation = $lists['translation'][$value]['s_rules'];
                                            }
                                            echo $editor->display('translation['.$value.'][s_rules]',  htmlspecialchars($translation, ENT_QUOTES), '100%', '200', '6', '2', array('pagebreak', 'readmore'));
                                            
                                            echo '</div>';
                                        }
                                        echo '</div>';
                                    }
                                    ?>
                            </td>
                    </tr>


                    <tr>
                            <td width="150">
                                    <?php echo JText::_('BLBE_ABOUT_SEASON'); ?>
                                <?php
                                if(count($lists['languages'])){

                                    echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                }?>
                            </td>
                            <td>
                                    <?php echo $editor->display('s_descr',  htmlspecialchars($this->row->s_descr, ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore'));  ?>
                                    <?php
                                    if(count($lists['languages'])){
                                        echo '<div class="jsTranslationContainer">';
                                        foreach (($lists['languages']) as $value) {
                                            echo '<div class="jsTranslationDiv">';
                                            $translation = '';
                                            echo $value;
                                            if(isset($lists['translation']) && isset($lists['translation'][$value]['s_descr'])){
                                                $translation = $lists['translation'][$value]['s_descr'];
                                            }
                                            echo $editor->display('translation['.$value.'][s_descr]',  htmlspecialchars($translation, ENT_QUOTES), '100%', '200', '6', '2', array('pagebreak', 'readmore'));
                                            
                                            echo '</div>';
                                        }
                                        echo '</div>';
                                    }
                                    ?>
                            </td>
                    </tr>
                </table>

                <?php 
                // version 3.3 race type
                if ($this->lists['tournament_type'] == 1) {
                    ?>
                    <table  class='jsTableEqual'>

                        <tr>
                            <td width="150">
                                    <?php echo JText::_('BLBE_RACE_RESULT_ORDERING');
                    ?>
                                 <span class="editlinktip hasTip" title="<?php echo JText::_('BLBE_RACE_RESULT_ORDERING');
                    ?>::<?php echo JText::_('BLBE_RACE_RESULT_ORDERING_DESC');
                    ?>"><img class="imgQuest" src="components/com_joomsport/img/quest.png" bOrder="0" /></span>

                            </td>
                            <td>
                                    <?php echo $this->lists['ordering_type'];
                    ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="150">
                                    <?php echo JText::_('BLBE_RACE_ADDIT_RESULT_ORDERING');
                    ?>
                                 <span class="editlinktip hasTip" title="<?php echo JText::_('BLBE_RACE_ADDIT_RESULT_ORDERING');
                    ?>::<?php echo JText::_('BLBE_RACE_ADDIT_RESULT_ORDERING_DESC');
                    ?>"><img class="imgQuest" src="components/com_joomsport/img/quest.png" bOrder="0" /></span>

                            </td>
                            <td>
                                    <?php echo $this->lists['ordering_type_advance'];
                    ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="150" style="padding-top:10px;">
                                    <?php echo JText::_('BLBE_ADD_POINTS');
                    ?>
                                <span class="editlinktip hasTip" title="<?php echo JText::_('BLBE_ADD_POINTS');
                    ?>::<?php echo JText::_('BLBE_ADD_POINTS_DESC');
                    ?>"><img class="imgQuest" src="components/com_joomsport/img/quest.png" bOrder="0" /></span>
                            </td>
                            <td style="padding-top:10px;">
                                    <?php echo $this->lists['race_points'];
                    ?>
                                <div>
                                    <div id="js_div_ptsbyrank">
                                        <table>
                                            <thead>
                                                <tr>

                                                    <th>
                                                        <?php echo JText::_('BLBE_PLACE');
                    ?>
                                                    </th>
                                                    <th>
                                                        <?php echo JText::_('BLBE_LANGRANK_POINT');
                    ?>
                                                    </th>
                                                    <th>

                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (isset($this->lists['season_options']->rnkpts_rank)) {
                                                    for ($i = 0; $i < count($this->lists['season_options']->rnkpts_rank); ++$i) {
                                                        if ($this->lists['season_options']->rnkpts_rank[$i] && isset($this->lists['season_options']->rnkpts_pts[$i])) {
                                                            ?>
                                                            <tr>

                                                                <td>
                                                                    <input type="hidden" name="roundopt[rnkpts_rank][]" value="<?php echo $this->lists['season_options']->rnkpts_rank[$i];
                                                            ?>" />
                                                                    <?php echo $this->lists['season_options']->rnkpts_rank[$i];
                                                            ?>
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" name="roundopt[rnkpts_pts][]" value="<?php echo $this->lists['season_options']->rnkpts_pts[$i];
                                                            ?>" />
                                                                    <?php echo $this->lists['season_options']->rnkpts_pts[$i];
                                                            ?>
                                                                </td>
                                                                <td>
                                                                    <a href="javascript:void(0);" onclick="javascript:(this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode));">
                                                                        <span class="icon-cancel"></span>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <?php

                                                        }
                                                    }
                                                }
                    ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td style="padding:0px 7px;">
                                                        <input type="text" style="width:50px;" value="" name="rank_add_js" id="rank_add_js" />
                                                    </td>
                                                    <td style="padding:0px 7px;">
                                                        <input type="text" style="width:50px;" value="" name="points_add_js" id="points_add_js" />
                                                    </td>
                                                    <td style="vertical-align:top;padding:0px 7px;">
                                                        <input class="btn" type="button" value="<?php echo JText::_('BLBE_ADD');
                    ?>" onclick="javascript:addRankPts();" />
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>

                                    </div>
                                    <div  id="js_div_ptsbyresult">
                                        <table>
                                            <thead>
                                                <tr>

                                                    <th>
                                                        <?php echo JText::_('BLBE_ROUND_RESULT_FROM');
                    ?>
                                                    </th>
                                                    <th>
                                                        <?php echo JText::_('BLBE_ROUND_RESULT_TO');
                    ?>
                                                    </th>
                                                    <th>
                                                        <?php echo JText::_('BLBE_PLACE');
                    ?>
                                                    </th>
                                                    <th>

                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (isset($this->lists['season_options']->rnkpts_fromres)) {
                                                    for ($i = 0; $i < count($this->lists['season_options']->rnkpts_fromres); ++$i) {
                                                        if ($this->lists['season_options']->rnkpts_fromres[$i] && isset($this->lists['season_options']->rnkpts_tores[$i]) && isset($this->lists['season_options']->rnkpts_results[$i])) {
                                                            ?>
                                                            <tr>

                                                                <td>
                                                                    <input type="hidden" name="roundopt[rnkpts_fromres][]" value="<?php echo $this->lists['season_options']->rnkpts_fromres[$i];
                                                            ?>" />
                                                                    <?php echo $this->lists['season_options']->rnkpts_fromres[$i];
                                                            ?>
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" name="roundopt[rnkpts_tores][]" value="<?php echo $this->lists['season_options']->rnkpts_tores[$i];
                                                            ?>" />
                                                                    <?php echo $this->lists['season_options']->rnkpts_tores[$i];
                                                            ?>
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" name="roundopt[rnkpts_results][]" value="<?php echo $this->lists['season_options']->rnkpts_results[$i];
                                                            ?>" />
                                                                    <?php echo $this->lists['season_options']->rnkpts_results[$i];
                                                            ?>
                                                                </td>
                                                                <td>
                                                                    <a href="javascript:void(0);" onclick="javascript:(this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode));">
                                                                        <span class="icon-cancel"></span>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <?php

                                                        }
                                                    }
                                                }
                    ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td style="padding:0px 7px;">
                                                        <input type="text" style="width:50px;" value="" name="fromresult_add_js" id="fromresult_add_js" />
                                                    </td>
                                                    <td style="padding:0px 7px;">
                                                        <input type="text" style="width:50px;" value="" name="toresult_add_js" id="toresult_add_js" />
                                                    </td>
                                                    <td style="padding:0px 7px;">
                                                        <input type="text" style="width:50px;" value="" name="points_result_add_js"  id="points_result_add_js" />
                                                    </td>
                                                    <td style="vertical-align:top;padding:0px 7px;">
                                                        <input class="btn" type="button" value="<?php echo JText::_('BLBE_ADD');
                    ?>" onclick="javascript:addResultPts();" />
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>


                                    </div>
                                    <script>
                                        showPointsAdditionalOptions('<?php echo isset($this->lists['season_options']->race_points) ? $this->lists['season_options']->race_points : 0?>');
                                    </script>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="150">
                                    <?php echo JText::_('BLBE_RACE_ATTEMPTS');
                    ?>
                            </td>
                            <td>
                                <input type="text" maxlength="5" size="10" style="width:50px;" name="roundopt[attempts]" value="<?php echo isset($this->lists['season_options']->attempts) ? $this->lists['season_options']->attempts : 1;
                    ?>" onblur="extractNumber(this,2,true);" onkeyup="extractNumber(this,2,true);" onkeypress="return blockNonNumbers(this, event, true, true);" />
                            </td>
                        </tr>
                        <tr>
                            <td width="150">
                                    <?php echo JText::_('BLBE_ENABLE_PENALTY');
                    ?>
                            </td>
                            <td>

                                <?php echo $this->lists['penalty'];
                    ?>

                            </td>
                        </tr>
                        <tr>
                            <td width="150">
                                    <?php echo JText::_('BLBE_POSFIX');
                    ?>
                                <span class="editlinktip hasTip" title="<?php echo JText::_('BLBE_POSFIX');
                    ?>::<?php echo JText::_('BLBE_POSFIX_DESC');
                    ?>"><img class="imgQuest" src="components/com_joomsport/img/quest.png" bOrder="0" /></span>
                            </td>
                            <td>
                                    <input type="text" maxlength="5" size="10" style="width:50px;" name="roundopt[postfix]" value="<?php echo isset($this->lists['season_options']->postfix) ? $this->lists['season_options']->postfix : '';
                    ?>"  />
                            </td>
                        </tr>
                    </table>        
                <?php 
                } ?>
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
                                <td width="150">
                                        <?php echo JText::_('BLBE_TOURNAMENT'); ?>
                                </td>
                                <td>
                                        <b><?php echo $this->lists['tourn'];?></b>
                                </td>
                        </tr>
                        <tr>
                                <td width="150">
                                        <?php echo JText::_('JSTATUS'); ?>
                                </td>
                                <td>
                                    <div class="controls"><fieldset class="radio btn-group"><?php echo $this->lists['published'];?></fieldset></div>
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
                                <td width="150">
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
        
            <?php

            if ($this->lists['tournament_type'] != 1) {
                ?>
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_MAPS');
                ?>
                </div>
                <div class="jsBEsettings">
                    <?php 
                    if ($this->lists['maps_available']) {
                        ?>
                    <table class="jsnoborders jsmapscont"  border="0">
                        
                        <tbody id="map_tbl">
                            <?php
                            for ($i = 0;$i < count($this->lists['cur_maps']);++$i) {
                                ?>
                                    <tr>
                                            <td width="50"><a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this);chngPlayerList(); return false;" title="<?php echo JText::_('BLBE_DELETE');
                                ?>"><img src="components/com_joomsport/img/publish_x.png"  bOrder="0" alt="Delete"></a></td>
                                            <td><?php echo $this->lists['cur_maps'][$i]->m_name?><input type="hidden" name="maps_s[]" value="<?php echo $this->lists['cur_maps'][$i]->id?>" /></td>
                                    </tr>
                                    <?php

                            }
                        ?>
                        </tbody>   
                        <tfoot>
                            <tr>	
                                    <td colspan="2">
                                            <?php echo $this->lists['maps'];
                        ?>
                                            <input class="btn btn-small" type="button" style="cursor:pointer;" value="<?php echo JText::_('BLBE_ADD');
                        ?>" onclick="bl_add_map();" />
                                    </td>

                            </tr>
                        </tfoot>
                    </table>
                    <?php 
                    } else {
                        echo JText::_('BLBE_GAMESTAGES_NOTIF');
                    }
                ?>
                </div>
            </div>  
        <?php 
            } ?>
        </div> 
        <div style="clear:both"></div>
    </div>
    <?php //if($this->lists['t_type'] == 0){?>
    <div id="col_conf_div" class="tabdiv" style="display:none;">
        <div class="jsrespdiv6">
            <?php if ($this->lists['tournament_type'] != 1) {
    ?>
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_TOURN_TABLE');
    ?>
                </div>
                <div class="jsBEsettings">
                    
                    <?php if ($this->lists['tournament_type'] == 0) {
    ?>
                    <table >
                        <thead>
                            
                            <tr>
                                    <td ></td>
                                    <td><?php echo $this->lists['t_single'] ? JText::_('BLBE_PARTICS_EMBLEM') : JText::_('BLBE_TEAMEMBL');
    ?></td>
                                    <td align="right">
                                        <div class="controls">
                                            <fieldset class="radio btn-group">
                                                <?php echo JHTML::_('select.booleanlist',  'emblem_chk', 'class="inputbox" ', $this->lists['emblem_chk']);
    ?>
                                            </fieldset>
                                        </div>

                                    </td>
                            </tr>
                        </thead> 
                        <tbody id="id_column_seas">
                            <?php
                // print_r($this->lists["soptions"]);
                            $curcol = 0;
                            //print_r($this->lists["soptions"]);
                                    if (count($this->lists['soptions'])) {
                                        foreach ($this->lists['soptions'] as $key => $value) {
                                            if ($key) {
                                                ?>
                                                    <tr class="ui-state-default">
                                                            <td width="30">
                                                                <span class="sortable-handler" style="cursor: move;">
                                                                    <span class="icon-menu"></span>
                                                                </span>
                                                            </td>
                                                            <td style="padding-right:20px;"><?php echo $this->lists['available_options'][$key]?></td>
                                                            <td align="right">
                                                                <div class="controls">
                                                                    <fieldset class="radio btn-group">
                                                                        <?php echo JHTML::_('select.booleanlist',  $key.'_name', 'class="inputbox" ', $value);
                                                ?>
                                                                    </fieldset>
                                                                </div>
                                                                <input type="hidden" name="opt_columns[]" value="<?php echo $key?>" />
                                                            </td>	
                                                    </tr>
                                                    <?php
                                                    ++$curcol;
                                            }
                                        }
                                    }

    if (count($this->lists['soptions_notin'])) {
        foreach ($this->lists['soptions_notin'] as $key => $value) {
            ?>
                                                    <tr class="ui-state-default">
                                                            <td width="30">
                                                                <span class="sortable-handler" style="cursor: move;">
                                                                    <span class="icon-menu"></span>
                                                                </span>
                                                            </td>
                                                            <td style="padding-right:20px;"><?php echo $this->lists['available_options'][$key]?></td>
                                                            <td align="right">
                                                                <div class="controls">
                                                                    <fieldset class="radio btn-group">
                                                                        <?php echo JHTML::_('select.booleanlist',  $key.'_name', 'class="inputbox" ', $value);
            ?>
                                                                    </fieldset>
                                                                </div>
                                                                <input type="hidden" name="opt_columns[]" value="<?php echo $key?>" />
                                                            </td>	
                                                    </tr>
                                                    <?php
                                                    ++$curcol;
        }
    }
    ?>

                        </tbody>
                    </table>
                    
                <?php 
}
    ?>
                </div>
            </div>
            <?php 
} ?>
        </div>
        <div class="jsrespdiv6 jsrespmarginleft2">
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_HIGHLIGHT'); ?>
                </div>
                <div class="jsBEsettings">
                   
                    <table>
                            <tr>
                                    <td>
                                            <div id="colorpicker201" class="colorpicker201"></div>
                                    </td>
                            </tr>
                            <tr>
                                    <td id="app_newcol">
                                            <?php if (!count($this->lists['colors'])) {
    ?>
                                            <div class="jscolordivcont">
                                                    
                                                <input class="btn btn-small" type="button" style="cursor:pointer;" onclick="showColorGrid2('input_field_1','sample_1');" value="...">&nbsp;<input type="text" ID="input_field_1" class="jscolorinp" name="input_field_1" size="9" value=""><input type="text" ID="sample_1" size="1" value="" class="color-kind"/>
                                                    <?php echo JText::_('BLBE_PLACE');
    ?>
                                                    <input type="text" ID="place_1" name="place_1" style="width:30px;" size="5" value="" onblur="extractNumber2(this,0,false);" onkeyup="extractNumber2(this,0,false);" onkeypress="return blockNonNumbers2(this, event, true, false);" />
                                                     <span class="editlinktip hasTip" title="<?php echo JText::_('BLBE_PLACE');
    ?>::<?php echo JText::_('BLBE_TT_PLACES');
    ?>"><img class="imgQuest" src="components/com_joomsport/img/quest.png" border="0" /></span>
                                                <?php echo JText::_('BLBE_PLACE_LEGEND');?>
                                                <span class="editlinktip hasTip" title="<?php echo JText::_('BLBE_PLACE_LEGEND');
    ?>::<?php echo JText::_('BLBE_TT_PLACE_LEGEND');
    ?>"><img class="imgQuest" src="components/com_joomsport/img/quest.png" border="0" /></span>
                                                <input type="text" ID="legend_1" style="width:100px;" name="legend_1"  size="5" value="" />
                                                            
                                            </div>
                                            <?php

} else {
    $m = 0;
    foreach ($this->lists['colors'] as $colores) {
        ++$m;
        ?>
                                                    <div class="jscolordivcont">
                                                            <input class="btn btn-small" type="button" style="cursor:pointer;" onclick="showColorGrid2('input_field_<?php echo $m?>','sample_<?php echo $m?>');" value="...">&nbsp;<input type="text" class="jscolorinp" ID="input_field_<?php echo $m?>" name="input_field_<?php echo $m?>" size="9" value="<?php echo $colores->color?>"><input type="text" ID="sample_<?php echo $m?>" size="1" value="" style="width:30px;background-color:<?php echo $colores->color?>" />
                                                            <?php echo JText::_('BLBE_PLACE');
        ?> 


                                                            <span class="editlinktip hasTip" title="<?php echo JText::_('BLBE_PLACE');
        ?>::<?php echo JText::_('BLBE_TT_PLACES');
        ?>"><img class="imgQuest" src="components/com_joomsport/img/quest.png" bOrder="0" /></span>
                                                            <input type="text" ID="place_<?php echo $m?>" style="width:30px;" name="place_<?php echo $m?>"  size="5" value="<?php echo $colores->place?>"  onblur="extractNumber2(this,0,false);" onkeyup="extractNumber2(this,0,false);" onkeypress="return blockNonNumbers2(this, event, true, false);" />
                                                            <?php echo JText::_('BLBE_PLACE_LEGEND');?>
                                                <span class="editlinktip hasTip" title="<?php echo JText::_('BLBE_PLACE_LEGEND');
    ?>::<?php echo JText::_('BLBE_TT_PLACE_LEGEND');
    ?>"><img class="imgQuest" src="components/com_joomsport/img/quest.png" border="0" /></span>
                                                            <input type="text" ID="legend_<?php echo $m?>" style="width:100px;" name="legend_<?php echo $m?>"  size="5" value="<?php echo $colores->s_legend?>" />
                                                            
                                                    </div>
                                            <?php	
    }
}
                                            ?>
                                    </td>
                            </tr>
                            <tr>
                                    <td>
                                    <input type="hidden" name="col_count" value="<?php echo count($this->lists['colors']) ? count($this->lists['colors']) : 1?>"  onblur="extractNumber(this,0,false);" onkeyup="extractNumber(this,0,false);" onkeypress="return blockNonNumbers(this, event, true, false);" />
                                    <input class="btn btn-small" type="button" style="cursor:pointer;" value="<?php echo JText::_('BLBE_NEWCOLOR'); ?>" onclick="javascript:add_colors();" />
                                    </td>
                            </tr>
                    </table>
                </div>
            </div>
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_RANK_CRIT'); ?>
                </div>
                <div class="jsBEsettings">
                    <table class="admin">
                            
                            <tr>
                                <td colspan="2">
                                    <?php echo JText::_('BLBE_RANKING_SPAIN');?>
                                    <div class="controls" style="display:inline; margin-left:10px;">
                                        <fieldset class="radio btn-group">
                                            <?php echo JHTML::_('select.booleanlist',  'spainranking_chk', 'class="inputbox" ', $this->lists['spainranking_chk'] ? 1 : 0);?>
                                        </fieldset>
                                    </div>
                                    <br />
                                    <br />
                                    
                                </td>
                            </tr>
                    </table>  
                    <table class="admin" id="divrankingsboxequal">
                            
                            <tr>
                                <td colspan="2">
                                    <?php echo JText::_('BLBE_RANK_EQUAL');?>
                                    <div class="controls" style="display:inline; margin-left:10px;">
                                        <fieldset class="radio btn-group">
                                            <?php echo JHTML::_('select.booleanlist',  'equalpts_chk', 'class="inputbox" ', $this->lists['equalpts_chk'] ? 1 : 0);?>
                                        </fieldset>
                                    </div>
                                    <br />
                                    <br />
                                    
                                </td>
                            </tr>
                    </table>        
                    <table class="admin" id="divrankingsbox" <?php echo $this->lists['equalpts_chk'] ? 'style="display:none"' : ''?>>        
                            <?php
                            if(!$this->row->s_id){
                                $default_criteria = array(1, 4, 5, 7, 0);
                            }else{
                                $default_criteria = array();
                            }
                            for ($i = 0;$i < 5;++$i) {
                                echo '<tr>';
                                echo '<td>'.JHTML::_('select.genericlist',   $this->lists['sortfield'], 'sortfield[]', 'class="inputbox chosen-select"', 'id', 'name', ((isset($this->lists['savedsort'][$i]->sort_field)) ? $this->lists['savedsort'][$i]->sort_field : (isset($default_criteria[$i])?$default_criteria[$i]:0)), 'sortfield'.$i).'</td>';
                                echo '<td>'.JHTML::_('select.genericlist',   $this->lists['sortway'], 'sortway[]', 'class="inputbox chosen-select"', 'id', 'name', ((isset($this->lists['savedsort'][$i]->sort_way)) ? $this->lists['savedsort'][$i]->sort_way : 0), 'sortway'.$i).'</td>';
                                echo '</tr>';
                                
                            }
                            ?>
                    </table>
                    <div id="divcririadescr" <?php echo $this->lists['equalpts_chk'] ? '' : 'style="display:none"'?>>
                        <?php echo JText::_('BLBE_EQPOINT_DESCR'); ?>
                    </div>    
                </div>    
            </div>    
        </div>    
    </div>
    <?php //} ?>
    <div id="esport_conf_div" class="tabdiv" style="display:none;">
        <div class="span12">
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_GENERAL'); ?>
                </div>
                <div class="jsBEsettings">
                    <table class="jsbetable">
                        <tr>
                            <td width="250">
                                    <?php echo JText::_('BLBE_UNMLREG'); ?>
                            </td>
                            <td>
                                <div class="controls"><fieldset class="radio btn-group"><?php echo $this->lists['enbl_reg'];?></fieldset></div>
                            </td>
                        </tr>
                    </table>
                    <table  class="jsbetable" id="partRegDiv" <?php echo $this->lists['enbl_reg_val'] ? '' : "style='display:none;'";?>>
                            <tr>
                                    <td width="250">
                                            <?php echo JText::_('BLBE_LIMIT_NUM_PART'); ?>
                                    </td>
                                    
                                    <td>
                                        <div class="controls">
                                            <fieldset class="radio btn-group">
                                                <?php echo $this->lists['enbl_reg_to'];?>
                                            </fieldset>
                                        
                                            <div class="jsregnumpart dependonilmit" <?php echo $this->row->s_participant ? "style='display: inline-block;'" : "style='display:none;'";?>>

                                                    <?php echo JText::_('BLBE_SETNUMPART'); ?>
                                                    <input type="text" maxlength="6" size="10" name="s_participant" value="<?php echo $this->row->s_participant?>" onblur="extractNumber(this,2,true);" onkeyup="extractNumber(this,2,true);" onkeypress="return blockNonNumbers(this, event, false, false);"  />
                                                   
                                            </div>
                                        </div>
                                    </td>
                            </tr>
                            
                            
                            
                            <tr>
                                    <td width="250">
                                            <?php echo JText::_('BLBE_ENBL_PAYMENTS'); ?>
                                    </td>
                                    <td>
                                        <?php if ($this->lists['is_payments']) {
    ?>
                                            <div class="controls"><fieldset class="radio btn-group"><?php echo $this->lists['enbl_pay'];
    ?></fieldset></div>
                                        <?php 
} else {
    echo JText::sprintf('BLBE_WARN_NOPAYPALADDON', '<a target="_blank" href="http://joomsport.com/web-shop/joomsport-addons.html?utm_source=jsproBE&utm_medium=links&utm_campaign=buyPPaddon">', '</a>');
}
                                        ?>
                                        
                                    </td>
                            </tr>
                            
                            <tr>
                                    <td width="250">
                                            <?php echo JText::_('BLBE_STARTREG'); ?>
                                    </td>
                                    <td>
                                            <?php
                        $arr_regdate = $this->row->s_reg ? array('class' => 'inputbox', 'size' => '25',  'maxlength' => '19') : array('class' => 'inputbox', 'size' => '25',  'maxlength' => '19');
                                            echo JHTML::_('calendar', $this->row->reg_start, 'reg_start', 'reg_start', '%Y-%m-%d %H:00:00', array('class' => 'inputbox', 'size' => '25',  'maxlength' => '19', $arr_regdate));
                                            ?>
                                    </td>
                            </tr>
                            <tr>
                                    <td width="250">
                                            <?php echo JText::_('BLBE_ENDREG'); ?>
                                    </td>
                                    <td>
                                            <?php
                                            echo JHTML::_('calendar', $this->row->reg_end, 'reg_end', 'reg_end', '%Y-%m-%d %H:00:00', array('class' => 'inputbox', 'size' => '25',  'maxlength' => '19', $arr_regdate));

                                            ?>
                                    </td>
                            </tr>
                    </table>
                    
                    <br />
                    
                </div>
            </div>
        </div>    
    </div>
    
    <?php if (count($lists['teams_regs'])) {
    ?>
    <div id="partr_conf_div" class="tabdiv" style="display:none;">
        <div class="span12">
           <div class="jsBepanel">
               <div class="jsBEheader">
                   <?php echo JText::_('BLBE_GENERAL');
    ?>
               </div>
               <div class="jsBEsettings">
                    <table class="table table-striped">
                            <?php foreach ($lists['teams_regs'] as $trg) {
    ?>
                                    <tr>
                                            <td>
                                                    <a href="javascript: void(0);" onClick="javascript:JS_del_REGFE('teams_season','<?php echo $trg->id?>'); Delete_tbl_row(this); return false;" <?php echo JText::_('BLBE_DELETE');
    ?>><img src="components/com_joomsport/img/publish_x.png"  bOrder="0" alt="Delete"></a>
                                            </td>
                                            <td>
                                                    <?php echo $trg->t_name;
    ?>
                                            </td>
                                    </tr>
                            <?php 
}
    ?>
                    </table>
                </div>
            </div>
        </div>    
    </div>
    <?php 
} ?>
    <?php if ($lists['is_betting']) {
    ?>
        <div id="bet_option_div" class="tabdiv">
                    <table>
                    <tr>
                            <td width="150"><?php echo JText::_('BLBE_BET_SELECT_TEMPLATE')?>:</td>
                            <td><?php echo $this->lists['templates']?></td>
                    </tr>
                    </table>
            </div>
    <?php 
} ?>
    <?php 
    if ($this->lists['tournament_type'] != 1) {
        ?>
    <div id="groups_div" class="tabdiv" style="display:none;">
        <div class="span12">
           <div class="jsBepanel">
               <div class="jsBEheader">
                   <?php echo JText::_('BLBE_MENGROUPS');
        ?>
               </div>
               <div class="jsBEsettings">
                   <?php if ($this->row->s_id && count($lists['teams2_season'])) {
    ?>
                    <div class="btn-toolbar" id="toolbar">
                        <div class="btn-wrapper" id="toolbar-new">
                            <a class="modal" title="Select" href="index.php?option=com_joomsport&amp;task=group_add&amp;sid=<?php echo $this->row->s_id?>&amp;tmpl=component" rel="{handler: 'iframe', size: {x: 800, y: 450},onClose:function(){groupClose();}}">
                    
                                <button  class="btn btn-small btn-success">
                                <span class="icon-new icon-white"></span>
                                <?php echo JText::_('BLBE_NEW_GROUP_CREATE');
    ?></button>
                            </a>    
                        </div>
                        
                        <div class="btn-wrapper" id="toolbar-delete">
                            <button onclick="jsGroupDel();return false;" class="btn btn-small">
                                <span class="icon-delete"></span>
                                <?php echo JText::_('JTOOLBAR_DELETE');
    ?></button>
                        </div>
                        
                    </div>
                    
                    <table class="table table-striped" >
                        <thead class="showjsthead" <?php echo count($lists['groups']) ? '' : 'style="display:none;"';
    ?>>
                            <tr>
				<th width="2%" align="center">#</th>
				<th width="2%">
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);">
				</th>
				<th class="title">
						
				</th>
                            </tr>    
                        </thead>
                        <tbody id="jsGroupList">
                            <?php
                            if (count($lists['groups'])) {
                                $i = 0;
                                foreach ($lists['groups'] as $gr) {
                                    ?>
                                    <tr class="ui-state-default">

                                        <td>
                                            <span class="sortable-handler" style="cursor: move;">
                                                    <span class="icon-menu"></span>
                                            </span>
                                            <input type="hidden" name="groupId[]" value="<?php echo $gr->id?>" />
                                        </td>
                                        <td><?php echo @JHTML::_('grid.checkedout',   $gr, $i);
                                    ?></td>
                                        <td>    
                                            <a class="modal" title="Select" href="index.php?option=com_joomsport&amp;task=group_add&amp;sid=<?php echo $this->row->s_id?>&amp;cid[]=<?php echo $gr->id?>&amp;tmpl=component" rel="{handler: 'iframe', size: {x: 800, y: 450},onClose:function(){groupClose();}}">

                                                <?php echo $gr->group_name?>
                                            </a>
                                        </td>    
                                    </tr>
                                    <?php
                                    ++$i;
                                }
                            }
    ?>
                        </tbody>
                    </table>
                    
                    <?php

} else {
    echo JText::_('BLBE_SAVETOADD_GROUPS');
}
        ?>        
                </div>
            </div>
        </div>    
    </div>
    <?php 
    }?>   
    <input type="hidden" name="option" value="com_joomsport" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="s_id" value="<?php echo $this->row->s_id?>" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="jscurtab" id="jscurtab" value="" />
    <?php echo JHTML::_('form.token'); ?>
</form>