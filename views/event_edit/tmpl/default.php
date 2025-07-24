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
		<!--
		Joomla.submitbutton = function(task) {
			submitbutton(task);
		}
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			 if(pressbutton == 'event_apply' || pressbutton == 'event_save' || pressbutton == 'event_save_new'){
                 var reg = /^\s+$/;
                 if(form.e_name.value != '' && !reg.test(form.e_name.value)){
					submitform( pressbutton );
					return;
				}else{
					getObj('evname').style.border = "1px solid red";
					alert("<?php echo JText::_('BLBE_JSMDNOT17');?>");
					
					
				}
			}else{
				submitform( pressbutton );
					return;
			}			
		}	
		function View_eventimg(){
			getObj('view_img').innerHTML = '<img src="<?php echo JURI::base()?>../media/bearleague/events/'+document.adminForm.e_img.value+'" width="25" />';
		}
		function calctpfun(){
			if(getObj("player_event").value == '1'){
				getObj("calctp").style.display = "block";
                                getObj("evdependson").style.display = "block";
			}else{
				getObj("calctp").style.display = "none";
                                getObj("evdependson").style.display = "none";
			}
			if(getObj("player_event").value == '2'){
				getObj("calctp_sum").style.display = "block";
			}else{
				getObj("calctp_sum").style.display = "none";
			}
			
		}
		//-->
		</script>
		<form action="index.php?option=com_joomsport" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div class="jsrespdiv12">
    <div class="jsBepanel">
        <div class="jsBEheader">
            <?php echo JText::_('BLBE_GENERAL'); ?>
        </div>
        <div class="jsBEsettings">		
		<table>
			<tr>
				<td width="200">
					<?php echo JText::_('BLBE_EVENTNAME'); ?>
                                    <?php
                                    if(count($lists['languages'])){

                                        echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                    }?>
				</td>
				<td>
					<input type="text" name="e_name" size="50" value="<?php echo htmlspecialchars($row->e_name)?>" id="evname" maxlength="255" onKeyPress="return disableEnterKey(event);" />
                                        <?php
                                        if(count($lists['languages'])){

                                            echo '<div class="jsTranslationContainer">';
                                            foreach (($lists['languages']) as $value) {
                                                echo '<div class="jsTranslationDiv">';
                                                $translation = '';

                                                if(isset($lists['translation']) && isset($lists['translation'][$value]['e_name'])){
                                                    $translation = htmlspecialchars($lists['translation'][$value]['e_name'], ENT_QUOTES);
                                                }
                                                echo '<input type="text" maxlength="255" size="60" name="translation['.$value.'][e_name]" value="'.addslashes($translation).'"/>';
                                                echo '  ' . $value;
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                        }
                                        ?>
                                </td>
			</tr>
			<tr>
				<td width="200" valign="top">
					<?php echo JText::_('BLBE_PLEVENT'); ?>
				</td>
				<td>
					<?php echo $lists['player_event'];?>
				</td>
			</tr>
			
			<tr>
				<td colspan="2">
					<table cellpadding="0" id="calctp" <?php echo ($row->player_event == 1) ? '' : "style='display:none;'";?>>
						<tr>
							<td width="202" valign="top">
								<?php echo JText::_('BLBE_CALCETYPE'); ?>
							</td>
							<td>
								<?php echo $lists['restype'];?>
							</td>
						</tr>
					</table>				
				</td>	
			</tr>
                        
			<tr>
				<td colspan="2">
					<table cellpadding="0" id="calctp_sum" <?php echo ($row->player_event == 2) ? '' : "style='display:none;'";?>>
						<tr>
							<td width="202" valign="top">
								<?php echo JText::_('BLBE_SELEVENTFORSUM'); ?>
							</td>
							<td>
								<?php echo $lists['sumev1'];?><?php echo $lists['sumev2'];?>
							</td>
						</tr>
					</table>	
					
				</td>
			</tr>
                        <tr>
				<td colspan="2">
					<table cellpadding="0" id="evdependson" <?php echo ($row->player_event == 1) ? '' : "style='display:none;'";?>>
						<tr>
							<td width="202" valign="top">
								<?php echo JText::_('BLBE_EVENTDEPENDS'); ?>
							</td>
							<td>
								<?php echo $lists['dependson'];?>
							</td>
						</tr>
					</table>				
				</td>	
			</tr>
			<tr>
				<td width="200" valign="top">
					<?php echo JText::_('BLBE_EVIMG'); ?>
				</td>
				<td>
					<?php echo $lists['image'].' '.Jtext::_('BLBE_EVNEWIMG');?> <input type="file" name="new_event_img" id="event_img"/><input class="btn btn-small" type="button" value="<?php echo JText::_('BLBE_UPLOAD'); ?>" onclick="submitbutton('event_apply');" style="cursor:pointer;" id="get_img"/>
					<br />
					<div id="view_img" style="width:50px; height:50px; margin: 10px; ">
						<?php 
                        if ($row->e_img) {
                            echo '<img id="img_div" src="../media/bearleague/events/'.$row->e_img.'" width="25" />';
                        }
                        ?>
					</div>
				</td>
			</tr>
		</table>
            </div>
        </div>
    </div>
            <script type="text/javascript">
                var photo1 = document.getElementById("event_img");
                var but_on = document.getElementById("get_img");
                var serv_sett = <?php echo $lists['post_max_size'];?>;
                but_on.onclick = function() {
                    if( photo1.files[0].size > serv_sett){
                        alert("Image too big size (change settings post_max_size)");
                    }else{submitbutton('event_apply');}

                };
            </script>
		<input type="hidden" name="option" value="com_joomsport" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo $row->id?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_('form.token'); ?>
	</form>