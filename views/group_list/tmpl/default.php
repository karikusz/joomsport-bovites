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
$sort_way = $this->lists['sortway'];
$sort_field = $this->lists['sortfield'];
    JHTML::_('behavior.tooltip');
if (count($rows)) {
    $i = 0;
    foreach ($rows as $row) {
        ?>
        <tr class="ui-state-default">
            <td>
                <span class="sortable-handler" style="cursor: move;">
                        <span class="icon-menu"></span>
                </span>
                <input type="hidden" name="groupId[]" value="<?php echo $row->id?>" />
            </td>
            <td><?php echo @JHTML::_('grid.checkedout',   $row, $i);
        ?></td>
            <td>    
                <a class="modal" title="Select" href="index.php?option=com_joomsport&amp;task=group_add&amp;sid=<?php echo $row->s_id?>&amp;cid[]=<?php echo $row->id?>&amp;tmpl=component" rel="{handler: 'iframe', size: {x: 800, y: 450},onClose:function(){groupClose();}}">

                    <?php echo $row->group_name?>
                </a>
            </td>    
        </tr>
        <?php
        ++$i;
    }
}
die();
