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

?>
<script type="text/javascript">
Joomla.submitbutton = function(task) {
        submitbutton(task);
}
function submitbutton(pressbutton) {
        var form = document.adminForm;
         if(pressbutton == 'tour_apply' || pressbutton == 'tour_save' || pressbutton == 'tour_save_new'){
                var reg=/^\s+$/;
                if(form.name.value && !reg.test(form.name.value)){
                        submitform( pressbutton );
                        return;
                }else{
                        getObj('trname').style.border = "1px solid red";
                        alert("<?php echo JText::_('BLBE_JSMDNOT1');?>");


                }
        }else{
                submitform( pressbutton );
                        return;
        }			
}	
function delete_logo(){
        getObj("logoiddiv").innerHTML = '';
}

</script>
<form action="index.php?option=com_joomsport" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div class="jsrespdiv8">
    <div class="jsBepanel">
        <div class="jsBEheader">
            <?php echo JText::_('BLBE_HEAD_EDIT_TOURN'); ?>
        </div>
        <div class="jsBEsettings">
            <table class="jsTableEqual">
                    <tr>
                            <td width="160">
                                    <?php echo JText::_('BLBE_TOURNAMENTNAME'); ?>
                                <?php
                                if(count($lists['languages'])){

                                    echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                }?>
                            </td>
                            <td>
                                    <input type="text" maxlength="255" size="60" name="name" id="trname" value="<?php echo htmlspecialchars($this->row->name);?>" onKeyPress="return disableEnterKey(event);" />
                                    <?php
                                    if(count($lists['languages'])){
                                        
                                        echo '<div class="jsTranslationContainer">';
                                        foreach (($lists['languages']) as $value) {
                                            echo '<div class="jsTranslationDiv">';
                                            $translation = '';
                                            
                                            if(isset($lists['translation']) && isset($lists['translation'][$value]['name'])){
                                                $translation = htmlspecialchars($lists['translation'][$value]['name'], ENT_QUOTES);
                                            }
                                            echo '<input type="text" maxlength="255" size="60" name="translation['.$value.'][name]" value="'.addslashes($translation).'" onKeyPress="return disableEnterKey(event);" />';
                                            echo '  ' . $value;
                                            echo '</div>';
                                        }
                                        echo '</div>';
                                    }
                                    ?>
                            </td>
                    </tr>

                    <tr>
                            <td width="160">
                                    <?php echo JText::_('BLBE_TOURNMODE'); ?>
                                                                    </td>
                            <td>
                                <div class="controls">
                                    <fieldset class="radio btn-group">
                                        <?php echo $this->lists['t_single'];?>
                                    </fieldset>
                                </div>
                                    
                            </td>
                    </tr>
                    <!--tr>
                            <td width="100">
                                    <?php //echo JText::_( 'BLBE_TOURNTYPE' ); ?>
                                                                    </td>
                            <td>
                                    <?php //echo $this->lists['tourn_type'];?>
                                <?php
                                $chk = $this->row->tournament_type ? false : true;
                                ?>
                                <div style="float:left; margin-right: 20px;">
                                    <input style="margin-top: 0px;" id="tournament_type_0" type="radio" name="tournament_type" value="0" <?php echo $chk ? 'checked' : '';?> />
                                    <label for="tournament_type_0" style="display:inline-block;">
                                        <b><?php echo JText::_('BLBE_TOURTYPEMATCH');?></b>
                                    </label>
                                    <br />
                                    <?php echo JText::_('BLBE_TOURTYPEMATCH_DESC');?>
                                </div>
                                <div style="float:left;">
                                    <input style="margin-top: 0px;" id="tournament_type_1" type="radio" name="tournament_type" value="1" <?php echo $chk ? '' : 'checked';?> />
                                    <label for="tournament_type_1" style="display:inline-block;">
                                        <b><?php echo JText::_('BLBE_TOURNTYPERACING');?></b>
                                    </label>
                                    <br />
                                    <?php echo JText::_('BLBE_TOURNTYPERACING_DESC');?>
                                </div>
                            </td>
                    </tr-->
                    <tr>
                            <td valign="top">
                                    <?php echo JText::_('BLBE_TOURN_LOGO'); ?>
                                                                    </td>
                            <td>
                                    <input type="file" name="t_logo"  id="logo"/>
                                    
                                    <div id="logoiddiv">
                                    <?php

                                    if ($this->row->logo && is_file('../media/bearleague/'.$this->row->logo)) {
                                        echo '<img class="thumbnail" width="100" src="'.JURI::base().'../media/bearleague/'.$this->row->logo.'">';
                                        echo '<input type="hidden" name="istlogo" value="1" />';
                                        echo '<input type="hidden" name="uplLogo" value="'.$this->row->logo.'" />';
                                        ?>
                                            <a href="javascript:void(0);" title="<?php echo JText::_('BLBE_REMOVE');
                                        ?>" onClick="javascript:delete_logo();"><img src="<?php echo JURI::base();
                                        ?>components/com_joomsport/img/publish_x.png" title="Remove" /></a>
                                            
                                    <?php

                                    }
                                    ?>
                                    </div>
                            </td>
                    </tr>
                    <tr>
                            <td>
                                    <?php echo JText::_('BLBE_ABOUT_TOURN'); ?>
                                <?php
                                if(count($lists['languages'])){

                                    echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                }?>
                            </td>
                            <td>
                                <?php echo $editor->display('descr',  htmlspecialchars($this->row->descr, ENT_QUOTES), '100%', '200', '6', '2', array('pagebreak', 'readmore'));  ?>
                                <?php
                                    if(count($lists['languages'])){
                                        echo '<div class="jsTranslationContainer">';
                                        foreach (($lists['languages']) as $value) {
                                            echo '<div class="jsTranslationDiv">';
                                            $translation = '';
                                            echo $value;
                                            if(isset($lists['translation']) && isset($lists['translation'][$value]['descr'])){
                                                $translation = $lists['translation'][$value]['descr'];
                                            }
                                            echo $editor->display('translation['.$value.'][descr]',  htmlspecialchars($translation, ENT_QUOTES), '100%', '200', '6', '2', array('pagebreak', 'readmore'));
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
</div>
<div class="jsrespdiv4 jsrespmarginleft2">
    <div class="jsBepanel">
        <div class="jsBEheader">
            <?php echo JText::_('JGLOBAL_FIELDSET_PUBLISHING'); ?>
        </div>
        <div class="jsBEsettings">
            <table>
                <tr>
                        <td width="100">
                                <?php echo JText::_('JSTATUS'); ?>
                        </td>
                        <td>
                                <div class="controls"><fieldset class="radio btn-group"><?php echo $this->lists['published'];?></fieldset></div>
                        </td>
                </tr>
            </table>
        </div>
    </div>    
</div>    

<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $this->row->id?>" />
<input type="hidden" name="boxchecked" value="0" />
<?php echo JHTML::_('form.token'); ?>
</form>

                
