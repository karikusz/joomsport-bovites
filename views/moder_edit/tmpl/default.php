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
				var srcListName = 'teams_season';
					var srcList = eval( 'form.' + srcListName );
					var srcLen = srcList.length;
					for (var i=0; i < srcLen; i++) {
						srcList.options[i].selected = true;
					} 
					submitform( pressbutton );
					return;
		}	
		</script>
		<form action="index.php?option=com_joomsport" method="post" name="adminForm" id="adminForm">
 <div class="jsrespdiv12">
    <div class="jsBepanel">
        <div class="jsBEheader">
            <?php echo JText::_('BLBE_HEAD_MODERASSIGN'); ?>
        </div>
        <div class="jsBEsettings">
            <table class="jsTableEqual"  border="0">
			<tr>
				<td>
					<?php echo JText::_('BLBE_CHOOSE_USER'); ?>
				</td>
                                <td colspan="2">
					<?php echo $lists['moder'];?>
				</td>
			</tr>
			<tr>
				<td width="150">
					<?php echo JText::_('BLBE_ADD_TEAMS'); ?>
				</td>
				<td width="150">
                                    <div class="selectedlistdescr"><?php echo JText::_('BLBE_AVAILABLE')?></div>
					<?php echo $lists['teams'];?>
				</td>
				<td valign="middle" width="60" align="center">
					<input class="btn" type="button" style="cursor:pointer;" value=">>" onClick="javascript:JS_addSelectedToList('adminForm','teams_id','teams_season');" /><br />
					<input class="btn" type="button" style="cursor:pointer;" value="<<" onClick="javascript:JS_delSelectedFromList('adminForm','teams_season','teams_id');" />
				</td>
				<td >
                                    <div class="selectedlistdescr"><?php echo JText::_('BLBE_SELECTED')?></div>
					<?php echo $lists['teams2'];?>
				</td>
			</tr>
		</table>
            </div>
        </div>
    </div> 
		<input type="hidden" name="option" value="com_joomsport" />
		<input type="hidden" name="task" value="moder_list" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_('form.token'); ?>
	</form>