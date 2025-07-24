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
$doc = JFactory::getDocument();
$doc->addCustomTag('<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" />');

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
        require_once JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_joomsport'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'tabs.php';
require_once JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_joomsport'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'js-helper-stages.php';
        $etabs = new esTabs();
        ?>
		<script type="text/javascript">
		<!--
		Joomla.submitbutton = function(task) {
			submitbutton(task);
		}
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if(pressbutton == 'matchday_cancel'){
				window.history.back(-1);
			}
			else{
					if( '<?php echo $lists['t_type']?>'==0 && document.adminForm.m_id.value == 0){
						alert("<?php echo JText::_('BLBE_PLSELMD');?>");return false;
					}
					
					var regE = /[0-2][0-9]:[0-5][0-9]/;
					if(!regE.test(document.adminForm.d_time.value) && document.adminForm.d_time.value != ''){
						alert("<?php echo JText::_('BLBE_JSMDNOT7'); ?>");return;
					}else{
                                            jQuery('input[name="jsgallery"]').val(JSON.stringify(jQuery('input[name^="filnm"]').serializeArray()));
			 	
					submitform( pressbutton );
					return;
					}
			}
		}	
		
		function bl_add_event(prefixInt){
            var prefix = prefixInt>0?"_"+prefixInt:"";
			var cur_event = getObj('event_id'+prefix);
			
			//var e_count = getObj('e_count').value;
			var e_minutes = getObj('e_minutes'+prefix).value;
			var e_player = getObj('playerz_id'+prefix);
			var re_count = getObj('re_count'+prefix).value;
			if (cur_event.value == 0) {
				alert("<?php echo JText::_('BLBE_SELEVENT'); ?>");return;
			}
			if (e_player.value == 0) {
				alert("<?php echo JText::_('BLBE_SELPLAYER'); ?>");return;
			}
	
			var tbl_elem = getObj('new_events'+prefix);
			var row = tbl_elem.insertRow(tbl_elem.rows.length);
                        row.className = 'ui-state-default';
                        var cell0 = document.createElement("td");
			var cell1 = document.createElement("td");
			var cell2 = document.createElement("td");
			var cell3 = document.createElement("td");
			var cell4 = document.createElement("td");
			var cell5 = document.createElement("td");
			var cell6 = document.createElement("td");
			var cell7 = document.createElement("td");
			var cell8 = document.createElement("td");///
                        var cell9 = document.createElement("td");
                        
                        cell0.innerHTML = '<span class="sortable-handler" style="cursor: move;"><span class="icon-menu"></span></span>';
			var input_hidden = document.createElement("input");
			input_hidden.type = "hidden";
			input_hidden.name = "em_id[]";
			input_hidden.value = 0;
			cell1.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('BLBE_DELETE');?>"><img src="components/com_joomsport/img/publish_x.png"  bOrder="0" alt="Delete"></a>';
			cell1.appendChild(input_hidden);

            var input_hidden = document.createElement("input");
            input_hidden.type = "hidden";
            input_hidden.name = "stage_id[]";
            input_hidden.value = prefixInt;
            cell1.appendChild(input_hidden);

			var input_hidden = document.createElement("input");
			input_hidden.type = "hidden";
			input_hidden.name = "new_eventid[]";
			input_hidden.value = cur_event.value;
			cell2.innerHTML = cur_event.options[cur_event.selectedIndex].text;
			cell2.appendChild(input_hidden);
			
			
			var input_hidden = document.createElement("input");
			input_hidden.type = "text";
			input_hidden.name = "e_minuteval[]";
			input_hidden.value = e_minutes;
			//cell4.innerHTML = e_minutes;
			input_hidden.setAttribute("maxlength",5);
			input_hidden.setAttribute("size",5);
            input_hidden.style.width = '30px';
			input_hidden.onblur = function(){extractNumberEv(this,2,true);};
			input_hidden.onkeyup = function(){extractNumberEv(this,2,true);};
			//input_hidden.onkeypress = function(){return blockNonNumbers(this, event, true, true);};
			cell4.appendChild(input_hidden);
			
			var input_player = document.createElement("input");
			input_player.type = "hidden";
			input_player.name = "new_player[]";
			input_player.value = e_player.value;
			if(e_player.value != 0){
				cell5.innerHTML = e_player.options[e_player.selectedIndex].text;
			}	
			cell5.appendChild(input_player);
			var input_hidden = document.createElement("input");
			input_hidden.type = "text";
			input_hidden.name = "e_countval[]";
			input_hidden.value = re_count;
			//cell4.innerHTML = e_minutes;
			input_hidden.setAttribute("maxlength",5);
			input_hidden.setAttribute("size",5);
            input_hidden.style.width = '30px';
			input_hidden.onblur = function(){extractNumber(this,0,false);}; 
			input_hidden.onkeyup = function(){extractNumber(this,0,false);};
			input_hidden.onkeypress = function(){return blockNonNumbers(this, event, true, false);};
			cell6.appendChild(input_hidden); //���� Count
			
                        //if(jQuery("#subeventid").val() != '0'){
                            var input_hidden = document.createElement("input");
                            input_hidden.type = "hidden";
                            input_hidden.name = "sub_eventid[]";
                            
                            var resValSub = [];
                            input_hidden.value = jQuery("#subeventid"+prefix).val();
                            //resValSub.push(jQuery("#subeventid").val());
                            //resValSub[jQuery("#subeventid").val()] = [];
                            var subpl = [];
                            var assistText = [];
                            var selection = jQuery('#playerzSub_id'+prefix).getSelectionOrder();
                            console.log(selection);
                            if(selection.length){
                                for(var i=0;i<selection.length;i++){
                                    console.log(jQuery("#playerzSub_id"+prefix).find('option[value="'+selection[i]+'"]'));
                                    resValSub.push(selection[i]);
                                    assistText.push(jQuery("#playerzSub_id"+prefix).find('option[value="'+selection[i]+'"]').text());
                                }
                            }
                            //jQuery("#playerzSub_id").chosen();
                            //$("#select-id").chosen().find("option:selected" ).text();
                            console.log(jQuery("#playerzSub_id"+prefix+" :selected").text());
                            jQuery.each(jQuery("#playerzSub_id"+prefix+" option:selected"), function(){
                                subpl.push(jQuery(this).val());
                            });
                            
                            var input_hidden2 = document.createElement("input");
                            input_hidden2.type = "hidden";
                            input_hidden2.name = "sub_eventid_vals[]";
                            input_hidden2.value = resValSub;
                            
                            //alert("You have selected the country - " + subpl.join(", "));
                            
                            cell9.innerHTML = assistText.join(", ");
                            cell9.appendChild(input_hidden);
                            cell9.appendChild(input_hidden2);
                        //}
                        
                        row.appendChild(cell0);
			row.appendChild(cell1);
			row.appendChild(cell2);
			row.appendChild(cell5);
                        row.appendChild(cell9);
			row.appendChild(cell4);
			row.appendChild(cell6);
			row.appendChild(cell7);
			//row.appendChild(cell8);
			getObj('event_id'+prefix).value =  0;
			getObj('playerz_id'+prefix).value =  0;
			getObj('e_minutes'+prefix).value = '';
			getObj('re_count'+prefix).value =  1;
                        getObj('playerzSub_id'+prefix).value =  0;
			
                        jQuery('#event_id'+prefix).trigger("liszt:updated");
                        jQuery('#playerz_id'+prefix).trigger("liszt:updated");
                        jQuery('#playerzSub_id'+prefix).trigger("liszt:updated");
			//ReAnalize_tbl_Rows('new_events');
			
		}
		function bl_add_tevent(){
			var cur_event = getObj('tevent_id');
			
			var e_count = getObj('et_count').value;
			var e_player = getObj('teamz_id');
			
			if (cur_event.value == 0) {
				alert("<?php echo JText::_('BLBE_SELEVENT'); ?>");return;
			}
			if (e_player.value == 0) {
				alert("<?php echo JText::_('BLBE_SELTEAM'); ?>");return;
			}
			
			var exevs = eval( 'document.adminForm["new_teventid\[\]"]');
			var exiev = eval( 'document.adminForm["new_tplayer\[\]"]');
			if(exevs && exiev){
				var ransw2 = exevs.length;
				if(ransw2){
					for (var i=0; i < ransw2; i++) {
						if(exiev[i].value == e_player.value && exevs[i].value == cur_event.value){
							alert("<?php echo JText::_('BLBE_JSMDNOT66'); ?>");return;
						}
					} 
				}else{
					if(exiev.value == e_player.value && exevs.value == cur_event.value){
						alert("<?php echo JText::_('BLBE_JSMDNOT66'); ?>");return;
					}
				}
				
			}
			
			var tbl_elem = getObj('new_tevents');
			var row = tbl_elem.insertRow(tbl_elem.rows.length);
			var cell1 = document.createElement("td");
			var cell2 = document.createElement("td");
			var cell3 = document.createElement("td");
			var cell4 = document.createElement("td");
			var cell5 = document.createElement("td");
			var cell6 = document.createElement("td");
			var cell7 = document.createElement("td");
			var cell8 = document.createElement("td");
			
			var input_hidden = document.createElement("input");
			input_hidden.type = "hidden";
			input_hidden.name = "et_id[]";
			input_hidden.value = 0;
			cell1.appendChild(input_hidden);
			cell1.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('BLBE_DELETE');?>"><img src="components/com_joomsport/img/publish_x.png"  bOrder="0" alt="Delete"></a>';
			
			var input_hidden = document.createElement("input");
			input_hidden.type = "hidden";
			input_hidden.name = "new_teventid[]";
			input_hidden.value = cur_event.value;
			cell2.innerHTML = cur_event.options[cur_event.selectedIndex].text;
			cell2.appendChild(input_hidden);
			
			var input_hidden = document.createElement("input");
			input_hidden.type = "text";
			input_hidden.name = "et_countval[]";
			input_hidden.value = e_count;
			input_hidden.setAttribute("maxlength",5);
			input_hidden.setAttribute("size",5);
            input_hidden.style.width = '30px';
			input_hidden.onblur = function(){extractNumber(this,0,false);}; 
			input_hidden.onkeyup = function(){extractNumber(this,0,false);};
			input_hidden.onkeypress = function(){return blockNonNumbers(this, event, true, false);};
			//cell3.align = "center";
			//cell3.innerHTML = e_count;
			cell3.appendChild(input_hidden);
			
			
			var input_player = document.createElement("input");
			input_player.type = "hidden";
			input_player.name = "new_tplayer[]";
			input_player.value = e_player.value;
			if(e_player.value != 0){
				cell5.innerHTML = e_player.options[e_player.selectedIndex].text;
			}	
			cell5.appendChild(input_player);
			
			row.appendChild(cell1);
			row.appendChild(cell2);
			row.appendChild(cell5);		
			row.appendChild(cell6);
			row.appendChild(cell3);
			row.appendChild(cell7);
			row.appendChild(cell8);
			
		
			getObj('tevent_id').value =  0;
			getObj('teamz_id').value =  0;
			getObj('et_count').value = 1;
                        jQuery('#tevent_id').trigger("liszt:updated");
                        jQuery('#teamz_id').trigger("liszt:updated");
			
			//ReAnalize_tbl_Rows('new_tevents'); 
		}
		
		function bl_add_squard(tblid,selid,elname){
			var cur_event = getObj(selid);
			

			if (cur_event.value == 0) {
				alert("<?php echo JText::_('BLBE_SELPLAYER'); ?>");return;
				}
			
			
			var tbl_elem = getObj(tblid);
			var row = tbl_elem.insertRow(tbl_elem.rows.length);
			var cell1 = document.createElement("td");
			var cell2 = document.createElement("td");
			
			
			
			cell1.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('BLBE_DELETE');?>"><img src="components/com_joomsport/img/publish_x.png"  bOrder="0" alt="Delete"></a>';
			
			var input_hidden = document.createElement("input");
			input_hidden.type = "hidden";
			input_hidden.name = elname;
			input_hidden.value = cur_event.value;
			cell2.innerHTML = cur_event.options[cur_event.selectedIndex].text;
			cell2.appendChild(input_hidden);
			
			
			
			row.appendChild(cell1);
			row.appendChild(cell2);
			
		
			getObj(selid).value =  0;
			
		}
		
		function Delete_tbl_row(element) {
			var del_index = element.parentNode.parentNode.sectionRowIndex;
			var tbl_id = element.parentNode.parentNode.parentNode.parentNode.id;
			element.parentNode.parentNode.parentNode.deleteRow(del_index);
		}
		
		function enblnp(){
			if(document.adminForm.new_points1.checked){
				getObj("newp1").removeAttribute('readonly');
				getObj("newp2").removeAttribute('readonly');
				getObj("newp1").removeAttribute('disabled');
				getObj("newp2").removeAttribute('disabled');
                                getObj("enbl_manpts").style.display = 'block';
			}else{
				getObj("newp1").setAttribute('readonly','readonly');
				getObj("newp2").setAttribute('readonly','readonly');
				getObj("newp1").setAttribute('disabled','true');
				getObj("newp2").setAttribute('disabled','true');
                                getObj("enbl_manpts").style.display = 'none';
			}
		}
		
		function sqchng(nid,nid2){
			if(getObj(nid).checked){
		
				getObj(nid2).checked = false;
			}
		}
		
		function js_add_subs(tblid,pl1,pl2,minutes){
			var tbl_elem = getObj(tblid);
			if(getObj(pl1).value == getObj(pl2).value || getObj(pl1).value == 0){
				return false;
			}
			var row = tbl_elem.insertRow(tbl_elem.rows.length);
			var cell1 = document.createElement("td");
			var cell2 = document.createElement("td");
			var cell3 = document.createElement("td");
			var cell4 = document.createElement("td");
                        var cell5 = document.createElement("td");
			
			
			cell1.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this);getSubsLists(\'squadradio1\');getSubsLists(\'squadradio2\'); return false;" title="<?php echo JText::_('BLBE_DELETE')?>"><img src="components/com_joomsport/img/publish_x.png"  bOrder="0" alt="Delete"></a>';
			
			var input_hidden = document.createElement("input");
			input_hidden.type = "hidden";
			input_hidden.name = pl1+"_arr[]";
			input_hidden.value = getObj(pl1).value;
			cell2.innerHTML = getObj(pl1).options[getObj(pl1).selectedIndex].text;
			cell2.appendChild(input_hidden);
			var input_hidden = document.createElement("input");
			input_hidden.type = "hidden";
			input_hidden.name = pl2+"_arr[]";
			input_hidden.value = getObj(pl2).value;
                        if(getObj(pl2).value != 0){
                            cell3.innerHTML = getObj(pl2).options[getObj(pl2).selectedIndex].text;
                        }else{    
                            cell3.innerHTML = '';
                        }
			
			cell3.appendChild(input_hidden);
			
			var input_hidden = document.createElement("input");
			input_hidden.type = "text";
            input_hidden.style.width = "30px";
			input_hidden.name = minutes+"_arr[]";
			input_hidden.value = getObj(minutes).value;
			input_hidden.setAttribute("maxlength",5);
			input_hidden.setAttribute("size",5);
			cell4.appendChild(input_hidden);
			
			row.appendChild(cell1);
                        row.appendChild(cell3);
			row.appendChild(cell2);
			
			row.appendChild(cell4);
                        row.appendChild(cell5);
			
			getObj(minutes).value =  0;
                        getSubsLists('squadradio1');
                        getSubsLists('squadradio2');
		}
		
		function ReAnalize_tbl_Rows( tbl_id ) {
			start_index = 0;
			var tbl_elem = getObj(tbl_id);
			if (tbl_elem.rows[start_index]) {
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					
					
					
					if (i > 0) { 
						tbl_elem.rows[i].cells[5].innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('BLBE_MOVEUP');?>"><img src="components/com_joomsport/img/up.gif"  border="0" alt="<?php echo JText::_('BLBE_MOVEUP');?>"></a>';
					} else { tbl_elem.rows[i].cells[5].innerHTML = ''; }
					if (i < (tbl_elem.rows.length - 1)) {
						tbl_elem.rows[i].cells[6].innerHTML = '<a href="javascript: void(0);" onClick="javascript:Down_tbl_row(this); return false;" title="<?php echo JText::_('BLBE_MOVEDOWN');?>"><img src="components/com_joomsport/img/down.gif"  border="0" alt="<?php echo JText::_('BLBE_MOVEDOWN');?>"></a>';
					} else { tbl_elem.rows[i].cells[6].innerHTML = ''; }

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
				row.appendChild(element.parentNode.parentNode.cells[0]);
				row.appendChild(element.parentNode.parentNode.cells[0]);
				row.appendChild(element.parentNode.parentNode.cells[0]);
				//row.appendChild(element.parentNode.parentNode.cells[0]);
				
				var cell5 = document.createElement("td");
				var cell6 = document.createElement("td");
				row.appendChild(cell5);
				row.appendChild(cell6);
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
				row.appendChild(element.parentNode.parentNode.cells[0]);
				row.appendChild(element.parentNode.parentNode.cells[0]);
				row.appendChild(element.parentNode.parentNode.cells[0]);
				//row.appendChild(element.parentNode.parentNode.cells[0]);
				
				var cell5 = document.createElement("td");
				var cell6 = document.createElement("td");
				row.appendChild(cell5);
				row.appendChild(cell6);
				element.parentNode.parentNode.parentNode.deleteRow(element.parentNode.parentNode.sectionRowIndex);
				
				ReAnalize_tbl_Rows(tbl_id);
			}

			
		}

                        jQuery( document ).ready(function() {
                            if(jQuery("input[name='is_extra']:checked").val() == '1'){
                                jQuery(".jsHideAet").show();
                            }else{
                                jQuery(".jsHideAet").hide();
                            }
                            jQuery("input[name='is_extra']").on("click",function(){
                                if(jQuery("input[name='is_extra']:checked").val() == '1'){
                                    jQuery(".jsHideAet").show();
                                }else{
                                    jQuery(".jsHideAet").hide();
                                }
                            });



                            jQuery(".evTblSortable").sortable(

                            );

                            getSubsLists('squadradio1');
                            getSubsLists('squadradio2');

                        });
                
                function getSubEvents(prefix){
                    var eventid = jQuery("#event_id"+prefix).val();
                    jQuery("#ncPlSubTitle"+prefix).html("");
                    jQuery("#ncPlSub"+prefix).hide();
                    jQuery("#subeventid"+prefix).val("0");
                        
                    jQuery.ajax({
                        url: "index.php?option=com_joomsport&task=getSubEvents&tmpl=component&no_html=1",
                        type: 'POST',
                        data: {'eventid' : eventid},
                        
                      }).done(function(res) {
                        if(res && res != 'null'){
                            var jsOn = jQuery.parseJSON(res);
                            if(jsOn.name && jsOn.id){
                                jQuery("#ncPlSubTitle"+prefix).html(jsOn.name + ' :');
                                jQuery("#ncPlSub"+prefix).show();
                                jQuery("#subeventid"+prefix).val(jsOn.id);
                            }
                        }
                        
                      });
                }
		
		//-->
		</script>
		<form action="index.php?option=com_joomsport" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
		
                <?php
        if (!$lists['t_single']) {
            ?>
                    <div class="jsrespdiv12">
                        <div class="jsBepanel">
                            <div class="jsBEheader">
                                <?php echo JText::_('BLBE_HEAD_MATCHEDIT');
            ?>
                            </div>
                            <div class="jsBEsettings" style="padding:0px;">
                            <!-- <tab box> -->
                                <ul class="tab-box">
                                <?php
                                echo $etabs->newTab(JText::_('BLBE_MAIN'), 'match_conf', '', 'vis');

            echo $etabs->newTab(JText::_('BLBE_SQUARD'), 'squard_conf', '');
            ?>
                                </ul>
                            </div>
                        </div>
                    </div>    
			<?php

        }
        ?>
		<div style="clear:both"></div>
		<div id="match_conf_div" class="tabdiv">
                    <div class="jsrespdiv8">
                        <div class="jsBepanel">
                            <div class="jsBEheader">
                                <?php echo JText::_('BLBE_HEAD_SCOREPOINTS'); ?>
                            </div>
                            <div class="jsBEsettings">
                                <table class="jsnoborders">
                                    <tr>
                                        <td width="200">
                                                <?php echo JText::_('BLBE_SCORE'); ?>
                                                <?php if($lists['t_type'] == 3){echo ' <i class="fa fa-exchange jsknchange" aria-hidden="true"></i>';}; ?>
                                        </td>
                                        <td style="vertical-align:middle;">
                                                <?php
                                                if($lists['t_type'] == 3){
                                                    ?>
                                                        <span class="jsSpanHome"><?php echo $lists['teams1']?><input type="hidden" name="knteamid[]" value="<?php echo $row->team1_id;?>" /></span>

                                                        <span class="jsSpanHomeScore" style="width:52px;display: inline-block;text-align: center;"><?php echo $row->score1?><input type="hidden" name="knteamscore[]" value="<?php echo $row->score1;?>" /></span>&nbsp;:&nbsp;<span class="jsSpanAwayScore" style="width:52px;display: inline-block;text-align: center;"><?php echo $row->score2?><input type="hidden" name="knteamscore[]" value="<?php echo $row->score2;?>" /></span>&nbsp;<span class="jsSpanAway"><?php echo $lists['teams2']?><input type="hidden" name="knteamid[]" value="<?php echo $row->team2_id;?>" /></span>&nbsp;
                        
                                                    <?php
                                                }else{
                                                    echo $lists['teams1'].' <input type="text" style="width:30px;" name="score1" value="'.$row->score1.'" size="5" maxlength="5" onblur="extractNumber(this,0,false);" onkeyup="extractNumber(this,0,false);" onkeypress="return blockNonNumbers(this, event, false, false);" />';


                                                    echo '&nbsp;:&nbsp;<input type="text" style="width:30px;" name="score2" value="'.$row->score2.'" size="5" maxlength="5" onblur="extractNumber(this,0,false);" onkeyup="extractNumber(this,0,false);" onkeypress="return blockNonNumbers(this, event, false, false);" />&nbsp;'.$lists['teams2'].'&nbsp;';
                                                }
                                                if ($lists['t_type']) {
                                                    echo '<div style="text-align:center;">';
                                                    echo '&nbsp;<input type="checkbox" id="spenwin_1" '.(($row->p_winner && $row->p_winner == $row->team1_id) ? 'checked' : '').' name="penwin[]" value="'.$row->team1_id.'" onchange="sqchng(\'spenwin_1\',\'spenwin_2\');" onblur="extractNumber(this,0,false);" onkeyup="extractNumber(this,0,false);" onkeypress="return blockNonNumbers(this, event, false, false);" />&nbsp;&nbsp;';
                                                
                                                    echo JText::_('WINNER').'&nbsp;&nbsp;<input type="checkbox" id="spenwin_2" '.(($row->p_winner && $row->p_winner == $row->team2_id) ? 'checked' : '').' onchange="sqchng(\'spenwin_2\',\'spenwin_1\');" name="penwin[]" value="'.$row->team2_id.'" onblur="extractNumber(this,0,false);" onkeyup="extractNumber(this,0,false);" onkeypress="return blockNonNumbers(this, event, false, false);" />';
                                                
                                                    echo '</div>';
                                                }
                                                ?>
                                        </td>
                                    </tr>
                                    <?php if ($lists['s_enbl_extra']) {
    ?>
                                        <tr>
                                                <td>
                                                        <?php echo JText::_('BLBE_ET');
    ?>

                                                </td>
                                                <td>
                                                    <div class="controls">
                                                        <fieldset class="radio btn-group">
                                                            <?php echo $this->lists['extra'];
    ?>
                                                        </fieldset>
                                                    </div>

                                                </td>
                                        </tr>
                                    <?php 
} ?>
                                    <?php
                                    //if ($lists['s_enbl_extra'] && $lists['t_type']) {
                                        ?>
                                    <tr>
                                        <td class="jsHideAet">
                                            <?php echo JText::_('AET');
                                        ?>
                                        </td>
                                        <td class="jsHideAet">
                                            <?php
                                            echo $lists['teams1'];
                                        ?>
                                            <input type="text" style="width:30px;" id="aet1" name="aet1" value="<?php echo $row->aet1?>" size="5" maxlength="5"
                                        onblur="extractNumber(this,0,false);" onkeyup="extractNumber(this,0,false);" onkeypress="return blockNonNumbers(this, event, false, false);" />
                                            :&nbsp;<input type="text" id="aet2" style="width:30px;" name="aet2" value="<?php echo $row->aet2?>" size="5" maxlength="5"
                                        onblur="extractNumber(this,0,false);" onkeyup="extractNumber(this,0,false);" onkeypress="return blockNonNumbers(this, event, false, false);" />
                                            <?php
                                            echo $lists['teams2'];
                                        ?>
                                        </td>
                                    </tr>
                                    <?php

                                    //}
                                    ?>
                                    
                                    <?php
                                    if (count($lists['maps'])) {
                                        for ($i = 0;$i < count($lists['maps']);++$i) {
                                            ?>

                                    <tr>
                                        <td>
                                            <?php echo $lists['maps'][$i]->m_name;
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            echo $lists['teams1'];
                                            echo "&nbsp;<input type='text' name='t1map[]' style='width:30px;' size='5' value='".(isset($lists['maps'][$i]->m_score1) ? $lists['maps'][$i]->m_score1 : '')."'  />";
                                            echo "&nbsp;:&nbsp;<input type='text' name='t2map[]' style='width:30px;' size='5' value='".(isset($lists['maps'][$i]->m_score2) ? $lists['maps'][$i]->m_score2 : '')."' />";
                                            echo ' '.$lists['teams2'];
                                            echo "<input type='hidden' name='mapid[]' value='".$lists['maps'][$i]->id."'/>";

                                            ?>
                                        </td>

                                    </tr>
                                    <?php

                                        }
                                    }
                                    ?>
                                    
                                    <?php if (!$lists['t_type'] && $this->s_id != -1) {
    ?>
                                    <tr>
                                            <td width="100">
                                                    <?php echo JText::_('BLBE_BONUS');
    ?>
                                            </td>
                                            <td>
                                                    <?php echo $lists['teams1'].' <input type="text" style="width:30px;" name="bonus1" value="'.floatval($row->bonus1).'" size="5" maxlength="5" onblur="extractNumber(this,2,true);" onkeyup="extractNumber(this,2,true);" onkeypress="return blockNonNumbers(this, event, true, true);" />&nbsp;:&nbsp;<input type="text" style="width:30px;" name="bonus2" value="'.floatval($row->bonus2).'" size="5" maxlength="5" onblur="extractNumber(this,2,true);" onkeyup="extractNumber(this,2,true);" onkeypress="return blockNonNumbers(this, event, true, true);"  /> '.$lists['teams2'];
    ?>
                                            </td>
                                    </tr>
                                    <tr>
                                            <td width="200">
                                                    <?php echo JText::_('BLBE_MANUAL_POINT');
    ?>
                                            </td>
                                            <td>
                                                    <div class="controls"><fieldset class="radio btn-group"><?php echo $this->lists['new_points'];
    ?></fieldset></div>

                                                    <?php echo "<div id='enbl_manpts' style='margin-top:10px;".($row->new_points ? '' : 'display:none;')."'>".$lists['teams1'].' <input type="text" style="width:30px;" name="points1" id="newp1" value="'.floatval($row->points1).'" size="5" maxlength="5"  '.(!$row->new_points ? "readonly='readonly' disabled='true'" : "readonly='readonly' disabled='true'").' onblur="extractNumber(this,2,true);" onkeyup="extractNumber(this,2,true);" onkeypress="return blockNonNumbers(this, event, true, true);" />&nbsp;:&nbsp;<input type="text" name="points2" id="newp2" style="width:30px;" value="'.floatval($row->points2).'" size="5" maxlength="5" '.(!$row->new_points ? "readonly='readonly' disabled='true'" : "readonly='readonly' disabled='false'").' onblur="extractNumber(this,2,true);" onkeyup="extractNumber(this,2,true);" onkeypress="return blockNonNumbers(this, event, true, true);" /> '.$lists['teams2'];
    ?>


                                            </td>
                                    </tr>
                                    
                                    <?php 
} ?>
                                </table>
                            </div>
                        </div>  
                        <div class="jsBepanel">
                            <div class="jsBEheader">
                                <?php echo JText::_('BLBE_ABOUTMATCH'); ?>
                                <?php
                                if(count($lists['languages'])){

                                    echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                }?>
                            </div>
                            <div class="jsBEsettings">
                                <?php echo $editor->display('match_descr',  htmlspecialchars($row->match_descr, ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore'));  ?>
                                <?php
                                    if(count($lists['languages'])){
                                        echo '<div class="jsTranslationContainer">';
                                        foreach (($lists['languages']) as $value) {
                                            echo '<div class="jsTranslationDiv">';
                                            $translation = '';
                                            echo $value;
                                            if(isset($lists['translation']) && isset($lists['translation'][$value]['match_descr'])){
                                                $translation = $lists['translation'][$value]['match_descr'];
                                            }
                                            echo $editor->display('translation['.$value.'][match_descr]',  htmlspecialchars($translation, ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore'));
                                            
                                            echo '</div>';
                                        }
                                        echo '</div>';
                                    }
                                    ?>                
                            </div>
                        </div>    
                        
                        
                        <div class="jsBepanel">
                            <div class="jsBEheader">
                                <?php echo JText::_('BLBE_PLAYEREVENTS'); ?>
                            </div>
                            <div class="jsBEsettings">
                                <?php
                                if (!$lists['t_single'] && !count($lists['team2_players']) && !count($lists['team1_players'])) {
                                    echo JText::sprintf('BLBE_WARN_NOPLAYERSINTEAM', '<a href="index.php?option=com_joomsport&task=team_edit&cid[]='.$row->team1_id.'">'.$lists['teams1'].'</a>', '<a href="index.php?option=com_joomsport&task=team_edit&cid[]='.$row->team2_id.'">'.$lists['teams2'].'</a>');
                                } else {
                                    echo '<div>';
                                    jsview_player_events_by_stages(0, $lists['m_events'], $lists);
                                    echo '</div>';
                                    $stagesSeparate = jsHelperStages::getStagesManual($this->s_id);
                                    $db = JFactory::getDBO();
                                    if($stagesSeparate && count($stagesSeparate)){
                                        foreach($stagesSeparate as $stage){
                                            $query = "SELECT me.*,ev.e_name,CONCAT(p.first_name,' ',p.last_name) as p_name,(subev.e_id) as subevID, GROUP_CONCAT(CONCAT(subev.player_id,'*',subev.t_id)) as subevPl, GROUP_CONCAT(CONCAT(pSub.first_name,' ',pSub.last_name)) as plFM"
                                                .' FROM  #__bl_match_events as me'
                                                . ' JOIN #__bl_events as ev ON me.e_id = ev.id AND me.match_id = '.$row->id
                                                . ' JOIN #__bl_players as p ON me.player_id = p.id'
                                                . ' LEFT JOIN #__bl_match_events as subev ON subev.additional_to = me.id'
                                                . ' LEFT JOIN #__bl_players as pSub ON subev.player_id = pSub.id '
                                                ." WHERE ev.player_event = '1' AND ev.dependson=''"
                                                ." AND me.stage_id = ".$stage->id
                                                .' '.(count($lists["pl_list"]) ? 'AND me.player_id IN('.implode(',', $lists["pl_list"]).')' : '')
                                                .' GROUP BY me.id'
                                                .' ORDER BY me.eordering, CAST(me.minutes AS UNSIGNED),p.first_name,p.last_name';

                                            $db->setQuery($query);
                                            $pevents = $db->loadObjectList();
                                            ?>
                                            <div>
                                                <h4><?=$stage->m_name;?></h4>
                                                <?php
                                                jsview_player_events_by_stages($stage->id, $pevents, $lists);
                                                ?>
                                            </div>
                                            <?php
                                        }
                                    }
                                }
                            ?>    
                        </div>
                    </div>                

	<?php if (!$lists['t_single']) {
    ?>

                    <div class="jsBepanel">
                        <div class="jsBEheader">
                            <?php echo JText::_('BLBE_MATCHSTATS');
    ?>
                        </div>
                        <div class="jsBEsettings">
                            <?php
                            if($lists['team_events_list']){
                            ?>
                            
                            <table>
                                <tr>
                                    <th><?php echo $lists['teams1']?></th>
                                    <th>&nbsp;</th>
                                    <th><?php echo $lists['teams2']?></th>
                                </tr>
                                <?php
                                
                                for($intA=0;$intA<count($lists['team_events_list']);$intA++){
                                    $evT = $lists['team_events_list'][$intA];
                                    ?>
                                    <tr>
                                        <td style="text-align: center;"><input style="width:40px;" type="number" name="mevents1[]" value="<?php echo (isset($evT->home)?$evT->home:'')?>" /></td>
                                        <td style="padding:0px 20px; text-align: center;"><?php echo $evT->e_name;?><input type="hidden" name="mevent_id[]" value="<?php echo $evT->id;?>" /></td>
                                        <td style="text-align: center;"><input style="width:40px;" type="number" name="mevents2[]" value="<?php echo (isset($evT->away)?$evT->away:'')?>" /></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                            

                            <?php
                            }else{
                                echo JText::sprintf('BLBE_MATCHEVENTS_NORECORDS','<a href="index.php?option=com_joomsport&task=event_list">','</a>');
                            }
                            ?>
                        </div>
                </div>        


                <div class="jsBepanel">
                    <div class="jsBEheader">
                        <?php echo JText::_('BLBE_HEAD_BOX_SCORE'); ?>
                    </div>
                    <div class="jsBEsettings"> 
                        <?php echo $lists['boxhtml'];?>
                    </div>	
                </div>
                    <?php 
} ?>                

                <div class="jsBepanel">
                    <div class="jsBEheader">
                        <?php echo JText::_('BLBE_HEAD_IMAGES'); ?>
                    </div>
                    <div class="jsBEsettings">            

                    <?php
                        require_once 'components/com_joomsport/helpers/images.php';
                        echo ImagesHelper::loaderUI($lists['photos'], null, true, 0);
                    ?>
                    </div>	
                </div>
                        
                        
                    
                
            </div>            
                  
                    <div class="jsrespdiv4 jsrespmarginleft2">
                        <div class="jsBepanel">
                            <div class="jsBEheader">
                                <?php echo JText::_('BLBE_GENERAL'); ?>
                            </div>
                            <div class="jsBEsettings">
                                    <table class="jsnoborders">



                                        
                                        <tr>
                                                <td width="100">
                                                        <?php echo JText::_('JSTATUS'); ?>
                                                </td>
                                                <Td>

                                    <div class="controls"><fieldset class="radio btn-group"><?php echo $this->lists['m_played'];?></fieldset></div>
                                                </Td>
                                        </tr>
                                        <tr>
                                                <td>
                                                        <?php echo JText::_('BLBE_DATE');?>

                                                </td>
                                                <td>
                                                        <?php
                                    //print_r(intval($row->m_date));
                                                                echo JHTML::_('calendar', (intval($row->m_date) ? $row->m_date : ''), 'm_date', 'm_date', '%Y-%m-%d', array('class' => 'inputbox', 'size' => '20',  'maxlength' => '10', 'style' => 'width:76px'));
                                                        ?>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td>
                                                        <?php echo JText::_('BLBE_TIME');?>

                                                </td>
                                                <td>
                                                        <input type="text" maxlength="5" size="10" name="d_time" value="<?php echo substr($row->m_time, 0, 5);?>" />

                                                </td>
                                        </tr>
                                        <?php if ($lists['unbl_venue'] == '0') {
    ?>
                                        <tr>
                                                <td>
                                                        <?php echo JText::_('BLBE_LOCATION');
    ?>

                                                </td>
                                                <td>
                                                        <input type="text" maxlength="255" size="20" name="m_location" value="<?php echo htmlspecialchars($row->m_location);
    ?>" />

                                                </td>
                                        </tr>
                                        <?php 
}
                                        ?>
                                        <?php if ($lists['unbl_venue'] == '1') {
    ?>
                                        <tr>
                                                <td>
                                                        <?php echo JText::_('BLBE_VENUE');
    ?>

                                                </td>
                                                <td>
                                                        <?php echo $lists['venue'];
    ?>

                                                </td>
                                        </tr>
                                        <?php

}
                                        ?>

                                        <?php
                                        $moptions = json_decode($row->options, true);
                                        $jstimeline = json_decode($lists['jstimeline']);
                                        if(isset($jstimeline->duration) && $jstimeline->duration){
                                            $mtf = isset($moptions['duration']) ?$moptions['duration'] : $jstimeline->duration;
                                            ?>
                                            <tr>
                                                <td>
                                                        <?php echo JText::_('BLBE_MDURATION');
    ?>

                                                </td>
                                                <td>
                                                        <input type="text" name="moptions[duration]"style="width:50px;" maxlength="5" class="inputbox" value="<?php echo $mtf;?>" onblur="extractNumber(this, 0, false);" onkeyup="extractNumber(this, 0, false);" onkeypress="return blockNonNumbers(this, event, false, false);" />


                                                </td>
                                            </tr>
                                            <?php
                                            
                                        }
                                        ?>
                                       
                                </table>
                            </div>
                        </div>
                        <div class="jsBepanel">
                            <div class="jsBEheader">
                                <?php echo JText::_('BLBE_HEAD_TRANSFER'); ?>
                            </div>
                            <div class="jsBEsettings">
                                <?php echo JText::_('BLBE_MATCHDAYNAME_TRANSFER'); ?>:<br /><br />
                                <?php echo $lists['mday'];?>
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
                        
        </div>
    </div>                
	<?php

        if (!$lists['t_single']) {
            $sqr = array();

            $sqr[] = JHTML::_('select.option', 1, JText::_('BLBE_LANGVIEWSOTH_LINUP'));
            $sqr[] = JHTML::_('select.option', 2, JText::_('BLBE_SUBSTITUTE_OPTION'));
            $sqr[] = JHTML::_('select.option', 0, JText::_('BLBE_NOT_PARTICIPATED'));
            ?>	
		<div id="squard_conf_div" class="tabdiv" style="display:none;">
		<div class="jsrespdiv6">
                    <div class="jsBepanel">
                        <div class="jsBEheader">
                            <?php echo $lists['teams1'];
            ?>
                            <?php echo ' '.strtolower(JText::_('BLBE_LANGVIEWSOTH_LINUP'));
            ?>
                        </div>
                        <div class="jsBEsettings">
                            <?php 
                            if (count($lists['pl1'])) {
                                ?>
                            <div style="text-align: center; margin-bottom:10px;">
                                <input type="button" class="btn jscheckall" value="<?php echo JText::_('BLBE_LINEUPFORALL')?>" />
                                <input type="button" class="btn jscheckallnot" value="<?php echo JText::_('BLBE_NOTPARTICIPATED')?>" />
                            </div>
                            <table class="table table-striped" id="new_squard1">
				
				<?php //var_dump($lists['squard1_res']);

                                foreach ($lists['pl1'] as $m_events) {
                                    echo '<tr>';

                                    echo '<td>'.$m_events->p_name.'</td>';
                                    $main_chk = 0;

                                    if (count($lists['squard1']) && in_array($m_events->pid, $lists['squard1'])) {
                                        $main_chk = 1;
                                    }
                                    if (count($lists['squard1_res']) && in_array($m_events->pid, $lists['squard1_res'])) {
                                        $main_chk = 2;
                                    }
                                    echo '<td>';
                                    echo '<div class="controls squardbut"><fieldset class="radio btn-group-js">';
                                    echo JHTML::_('select.radiolist', $sqr, 'squadradio1_'.$m_events->pid, 'class="inputbox" id="squadradio_'.$m_events->pid.'" ', 'value', 'text', $main_chk);
                                    echo '<input type="hidden" name="t1_squard[]" value="'.$m_events->pid.'" />';
                                    echo '</fieldset></div>';
                                    echo '</td>';
                                    echo '</tr>';
                                }

                                ?>
                            </table>
                            <?php 
                            } else {
                                echo JText::sprintf('BLBE_WARN_NOPLAYERSINCURTEAM', '<a href="index.php?option=com_joomsport&task=team_edit&cid[]='.$row->team1_id.'">', $lists['teams1'], '</a>');
                            }
            ?>
                        </div>    
                    </div>        

                    <div class="jsBepanel">
                        <div class="jsBEheader">
                            <?php echo $lists['teams1'].' '.JText::_('BLBE_LANGVIEWSOTH_SUBST');
            ?>
                        </div>
                        <div class="jsBEsettings">
				<table class="table table-striped">
                                    <tbody id="subsid_1">
					<tr>
						<th width="5%">
						#
						</th>
						<th>
							<?php echo JText::_('BLBE_PLAYERIN');
            ?>
						</th>
						<th>
							<?php echo JText::_('BLBE_PLAYEROUT');
            ?>
						</th>
						<th>
							<?php echo JText::_('BLBE_MINUTES');
            ?>
						</th>
                                                <th></th>
					</tr>
				  	
					<?php
                    if (count($lists['subsin1'])) {
                        for ($i = 0;$i < count($lists['subsin1']);++$i) {
                            $subs = $lists['subsin1'][$i];
                            echo '<tr>';
                            echo '<td>';
                            echo '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this);getSubsLists(\'squadradio1\'); return false;" title="'.JText::_('BLBE_DELETE').'"><img src="components/com_joomsport/img/publish_x.png"  bOrder="0" alt="Delete"></a>';
                            echo '</td>';
                            echo '<td>';
                            echo '<input type="hidden" value="'.$subs->player_in.'" name="playersq1_out_id_arr[]" />'.$subs->plin;
                            echo '</td>';
                            echo '<td>';
                            echo '<input type="hidden" value="'.$subs->player_out.'" name="playersq1_id_arr[]" />'.$subs->plout;
                            echo '</td>';
                            echo '<td>';
                            echo '<input type="text" style="width:30px;" value="'.$subs->minutes.'" name="minutes1_arr[]" maxlength="5" size="5" />';
                            echo '</td>';
                            echo '<td>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    }
            ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
						<td>
						</td>
						<td>
							<?php echo $lists['players_team1_out']?>
						</td>
						<td>
							<?php echo $lists['players_team1']?>
						</td>
						<td>
							<input type="text" style="width:30px;" name="minutes1" id="minutes1" value="" maxlength="5" size="5" />
						</td>
                                                <td>
                                                        <input class="btn btn-small" type="button" value="<?php echo JText::_('BLBE_ADD');
            ?>" style="cursor:pointer;" onclick="js_add_subs('subsid_1','playersq1_id','playersq1_out_id','minutes1');" />
						</td>
					</tr>
                                    </tfoot>    
				</table>
                            </div>
				
			</div>
		</div>
		<div class="jsrespdiv6 jsrespmarginleft2">
                    <div class="jsBepanel">
                        <div class="jsBEheader">
                            <?php echo $lists['teams2'];
            ?>
                            <?php echo ' '.strtolower(JText::_('BLBE_LANGVIEWSOTH_LINUP'));
            ?>
                        </div>
                        <div class="jsBEsettings">
                            <?php
                            if (count($lists['pl2'])) {
                                ?>
                            <div style="text-align: center; margin-bottom:10px;">
                                <input type="button" class="btn jscheckall" value="<?php echo JText::_('BLBE_LINEUPFORALL')?>" />
                                <input type="button" class="btn jscheckallnot" value="<?php echo JText::_('BLBE_NOTPARTICIPATED')?>" />
                            </div>
                            <table class="table table-striped" id="new_squard2">
				
				<?php


                                    foreach ($lists['pl2'] as $m_events) {
                                        echo '<tr>';

                                        echo '<td>'.$m_events->p_name.'</td>';
                                        $main_chk = 0;

                                        if (count($lists['squard2']) && in_array($m_events->pid, $lists['squard2'])) {
                                            $main_chk = 1;
                                        }
                                        if (count($lists['squard2_res']) && in_array($m_events->pid, $lists['squard2_res'])) {
                                            $main_chk = 2;
                                        }
                                        echo '<td>';
                                        echo '<div class="controls squardbut"><fieldset class="radio btn-group-js">';
                                        echo JHTML::_('select.radiolist', $sqr, 'squadradio2_'.$m_events->pid, 'class="inputbox" id="squadradio_'.$m_events->pid.'" ', 'value', 'text', $main_chk);
                                        echo '<input type="hidden" name="t2_squard[]" value="'.$m_events->pid.'" />';
                                        echo '</fieldset></div>';
                                        echo '</td>';
                                       // echo '<td><input type="checkbox" name="t2_squard[]" id="t2sq_'.$m_events->pid.'" value="'.$m_events->pid.'" '.($main_chk?"checked='true'":"").' onclick="sqchng(\'t2sq_'.$m_events->pid.'\',\'t2sqr_'.$m_events->pid.'\');" /></td>';
                                        //echo '<td><input type="checkbox" name="t2_squard_res[]" id="t2sqr_'.$m_events->pid.'" value="'.$m_events->pid.'" '.($main_chk_r?"checked='true'":"").' onclick="sqchng(\'t2sqr_'.$m_events->pid.'\',\'t2sq_'.$m_events->pid.'\');"  /></td>';
                                        echo '</tr>';
                                    }

                                ?>
                            </table>
                            <?php 
                            } else {
                                echo JText::sprintf('BLBE_WARN_NOPLAYERSINCURTEAM', '<a href="index.php?option=com_joomsport&task=team_edit&cid[]='.$row->team2_id.'">', $lists['teams2'], '</a>');
                            }
            ?>
			</div>
                    </div>    
		
			
                    
                        <div class="jsBepanel">
                            <div class="jsBEheader">
                                <?php echo $lists['teams2'].' '.JText::_('BLBE_LANGVIEWSOTH_SUBST');
            ?>
                            </div>
                            <div class="jsBEsettings">
				
				<table class="table table-striped" >
                                    <tbody id="subsid_2">
					<tr>
						<th width="5%">
						#
						</th>
						<th>
							<?php echo JText::_('BLBE_PLAYERIN');
            ?>
						</th>
						<th>
							<?php echo JText::_('BLBE_PLAYEROUT');
            ?>
						</th>
						<th>
							<?php echo JText::_('BLBE_MINUTES');
            ?>
						</th>
                                                <th>
                                                    
                                                </th>
					</tr>
					
					<?php
                    if (count($lists['subsin2'])) {
                        for ($i = 0;$i < count($lists['subsin2']);++$i) {
                            $subs = $lists['subsin2'][$i];
                            echo '<tr>';
                            echo '<td>';
                            echo '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this);getSubsLists(\'squadradio2\'); return false;" title="'.JText::_('BLBE_DELETE').'"><img src="components/com_joomsport/img/publish_x.png"  bOrder="0" alt="Delete"></a>';
                            echo '</td>';
                            echo '<td>';
                            echo '<input type="hidden" value="'.$subs->player_in.'" name="playersq2_out_id_arr[]" />'.$subs->plin;
                            echo '</td>';
                            echo '<td>';
                            echo '<input type="hidden" value="'.$subs->player_out.'" name="playersq2_id_arr[]" />'.$subs->plout;
                            echo '</td>';
                            echo '<td>';
                            echo '<input type="text" style="width:30px;" value="'.$subs->minutes.'" name="minutes2_arr[]" maxlength="5" size="5" />';
                            echo '</td>';
                            echo '<td>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    }
            ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td>
                                            </td>
                                            
                                            <td>
                                                    <?php echo $lists['players_team2_out']?>
                                            </td>
                                            <td>
                                                    <?php echo $lists['players_team2']?>
                                            </td>
                                            <td>
                                                    <input type="text" style="width:30px;" name="minutes2" id="minutes2"  value="" maxlength="5" size="5" />
                                            </td>
                                            <td>
                                                    <input class="btn btn-small" type="button" value="<?php echo JText::_('BLBE_ADD');
            ?>" style="cursor:pointer;" onclick="js_add_subs('subsid_2','playersq2_id','playersq2_out_id','minutes2');" />
                                            </td>
					</tr>
                                    </tfoot>    
				</table>	
                            </div>
                        </div>
                    </div>    
                    
		</div>
		
		</div>
		</div>
		
		<?php

        }

        ?>	
		<input type="hidden" name="option" value="com_joomsport" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo $row->id?>" />
		<input type="hidden" name="team1_id" value="<?php echo $row->team1_id?>" />
		<input type="hidden" name="team2_id" value="<?php echo $row->team2_id?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="jscurtab" id="jscurtab" value="" />
                <input type="hidden" name="jsgallery" value="" />
		<?php echo JHTML::_('form.token'); ?>
		</form>
 <?php
function jsview_player_events_by_stages($prefixInt, $events, $lists){
    if($prefixInt){
        $prefix = "_" . $prefixInt;
    }else{
        $prefix = "";
    }
    ?>
    <div class="table-responsive">
        <table class="table table-striped beEvTbl"  cellspacing="0" cellpadding="0">
            <thead>
            <tr>
                <th class="title" width="10"></th>
                <th class="title" width="20">#</th>
                <th class="title" width="170">
                    <?php echo JText::_('BLBE_EVENT'); ?>
                </th>
                <th>
                    <?php echo JText::_('BLBE_PLAYER'); ?>
                </th>
                <th class="title" width="190"></th>
                <th class="title" width="120">
                    <?php echo JText::_('BLBE_MINUTES'); ?>
                </th>
                <th class="title" width="60">
                    <?php echo JText::_('BLBE_COUNT'); ?>
                </th>
                <th class="title" width="20">
                </th>

            </tr>
            </thead>
            <tbody id="new_events<?=$prefix?>" class="evTblSortable">
            <?php
            $ps = 0;

            if (count($events)) {
                foreach ($events as $m_events) {
                    echo '<tr class="ui-state-default">';
                    echo '<td width="10">
                                                                <span class="sortable-handler" style="cursor: move;">
                                                                    <span class="icon-menu"></span>
                                                                </span>
                                                            </td>';
                    echo '<td><input type="hidden" name="stage_id[]" value="'.$prefixInt.'" /><input type="hidden" name="em_id[]" value="'.$m_events->id.'" /><a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="'.JText::_('BLBE_DELETE').'"><img src="components/com_joomsport/img/publish_x.png"  bOrder="0" alt="Delete"></a></td>';
                    echo '<td><input type="hidden" name="new_eventid[]" value="'.$m_events->e_id.'" />'.$m_events->e_name.'</td>';
                    echo '<td><input type="hidden" name="new_player[]" value="'.$m_events->player_id.'" />'.$m_events->p_name.'</td>';
                    echo '<td>'.$m_events->plFM.'<input type="hidden" name="sub_eventid[]" value="'.$m_events->subevID.'" /><input type="hidden" name="sub_eventid_vals[]" value="'.$m_events->subevPl.'" /></td>';
                    echo '<td><input type="text" style="width:30px;" size="5" maxlength="5" name="e_minuteval[]" value="'.$m_events->minutes.'" onblur="extractNumberEv(this,2,true);" onkeyup="extractNumberEv(this,2,true);" /></td>';
                    echo '<td><input type="text" style="width:30px;" size="5" maxlength="5" name="e_countval[]" value="'.$m_events->ecount.'" onblur="extractNumber(this,0,false);" onkeyup="extractNumber(this,0,false);" onkeypress="return blockNonNumbers(this, event, true, false);" /></td>';

                    echo '<td >';

                    echo '</td>';
                    echo '</tr>';
                    ++$ps;
                }
            }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3" class="title" width="230"> </td>
                <td> </td>
                <td width="190">
                    <input type="hidden" id="subeventid<?=$prefix?>" name="subeventid" value="0" />
                    <div id="ncPlSubTitle<?=$prefix?>"></div>
                </td>
                <td class="title" width="60">
                </td>

                <td class="title" width="60">

                </td>
                <td>

                </td>

            </tr>
            <tr>

                <td colspan="3" class="title" width="230">
                    <?php echo JHTML::_('select.genericlist',   $lists['events_list'], 'event_id'.$prefix, 'class="inputbox chzn-done" size="1" style="width:170px;" onchange="getSubEvents(\''.$prefix.'\');"', 'id', 'e_name', 0);

                    ?>

                </td>
                <td>
                    <?php
                    if(!$lists['t_single']) {
                        echo '<select name="playerz_id" id="playerz_id' . $prefix . '" style="width:190px;" class="chzn-done" size="1">';
                        echo '<option value="0">' . JText::_('BLBE_SELPLAYER') . '</option>';
                    }
                    echo $lists['players'];
                    ?>

                </td>
                <td>
                    <div id="ncPlSub<?=$prefix?>" style="display:none;">
                        <?php
                        echo '<select name="playerzSub_id'.$prefix.'[]" id="playerzSub_id'.$prefix.'" style="width:120px;" multiple class="chzn-done" size="1" data-placeholder="Select player">';

                        echo $lists['playersSub'];?>
                    </div>
                </td>
                <td class="title" width="60">
                    <input name="e_minutes" style="width:30px;" id="e_minutes<?=$prefix?>" type="text" maxlength="5" size="5" onblur="extractNumberEv(this,2,true);" onkeyup="extractNumberEv(this,2,true);"  />
                </td>

                <td class="title" width="60">
                    <input name="re_count" style="width:30px;" id="re_count<?=$prefix?>" type="text" maxlength="5" size="5" value="1" onblur="extractNumber(this,0,false);" onkeyup="extractNumber(this,0,false);" onkeypress="return blockNonNumbers(this, event, true, false);" />

                </td>
                <td>
                    <input class="btn btn-small" type="button" style="cursor:pointer;"  value="<?php echo JText::_('BLBE_ADD');
                    ?>" onClick="bl_add_event('<?=$prefixInt?>');" />
                </td>

            </tr>
            </tfoot>
        </table>
    </div>
    <?php
}
?>