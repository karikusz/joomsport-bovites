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
        require_once 'components/com_joomsport/helpers/jshtml.php';
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
JHTML::_('behavior.modal', 'a.modal');
        ?>
		
		<script type="text/javascript">
		<!--
		Joomla.submitbutton = function(task) {
			submitbutton(task);
		}
                var step_conteiner = null;
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'matchday_add') {
                            window.SqueezeBox.initialize({
                                    onClose:function(){
                                    }
                            });
                            if(!step_conteiner){
                                step_conteiner = $('vdfv');
                                
                            }
                            //var e = e.clone(); 
                            SqueezeBox.open(step_conteiner, {
                            handler: 'adopt',
                            size: {x: 400, y: 400}
                            });
                            if(!step_conteiner){
                                step_conteiner = step_conteiner.clone();
                            }
				
			}else if(pressbutton == 'matchday_del'){
				if(confirm("<?php echo JText::_('BLBE_MDDELCONFIRM');?>")){
					submitform( pressbutton );
					return;
				}
			}else{
				submitform( pressbutton );
					return;
			}
		}
                function jsStepSubmit(){
                    var form = document.adminForm;
                    
                    if(jQuery("#s_id_step").val() != 0){
                        form.s_id.value = jQuery("#s_id_step").val();
                        form.t_type.value = jQuery("#t_type_step").val();
                        form.task.value = 'matchday_add';
                        form.submit();
                        return;
                    }else{	
                        alert("<?php echo JText::_('BLBE_SELTOURNAMENT');?>");	
                    }
                }
                
		//-->
		</script>
                <form action="index.php?option=com_joomsport" method="post" name="adminForm" id="adminForm">
		<?php
        if (!($lists['totteams'])) {
            JhtmlJshtml::createmess('matchday_add');
            echo '<input type="hidden" name="s_id" value="0" />';
        } else {
            ?>
	
        <div id="filter-bar" class="btn-toolbar">
            <div class="btn-group pull-right hidden-phone">
                <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');
            ?></label>
				<?php echo $pagination->getLimitBox();
            ?>
            </div>
            <div class="btn-group pull-right"><?php echo $this->lists['tourn'];
            ?><?php //echo $this->lists['t_type'];?></div>
        </div>
		<table class="table table-striped">
		<thead>
			<tr>
				<th width="2%" align="left">
					<?php echo JText::_('#');
            ?>
				</th>
				<th width="2%">
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
				</th>
				<th class="title">
					<?php 
                        $sort_way_on = ($sort_field == 'm.m_name' && $sort_way == 'ASC') ? 'DESC' : 'ASC';

            ?>
					<a href="#" onclick="javascript:JSPRO_order('m.m_name','<?php echo $sort_way_on;
            ?>');" >
						<?php echo JText::_('BLBE_MATCHDAY');
            ?>
                        <?php
                        if ($sort_field == 'm.m_name') {
                            $sort_img = $sort_way == 'ASC' ? 'icon-arrow-down' : 'icon-arrow-up';
                            echo '<i class="'.$sort_img.'"/></i>';
                        }
            ?>
					</a>

				</th>
				<th width="13%" nowrap="nowrap" class="ordering">
					<?php 
                        $sort_way_on = ($sort_field == 'm.ordering' && $sort_way == 'ASC') ? 'DESC' : 'ASC';

            ?>
					<a href="#" onclick="javascript:JSPRO_order('m.ordering','<?php echo $sort_way_on;
            ?>');" class="order-lbl">
						<?php echo JText::_('BLBE_ORDER');
            ?>
                        <?php
                        if ($sort_field == 'm.ordering') {
                            $sort_img = $sort_way == 'ASC' ? 'icon-arrow-down' : 'icon-arrow-up';
                            echo '<i class="'.$sort_img.'"/></i>';
                        }
            ?>
					</a>
					<?php echo JHTML::_('jshtml.order',  $rows, 'filesave.png', 'matchday_Ordering');
            ?>
				</th>
				<th class="title">
					<?php 
                        $sort_way_on = ($sort_field == 't.name' && $sort_way == 'ASC') ? 'DESC' : 'ASC';

            ?>
					<a href="#" onclick="javascript:JSPRO_order('t.name','<?php echo $sort_way_on;
            ?>');" >
						<?php echo JText::_('BLBE_TOURNAMENT');
            ?>
                        <?php
                        if ($sort_field == 't.name') {
                            $sort_img = $sort_way == 'ASC' ? 'icon-arrow-down' : 'icon-arrow-up';
                            echo '<i class="'.$sort_img.'"/></i>';
                        }
            ?>
					</a>
				</th>
				
			</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="13">
				<?php echo $this->page->getListFooter();
            ?>
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
                    $link = JRoute::_('index.php?option=com_joomsport&task=matchday_edit&cid[]='.$row->id);
                    $checked = @JHTML::_('grid.checkedout',   $row, $i);
            //$published 	= JHTML::_('grid.published', $row, $i);
            ?>
			<tr class="<?php echo "row$k";
                    ?>">
				<td>
					<?php echo $this->page->getRowOffset($i);
                    ?>
				</td>
				<td>
					<?php echo $checked;
                    ?>
				</td>
				<td>
					<?php
                        echo '<a href="'.$link.'">'.$row->m_name.'</a>';
                    ?>
				</td>
				<td class="order">
					<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;
                    ?>" class="text_area" style="width:30px;text-align: center" />
				</td>
				<td>
					<?php echo $row->tourn;
                    ?>
				</td>
				
			</tr>
			<?php

                }
            }
            ?>
		</tbody>
		</table>
                <?php

        }
                ?>
		<input type="hidden" name="option" value="com_joomsport" />
		<input type="hidden" name="task" value="matchday_list" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="sortfield" value="<?php echo $sort_field; ?>" />
		<input type="hidden" name="sortway" value="<?php echo $sort_way; ?>" />
                <input type="hidden" name="t_type" value="" />
                
		<?php echo JHTML::_('form.token'); ?>
	</form>
        <div style="display:none;" >
            <div id="vdfv" class="center">
                <div><?php echo $this->lists['tourn_step'];?></div>
                <div style="padding:10px 0px;"><?php echo $this->lists['t_type_step'];?></div>
                <div class="stepNextBtn"><input type="button" onclick="jsStepSubmit();" class="btn btn-small btn-success" value="<?php echo JText::_('JNEXT');?>" /></div>
            </div>    
        </div>