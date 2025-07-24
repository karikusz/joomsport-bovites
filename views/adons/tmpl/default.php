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
require_once 'components/com_joomsport/helpers/jshtml.php';
JhtmlJshtml::showFeedback();
        ?>
	<script type="text/javascript">
		function submitaddon(){
			if(document.adminForm.addon_installer.value != ''){
				submitbutton('save_adons');
			}
		}
	</script>
	<form action="index.php?option=com_joomsport" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">

<div class="jsrespdiv12">
    <div class="jsBepanel">
        <div class="jsBEheader">
            <?php echo JText::_('BLBE_GENERAL'); ?>
        </div>
        <div class="jsBEsettings">
            <?php
            if (count($this->row)) {
                ?>
		<table class="table table-striped">
			<tr>
				<th width="2%"><?php echo JText::_('#');
                ?></th>
				<th><?php echo JText::_('BLBE_NAME');
                ?></th>
				<th><?php echo JText::_('BLBE_DESCRIPTION');
                ?></th>
				<th width="7%"><?php echo JText::_('BLBE_PUBL');
                ?></th>
				<th width="7%"><?php echo JText::_('BLBE_VERSION');
                ?></th>
			</tr>
			<?php
            for ($i = 0;$i < count($this->row);++$i) {
                echo '<tr>';
                echo '<td>';
                echo @JHTML::_('grid.checkedout',   $this->row[$i], $i);
                echo '</td>';
                echo '<td>';
                echo $this->row[$i]->title;
                echo '</td>';
                echo '<td>';
                echo $this->row[$i]->description;
                echo '</td>';
                echo '<td>';
                if (!$this->row[$i]->published) {
                    ?>
						<a title="<?php echo JText::_('BLBE_PUBLITEM');
                    ?>" onclick="return listItemTask('cb<?php echo $i?>','adons_publ')" href="javascript:void(0);">
						<img bOrder="0" alt="Unpublished" src="components/com_joomsport/img/publish_x.png"/></a>
						<?php

                } else {
                    ?>
						<a title="<?php echo JText::_('BLBE_UNPUBLITEM');
                    ?>" onclick="return listItemTask('cb<?php echo $i?>','adons_unpubl')" href="javascript:void(0);">
						<img bOrder="0" alt="Published" src="components/com_joomsport/img/tick.png"/></a>
						<?php

                }
                echo '</td>';
                echo '<td>';
                echo $this->row[$i]->version;
                echo '</td>';
                echo '</tr>';
            }
                ?>
		</table>
            <?php 
            } else {
                echo '<div class="jswarningbox"><p>';
                echo JText::sprintf('BLBE_WARN_NOADDONS', '<a target="_blank" href="http://joomsport.com/web-shop/joomsport-addons.html">', '</a>');

                echo '</p></div>';
            }

            ?>
		<br />
		<table class="table table-striped">
			<tr>
				<td>
					<input type="file" name="addon_installer" />
					<input class="btn btn-small" type="button" value="<?php echo JText::_('BLBE_UPLINSTALL');?>" style="cursor:pointer;" onClick="submitaddon();"  />
				</td>
			</tr>
		</table>
            </div>
        </div>
    </div>
		<input type="hidden" name="option" value="com_joomsport" />
		<input type="hidden" name="task" value="addons" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_('form.token'); ?>
	</form>