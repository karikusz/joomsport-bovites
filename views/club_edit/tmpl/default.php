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
        ?>
		<script type="text/javascript">
		Joomla.submitbutton = function(task) {
			submitbutton(task);
		}
		function submitbutton(pressbutton) {
			var form = document.adminForm;
            var reg = /^\s+$/;
			 if(pressbutton == 'club_apply' || pressbutton == 'club_save' || pressbutton == 'club_save_new'){
                             jQuery('input[name="jsgallery"]').val(JSON.stringify(jQuery('input[name^="filnm"]').serializeArray()));
			 	if(form.c_name.value != ''  && !reg.test(form.c_name.value)){
                                    
                                        var srcListName = 'teams_season';
                                        var srcList = eval( 'form.' + srcListName );

                                        var srcLen = srcList.length;

                                        for (var i=0; i < srcLen; i++) {
                                                        srcList.options[i].selected = true;
                                        } 

                                    
                                    
					submitform( pressbutton );
					return;
				}else{
					getObj('vname').style.border = "1px solid red";
					alert("<?php echo JText::_('BLBE_JSMDNOT22');?>");
					
					
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
					<?php echo JText::_('BLBE_CLUBN'); ?>
                                    <?php
                                    if(count($lists['languages'])){

                                        echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                    }?>
                                </td>
				<td>
					<input type="text" maxlength="255" size="60" name="c_name" id="vname" value="<?php echo htmlspecialchars($row->c_name)?>" onKeyPress="return disableEnterKey(event);" />
                                        <?php
                                        if(count($lists['languages'])){

                                            echo '<div class="jsTranslationContainer">';
                                            foreach (($lists['languages']) as $value) {
                                                echo '<div class="jsTranslationDiv">';
                                                $translation = '';

                                                if(isset($lists['translation']) && isset($lists['translation'][$value]['c_name'])){
                                                    $translation = htmlspecialchars($lists['translation'][$value]['c_name'], ENT_QUOTES);
                                                }
                                                echo '<input type="text" maxlength="255" size="60" name="translation['.$value.'][c_name]" value="'.addslashes($translation).'"/>';
                                                echo '  ' . $value;
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                        }
                                        ?>
                                </td>
			</tr>
			
                        <tr>
                            <td>
                                    
                                <?php echo JText::_('BLBE_ADDTEAMS'); ?>
                                                        
                            </td>
                            <td>
                                <table  border="0">
                                        <tr>

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
                            </td>
                        </tr>
			
			
			
			<tr>
				<td width="200">
					<?php echo JText::_('BLBE_CLUBDESCR'); ?>
                                    <?php
                                    if(count($lists['languages'])){

                                        echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                    }?>
				</td>
				<td>
					<?php echo $editor->display('c_descr',  htmlspecialchars($row->c_descr, ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore'));  ?>
                                    <?php
                                    if(count($lists['languages'])){
                                        echo '<div class="jsTranslationContainer">';
                                        foreach (($lists['languages']) as $value) {
                                            echo '<div class="jsTranslationDiv">';
                                            $translation = '';
                                            echo $value;
                                            if(isset($lists['translation']) && isset($lists['translation'][$value]['c_descr'])){
                                                $translation = $lists['translation'][$value]['c_descr'];
                                            }
                                            echo $editor->display('translation['.$value.'][c_descr]',  htmlspecialchars($translation, ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore'));
                                            
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
               
                    
                    
		
		<input type="hidden" name="option" value="com_joomsport" />
		<input type="hidden" name="task" value="club_edit" />
		<input type="hidden" name="id" value="<?php echo $row->id?>" />
		<input type="hidden" name="cid[]" value="<?php echo $row->id?>" />
		<input type="hidden" name="boxchecked" value="0" />
                <input type="hidden" name="jsgallery" value="" />
		<?php echo JHTML::_('form.token'); ?>
	</form>