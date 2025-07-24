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
$rows = $this->rows;

    JHTML::_('behavior.tooltip');
        ?>
		<?php
        if (!count($rows)) {
            echo "<div id='system-message'><dd class='notice'><ul>".JText::_('BLBE_NOITEMS').'</ul></dd></div>';
        }
        ?>
		<form action="index.php?option=com_joomsport" method="post" name="adminForm" id="adminForm">
		<div id="filter-bar" class="btn-toolbar">
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $pagination->getLimitBox(); ?>
			</div>
		</div>
		<table class="adminlist">
		<thead>
			<tr>
				<th width="2%">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows);?>);" />
				</th>
				<th class="title">
					<?php echo JText::_('BLBE_BET_TEMPLATE'); ?>
				</th>
				<th class="title">
					<?php echo JText::_('BLBE_BET_DESC'); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="13">
				<?php echo $this->page->getListFooter(); ?>
			</td>
		</tr>
		</tfoot>
		<tbody>
		<?php
        $k = 0;
        if (count($rows)) {
            for ($i = 0, $n = count($rows); $i < $n; ++$i) {
                $row = $rows[$i];
                JFilterOutput::objectHtmlSafe($row);
                $link = JRoute::_('index.php?option=com_joomsport&task=template_edit&cid[]='.$row->id);
                $checked = @JHTML::_('grid.checkedout',   $row, $i);
            //$published 	= JHTML::_('grid.published', $row, $i);
            ?>
			<tr class="<?php echo "row$k";
                ?>">
				<td>
					<?php echo $checked;
                ?>
				</td>
				<td>
					<?php
                        echo '<a href="'.$link.'">'.$row->name.'</a>';
                ?>
				</td>
				<td>
					<?php echo $row->description;
                ?>
				</td>
				
				
			</tr>
			<?php

            }
        }
        ?>
		</tbody>
		</table>
		<input type="hidden" name="option" value="com_joomsport" />
		<input type="hidden" name="task" value="template_list" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_('form.token'); ?>
	</form>