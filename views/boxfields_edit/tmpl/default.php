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
			 if(pressbutton == 'boxfields_apply' || pressbutton == 'boxfields_save' || pressbutton == 'boxfields_save_new'){
                                if(form.name.value != '' && !reg.test(form.name.value)){
					
					
						submitform( pressbutton );
						return;
						
				}else{
					getObj('fldname').style.border = "1px solid red";
					alert("<?php echo JText::_('BLBE_JSMDNOT19');?>");
					
					
				}
			}else{
				submitform( pressbutton );
					return;
			}			
		}	
		function boxfield_hide(){
                    if(jQuery('input[name="complex"]:checked').val() == '1'){
                        jQuery('.jshideforcomposite').hide();
                    }else{                        
                        jQuery('.jshideforcomposite').show();
                        boxfield_type_hide();
                    }    
                }
                function boxfield_type_hide(){
                    if(jQuery('select[name="ftype"]').val() == '1'){
                        jQuery('.jshideforboxtype').show();
                        jQuery('.jshideforplayerevents').hide();
                    } else if (jQuery('select[name="ftype"]').val() == '2') {
                        jQuery('.jshideforboxtype').hide();
                        jQuery('.jshideforplayerevents').show();
                    } else {
                        jQuery('.jshideforboxtype').hide();
                        jQuery('.jshideforplayerevents').hide();
                    }
                }
                
                jQuery( document ).ready(function() {
                    boxfield_hide();
                    boxfield_type_hide();
                });    
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
					<?php echo JText::_('BLBE_BOX_COMPOSITE'); ?>
				</td>
				<td>
                                    <div class="controls"><fieldset class="radio btn-group"><?php echo $this->lists['complex'];?></fieldset></div>
                                </td>
			</tr>
                        <tr class="jshideforcomposite">
				<td width="250">
					<?php echo JText::_('BLBE_BOX_SELECT_PARENT'); ?>
				</td>
				<td>
                                    <?php echo $this->lists['parent'];?>
                                </td>
			</tr>
                        <tr class="jshideforcomposite">
				<td width="250">
                                    <?php echo JText::_('BLBE_BOX_TYPE'); ?>
				</td>
				
                                <td>
                                    <?php echo $this->lists['calctype'];?>
                                </td>
			</tr>
                        <tr class="jshideforcomposite jshideforplayerevents">
				<td width="250">
                                    <?php echo JText::_('BLBE_PLAYEREVENT'); ?>
				</td>
				
                                <td>
                                    <?php echo $this->lists['player_events'];?>
                                </td>
			</tr>
            <tr class="jshideforcomposite jshideforboxtype">
				<td width="250">
					<?php echo JText::_('BLBE_BOX_DEPEND'); ?>
				</td>
				<td>
                    <?php echo $this->lists['depend1'];?>
                    <?php echo $this->lists['calc'];?>
                    <?php echo $this->lists['depend2'];?>
                </td>
			</tr>
            <tr class="">
                <td width="250">
                    <?php echo JText::_('BLBE_BOX_MULTIPLEBY'); ?>
                </td>
                <td>
                    <input type="number" min="0" step="0.1" name="options[multipleby]" value="<?=(isset($this->lists['coptions']['multipleby'])?$this->lists['coptions']['multipleby']:"")?>" />
                </td>
            </tr>
            <?php
            if($this->lists['efs2']){
            ?>
            <tr class="jshideforcomposite">
				<td width="250">
					<?php echo JText::_('BLBE_BOX_EFS'); ?>
				</td>
				<td>
                    <?php echo $this->lists['efs2'];?>
                </td>
			</tr>
            <?php
            }
            ?>
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
                    
                </table>
                <table class="table">
                    <tr>
                        <th><?php echo JText::_('BLBE_BOX_PAGE'); ?></th>
                        <th><?php echo JText::_('BLBE_BOX_DISPLAYCOL'); ?></th>
                        <th><?php echo JText::_('BLBE_BOX_SHOWTOTAL'); ?></th>
                    </tr>
                    <tr>
                        <td><?php echo JText::_('BLBE_BOX_PAGE_MATCH'); ?></td>
                        <td>
                            <div class="controls">
                                <fieldset class="radio btn-group">
                                    <div class="controls">
                                        <?php
                                        $checkedyes = (isset($this->lists['coptions']['matchpage']['display_col']) && $this->lists['coptions']['matchpage']['display_col'] == "0") ? false: true;
                                        ?>
                                        <label for="matchpage_col0" id="matchpage_col0-lbl" class="radio btn">

                                            <input type="radio" name="options[matchpage][display_col]" id="matchpage_col0" value="0" <?php echo $checkedyes?"":'checked="checked"';?> class="inputbox"><?php echo JText::_('JNO') ?>
                                        </label>
                                        <label for="matchpage_col1" id="matchpage_col1-lbl" class="radio btn">

                                            <input type="radio" name="options[matchpage][display_col]" id="matchpage_col1" value="1" <?php echo $checkedyes?'checked="checked"':"";?> class="inputbox"><?php echo JText::_('JYES') ?>
                                        </label>
                                    </div>

                                </fieldset>
                            </div> 
                        </td>
                        <td>
                            <div class="controls">
                                <fieldset class="radio btn-group">
                                    <div class="controls">
                                        <?php
                                        $checkedyes = (isset($this->lists['coptions']['matchpage']['display_total']) && $this->lists['coptions']['matchpage']['display_total'] == "0") ? false: true;
                                        ?>
                                        <label for="matchpage_tot0" id="matchpage_tot0-lbl" class="radio btn">

                                            <input type="radio" name="options[matchpage][display_total]" id="matchpage_tot0" value="0" <?php echo $checkedyes?"":'checked="checked"';?> class="inputbox"><?php echo JText::_('JNO') ?>
                                        </label>
                                        <label for="matchpage_tot1" id="matchpage_tot1-lbl" class="radio btn">

                                            <input type="radio" name="options[matchpage][display_total]" id="matchpage_tot1" value="1" <?php echo $checkedyes?'checked="checked"':"";?> class="inputbox"><?php echo JText::_('JYES') ?>
                                        </label>
                                    </div>

                                </fieldset>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo JText::_('BLBE_BOX_PAGE_TEAM'); ?></td>
                        <td>
                            <div class="controls">
                                <fieldset class="radio btn-group">
                                    <div class="controls">
                                        <?php
                                        $checkedyes = (isset($this->lists['coptions']['teampage']['display_col']) && $this->lists['coptions']['teampage']['display_col'] == "0") ? false: true;
                                        ?>
                                        <label for="teampage_col0" id="teampage_col0-lbl" class="radio btn">

                                            <input type="radio" name="options[teampage][display_col]" id="teampage_col0" value="0" <?php echo $checkedyes?"":'checked="checked"';?> class="inputbox"><?php echo JText::_('JNO') ?>
                                        </label>
                                        <label for="teampage_col1" id="teampage_col1-lbl" class="radio btn">

                                            <input type="radio" name="options[teampage][display_col]" id="teampage_col1" value="1" <?php echo $checkedyes?'checked="checked"':"";?> class="inputbox"><?php echo JText::_('JYES') ?>
                                        </label>
                                    </div>

                                </fieldset>
                            </div>
                        </td>
                        <td>
                            <div class="controls">
                                <fieldset class="radio btn-group">
                                    <div class="controls">
                                        <?php
                                        $checkedyes = (isset($this->lists['coptions']['teampage']['display_total']) && $this->lists['coptions']['teampage']['display_total'] == "0") ? false: true;
                                        ?>
                                        <label for="teampage_tot0" id="teampage_tot0-lbl" class="radio btn">

                                            <input type="radio" name="options[teampage][display_total]" id="teampage_tot0" value="0" <?php echo $checkedyes?"":'checked="checked"';?> class="inputbox"><?php echo JText::_('JNO') ?>
                                        </label>
                                        <label for="teampage_tot1" id="teampage_tot1-lbl" class="radio btn">

                                            <input type="radio" name="options[teampage][display_total]" id="teampage_tot1" value="1" <?php echo $checkedyes?'checked="checked"':"";?> class="inputbox"><?php echo JText::_('JYES') ?>
                                        </label>
                                    </div>

                                </fieldset>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo JText::_('BLBE_BOX_PAGE_PLAYER'); ?></td>
                        <td>
                            <div class="controls">
                                <fieldset class="radio btn-group">
                                    <div class="controls">
                                        <?php
                                        $checkedyes = (isset($this->lists['coptions']['playerpage']['display_col']) && $this->lists['coptions']['playerpage']['display_col'] == "0") ? false: true;
                                        ?>
                                        <label for="playerpage_col0" id="playerpage_col0-lbl" class="radio btn">

                                            <input type="radio" name="options[playerpage][display_col]" id="playerpage_col0" value="0" <?php echo $checkedyes?"":'checked="checked"';?> class="inputbox"><?php echo JText::_('JNO') ?>
                                        </label>
                                        <label for="playerpage_col1" id="playerpage_col1-lbl" class="radio btn">

                                            <input type="radio" name="options[playerpage][display_col]" id="playerpage_col1" value="1" <?php echo $checkedyes?'checked="checked"':"";?> class="inputbox"><?php echo JText::_('JYES') ?>
                                        </label>
                                    </div>

                                </fieldset>
                            </div>
                        </td>
                        <td>
                            <div class="controls">
                                <fieldset class="radio btn-group">
                                    <div class="controls">
                                        <?php
                                        $checkedyes = (isset($this->lists['coptions']['playerpage']['display_total']) && $this->lists['coptions']['playerpage']['display_total'] == "0") ? false: true;
                                        ?>
                                        <label for="playerpage_tot0" id="playerpage_tot0-lbl" class="radio btn">

                                            <input type="radio" name="options[playerpage][display_total]" id="playerpage_tot0" value="0" <?php echo $checkedyes?"":'checked="checked"';?> class="inputbox"><?php echo JText::_('JNO') ?>
                                        </label>
                                        <label for="playerpage_tot1" id="playerpage_tot1-lbl" class="radio btn">

                                            <input type="radio" name="options[playerpage][display_total]" id="playerpage_tot1" value="1" <?php echo $checkedyes?'checked="checked"':"";?> class="inputbox"><?php echo JText::_('JYES') ?>
                                        </label>
                                    </div>

                                </fieldset>
                            </div>
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