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
<script type="text/javascript" src="components/com_joomsport/js/main.js"></script>
		<script type="text/javascript">
		<!--
		Joomla.submitbutton = function(task) {
			submitbutton(task);
		}
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'group_save' || pressbutton == 'group_apply' || pressbutton == 'group_save_new') {
                var reg = /^\s+$/;
                if(form.group_name.value != "" && form.s_id.value != 0 && !reg.test(form.group_name.value)){
					
                                    var srcListName = 'teams_seasons';
                                    var srcList = eval( 'form.' + srcListName );
                                    if(srcList){
                                        var srcLen = srcList.length;

                                        for (var i=0; i < srcLen; i++) {
                                                        srcList.options[i].selected = true;
                                        } 
                                        submitform( pressbutton );
                                        return;
                                    }
					
					
				}else{	
					if(form.group_name.value == ""){
						alert("<?php echo JText::_('BLBE_JSMDNOT18'); ?>");
					}
					else if(form.s_id.value == 0){
						alert("<?php echo JText::_('BLBE_SELSEASN'); ?>");
					}
				}
			}else{
				submitform( pressbutton );
					return;
			}
		}	
		
		
		//-->
		</script>
		<form action="index.php?option=com_joomsport&tmpl=component" method="post" name="adminForm" id="adminForm">
		
		<table>
			<tr>
				<td width="150">
					<?php echo JText::_('BLBE_GROUPNAME'); ?>
                                        <?php
                                        if(count($lists['languages'])){
                                        
                                            echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                        }?>
                                </td>
				<td>
					<input type="text" name="group_name" value="<?php echo htmlspecialchars($row->group_name);?>" maxlength="255" onKeyPress="return disableEnterKey(event);" />
                                        <?php
                                        if(count($lists['languages'])){

                                            echo '<div class="jsTranslationContainer">';
                                            foreach (($lists['languages']) as $value) {
                                                echo '<div class="jsTranslationDiv">';
                                                $translation = '';

                                                if(isset($lists['translation']) && isset($lists['translation'][$value]['group_name'])){
                                                    $translation = htmlspecialchars($lists['translation'][$value]['group_name'], ENT_QUOTES);
                                                }
                                                echo '<input type="text" maxlength="255" size="60" name="translation['.$value.'][group_name]" value="'.addslashes($translation).'" />';
                                                echo '  ' . $value;
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                        }
                                        ?>
                                </td>
			</tr>
			
			
			
		</table>
		<?php
        //if($row->id && $row->s_id){
        ?>
		<table>
			<tr>
				<td width="150">
					
					<?php echo $lists['single'] ? JText::_('BLBE_ADDPARTIC') : JText::_('BLBE_ADDTEAMS'); ?>
				</td>
				<td width="150">
                                    <div class="selectedlistdescr"><?php echo JText::_('BLBE_AVAILABLE')?></div>
					<?php echo $lists['teams'];?>
				</td>
				<td valign="middle" width="60" align="center">
					<input class="btn" type="button" style="cursor:pointer;" value=">>" onClick="javascript:JS_addSelectedToList('adminForm','teams_id','teams_seasons');" /><br />
					<input class="btn" type="button" style="cursor:pointer;" value="<<" onClick="javascript:JS_delSelectedFromList('adminForm','teams_seasons','teams_id');" />
				</td>
				<td >
                                    <div class="selectedlistdescr"><?php echo JText::_('BLBE_SELECTED')?></div>
					<?php echo $lists['teams2'];?>
				</td>
			</tr>
		</table>
                    <input type="button" class="btn" value="<?php echo JText::_('BLBE_SAVE')?>" onclick="submitbutton('group_apply');" />
		<?php
        //}
        ?>
		
		<input type="hidden" name="option" value="com_joomsport" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo $row->id?>" />
		<input type="hidden" name="boxchecked" value="0" />
                <input type="hidden" name="s_id" value="<?php echo $lists['sid'];?>" />
                
		<?php echo JHTML::_('form.token'); ?>
	</form>