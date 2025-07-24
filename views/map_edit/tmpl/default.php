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
            var reg = /^\s+$/;
			 if(pressbutton == 'map_apply' || pressbutton == 'map_save' || pressbutton == 'map_save_new'){
			 	if(form.m_name.value == ''){
			 		getObj("m_name").style.border = "1px solid red";
					alert('<?php echo JText::_('BLBE_JSMDNOT200'); ?>');
			 	}else{
                     if(reg.test(form.m_name.value) == true){
                         getObj("m_name").style.border = "1px solid red";
                         alert('<?php echo JText::_('BLBE_JSMDNOT200'); ?>');
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
		
		function delete_logo(){
			getObj("logoiddiv").innerHTML = '';
		}



		
		//-->
		</script>

		<script language="javascript" type="text/javascript">
		<!--
		function imposeMaxLength(Object, MaxLen)
		{
		  return (Object.value.length <= MaxLen);
		}
		-->
		</script> 
		<?php
        if (!($row)) {
            echo "<div id='system-message'>".JText::_('BLBE_NOITEMS').'</div>';
        }
        ?>
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
					<?php echo JText::_('BLBE_MAPNAME'); ?>
                                    <?php
                                    if(count($lists['languages'])){

                                        echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                    }?>
					
				</td>
				<td>
					<input type="text" maxlength="255" size="60" name="m_name" id="m_name" value="<?php echo htmlspecialchars($row->m_name)?>" onKeyPress="return disableEnterKey(event);" />
                                        <?php
                                        if(count($lists['languages'])){

                                            echo '<div class="jsTranslationContainer">';
                                            foreach (($lists['languages']) as $value) {
                                                echo '<div class="jsTranslationDiv">';
                                                $translation = '';

                                                if(isset($lists['translation']) && isset($lists['translation'][$value]['m_name'])){
                                                    $translation = htmlspecialchars($lists['translation'][$value]['m_name'], ENT_QUOTES);
                                                }
                                                echo '<input type="text" maxlength="255" size="60" name="translation['.$value.'][m_name]" value="'.addslashes($translation).'"/>';
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
					<?php echo JText::_('BLBE_MAPIMAGE'); ?>
					
				</td>
				<td>
					<input type="file" name="t_logo" id="logo"/>
					<br />
					<div id="logoiddiv">
					<?php

                    if ($row->map_img && is_file('../media/bearleague/'.$row->map_img)) {
                        $imgsize = getimagesize('../media/bearleague/'.$row->map_img);
                        if ($imgsize[0] > 200) {
                            $width = 200;
                        } else {
                            $width = $imgsize[0];
                        }

                        echo '<img src="'.JURI::base().'../media/bearleague/'.$row->map_img.'" width="'.$width.'" />';
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
                <td width="200" valign="middle">
                    <?php echo JText::_('BLBE_MAPS_SEPARATE_EVENTS'); ?>
                </td>
                <td>
                    <div class="controls">
                        <fieldset class="radio btn-group">
                            <?php echo $lists['separate_events'];?>
                        </fieldset>
                    </div>
                </td>
            </tr>
            <tr>
                <td width="200" valign="middle" class="blockHSAutomatic">
                    <?php echo JText::_('BLBE_MAPS_DIVIDE'); ?>
                </td>
                <td class="blockHSAutomatic">
                    <?php echo JText::_('BLBE_MAPS_DIVIDE_FROM'); ?>
                    <input type="number" name="time_from" step="1" min="0" value="<?php echo (($row->time_from != '')?$row->time_from:"")?>" />
                    <?php echo JText::_('BLBE_MAPS_DIVIDE_TO'); ?>
                    <input type="number" name="time_to" step="1" min="1" value="<?php echo (($row->time_to)?$row->time_to:"")?>" />
                </td>
            </tr>
			<tr>
				<td width="100">
					<?php echo JText::_('BLBE_DESCRIPTION'); ?>
				</td>
				<td>
					<textarea name="map_descr" cols="60" rows="10" onkeypress="return imposeMaxLength(this, 150);"><?php echo htmlspecialchars($row->map_descr, ENT_QUOTES);?></textarea>
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
<script>
    function jshpSHAuto(){

        if(jQuery("input[name='separate_events']:checked").val() == '2'){
            jQuery(".blockHSAutomatic").show();
        }else{
            jQuery(".blockHSAutomatic").hide();
        }
    }
    jQuery("input[name='separate_events']").on("click",function(){

        jshpSHAuto();
    });

    jshpSHAuto();
</script>