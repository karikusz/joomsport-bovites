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
			 if(pressbutton == 'venue_apply' || pressbutton == 'venue_save' || pressbutton == 'venue_save_new'){
                              jQuery('input[name="jsgallery"]').val(JSON.stringify(jQuery('input[name^="filnm"]').serializeArray()));

                            if(form.v_name.value != ''  && !reg.test(form.v_name.value)){
					submitform( pressbutton );
					return;
				}else{
					getObj('vname').style.border = "1px solid red";
					alert("<?php echo JText::_('BLBE_JSMDNOT20');?>");
					
					
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
            <?php echo JText::_('BLBE_VENUE'); ?>
        </div>
        <div class="jsBEsettings">		
		<table>
			<tr>
				<td width="200">
					<?php echo JText::_('BLBE_VENNAME'); ?>
                                    <?php
                                    if(count($lists['languages'])){

                                        echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                    }?>
				</td>
				<td>
					<input type="text" maxlength="255" size="60" name="v_name" id="vname" value="<?php echo htmlspecialchars($row->v_name)?>" onKeyPress="return disableEnterKey(event);" />
                                        <?php
                                        if(count($lists['languages'])){

                                            echo '<div class="jsTranslationContainer">';
                                            foreach (($lists['languages']) as $value) {
                                                echo '<div class="jsTranslationDiv">';
                                                $translation = '';

                                                if(isset($lists['translation']) && isset($lists['translation'][$value]['v_name'])){
                                                    $translation = htmlspecialchars($lists['translation'][$value]['v_name'], ENT_QUOTES);
                                                }
                                                echo '<input type="text" maxlength="255" size="60" name="translation['.$value.'][v_name]" value="'.addslashes($translation).'"/>';
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
					<?php echo JText::_('BLBE_VADDRESS'); ?>
                                    <?php
                                    if(count($lists['languages'])){

                                        echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                    }?>
				</td>
				<td>
					<input type="text" maxlength="255" size="60" name="v_address" value="<?php echo htmlspecialchars($row->v_address)?>" />
                                        <?php
                                        if(count($lists['languages'])){

                                            echo '<div class="jsTranslationContainer">';
                                            foreach (($lists['languages']) as $value) {
                                                echo '<div class="jsTranslationDiv">';
                                                $translation = '';

                                                if(isset($lists['translation']) && isset($lists['translation'][$value]['v_address'])){
                                                    $translation = htmlspecialchars($lists['translation'][$value]['v_address'], ENT_QUOTES);
                                                }
                                                echo '<input type="text" maxlength="255" size="60" name="translation['.$value.'][v_address]" value="'.addslashes($translation).'"/>';
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
					<?php echo JText::_('BLBE_VCOORDY'); ?>
					<span class="editlinktip hasTip" title="<?php echo JText::_('BLBE_VCOORDY'); ?>::<?php echo JText::_('BLBE_TT_VCOORDY');?>"><img class="imgQuest" src="components/com_joomsport/img/quest.png" bOrder="0" /></span> 
				</td>
				<td>
					
					<input type="text" maxlength="255" size="60" name="v_coordx" value="<?php echo htmlspecialchars($row->v_coordx)?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('BLBE_VCOORDX'); ?>
					<span class="editlinktip hasTip" title="<?php echo JText::_('BLBE_VCOORDX'); ?>::<?php echo JText::_('BLBE_TT_VCOORDX');?>"><img class="imgQuest" src="components/com_joomsport/img/quest.png" bOrder="0" /></span> 
				</td>
				<td>
					<input type="text" maxlength="255" size="60" name="v_coordy" value="<?php echo htmlspecialchars($row->v_coordy)?>" />
				</td>
			</tr>
			
			
			<tr>
				<td width="200">
					<?php echo JText::_('BLBE_VDESCR'); ?>
                                    <?php
                                    if(count($lists['languages'])){

                                        echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                    }?>
				</td>
				<td>
					<?php echo $editor->display('v_descr',  htmlspecialchars($row->v_descr, ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore'));  ?>
                                    <?php
                                    if(count($lists['languages'])){
                                        echo '<div class="jsTranslationContainer">';
                                        foreach (($lists['languages']) as $value) {
                                            echo '<div class="jsTranslationDiv">';
                                            $translation = '';
                                            echo $value;
                                            if(isset($lists['translation']) && isset($lists['translation'][$value]['v_descr'])){
                                                $translation = $lists['translation'][$value]['v_descr'];
                                            }
                                            echo $editor->display('translation['.$value.'][v_descr]',  htmlspecialchars($translation, ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore'));
                                            
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
                    echo ImagesHelper::loaderUI($lists['photos'], $row->v_defimg);
                ?>
            </div>
        </div>
    </div>
		
		
		<input type="hidden" name="option" value="com_joomsport" />
		<input type="hidden" name="task" value="venue_edit" />
		<input type="hidden" name="id" value="<?php echo $row->id?>" />
		<input type="hidden" name="cid[]" value="<?php echo $row->id?>" />
		<input type="hidden" name="boxchecked" value="0" />
                <input type="hidden" name="jsgallery" value="" />
		<?php echo JHTML::_('form.token'); ?>
	</form>
                
                
                