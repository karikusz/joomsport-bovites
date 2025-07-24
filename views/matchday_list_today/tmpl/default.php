	<script type="text/javascript">
		<!--
		Joomla.submitbutton = function(task) {
			submitbutton(task);
		}
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'matchday_today_save' || pressbutton == 'matchday_today_apply') {
					var extras = eval("document.adminForm['extra_time[]']");
					if(extras){
						if(extras.length){
							for(i=0;i<extras.length;i++){
								if(extras[i].checked){	
									extras[i].value = 1;	
								}else{
									extras[i].value = 0;
								}
								extras[i].checked = true;
							}
						}else{
							if(extras.checked){	
								extras.value = 1;	
							}else{
								extras.value = 0;
							}
							extras.checked = true;
						}		
					}
					
					var errortime = '';
					var mt_time = eval("document.adminForm['match_time[]']");
					if(mt_time){
						if(mt_time.length){
							for(i=0;i<mt_time.length;i++){
								var regE = /[0-2][0-9]:[0-5][0-9]/;
								if(!regE.test(mt_time[i].value) && mt_time[i].value != ''){
									errortime = '1';
									mt_time[i].style.border = "1px solid red";
								}else{
									mt_time[i].style.border = "1px solid #C0C0C0";
								}
							}
						}else{
							var regE = /[0-2][0-9]:[0-5][0-9]/;
								if(!regE.test(mt_time.value) && mt_time.value != ''){
									errortime = '1';
									mt_time.style.border = "1px solid red";
								}else{
									mt_time.style.border = "1px solid #C0C0C0";
								}
						}
					}
					
					if(errortime){
						alert("<?php echo JText::_('BLBE_JSMDNOT7'); ?>");return;
					}else{
						submitform( pressbutton );
						return;
					}
			}else{
				submitform( pressbutton );
					return;
			}
		}	
			//-->
		</script>
	<form action="index.php?option=com_joomsport" method="post" name="adminForm" id="adminForm">
            <div class="pull-left">
                <?php echo JHTML::_('calendar', $this->lists['m_date'], 'm_date', 'm_date', '%Y-%m-%d', 
                        array('class' => 'inputbox', 'size' => '20',  'maxlength' => '10', 'style' => 'width:76px', 
                            'weekNumbers' => false,
                            'singleHeader' => true,
                            'fillTable'=>false,
                            'onChange' =>'document.adminForm.submit();'));?>
            </div>
            <div style="clear:both;"></div>
<div class="jsrespdiv12">
    <div class="jsBepanel">
        <div class="jsBEheader">
            <?php echo JText::_('BLBE_MATCHTODAY'); ?>
        </div>
        <div class="jsBEsettings">		
		
		<table class="table table-striped" id="new_matches">
		
			<tr>
				<th class="title" width="20">
					
				</th>
				<th class="title" width="170">
					<?php echo JText::_('BLBE_HOMETEAM'); ?>
				</th>
                                <th class="center" width="150">
					<?php echo JText::_('BLBE_SCORE'); ?>
				</th>
				<?php if (0) {//if($lists['s_enbl_extra']){?>
				<th width="50">
					<?php echo JText::_('BLBE_ET');
    ?>
				</th>
				<?php 
} ?>
				<th class="title" width="170">
					<?php echo JText::_('BLBE_AWAYTEAM'); ?>
				</th>
				<th class="title" width="20">
					<?php echo JText::_('JSTATUS'); ?>
				</th>
				
				<!--<th class="title" width="10%">
					<?php //echo JText::_( 'BLBE_DATE' ); ?>
				</th>-->
				<th  class="title center">
					<?php echo JText::_('BLBE_TIME'); ?>
				</th>
				
				<th class="title">
					
                                </th>
			</tr>
			<?php

$curseason = '';

            if (count($this->rows)) {
                foreach ($this->rows as $curmatch) {

          //change season
                if (!($curseason == $curmatch->fullname)) {
                    echo '<tr ><td colspan="11" align="center" style="background:#CCC;font-weight:bold;font-size:14px;padding:5px;">'.$curmatch->fullname.'</td</tr>';
                }

                    $match_link = 'index.php?option=com_joomsport&amp;task=match_edit&amp;cid= '.$curmatch->id;
                    echo '<tr>';
                    echo '<td><input type="hidden" name="match_id[]" value="'.$curmatch->id.'" /></td>';
                    echo '<td><input type="hidden" name="home_team[]" value="'.$curmatch->team1_id.'" />'.$curmatch->home_team.'</td>';
                    echo '<td align="center"><input type="text" style="width:50px;" name="home_score[]" value="'.$curmatch->score1.'" size="3" maxlength="5" /> : <input type="text" style="width:50px;" name="away_score[]" value="'.$curmatch->score2.'" size="3" maxlength="5" /></td>';
                    if (0 /*$lists['s_enbl_extra']*/) {
                        echo '<td><input type="checkbox" name="extra_time[]" value="'.(($curmatch->is_extra) ? 1 : 0).'" '.(($curmatch->is_extra) ? 'checked' : '').' /></td>';
                    }
                    echo '<td><input type="hidden" name="away_team[]" value="'.$curmatch->team2_id.'" />'.$curmatch->away_team.'</td>';
                    echo '<td>'.$this->_model->getMatchStatuses($curmatch->m_played, 'match_played[]').'</td>';
                    //echo '<td>';
                        //echo JHTML::_('calendar', $curmatch->m_date, 'match_data[]', 'match_data_'.$curmatch->id, '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'12',  'maxlength'=>'10')); 

                    //echo '</td>';		
                    echo '<td class="center"><input type="text" style="width:50px;" name="match_time[]" maxlength="5" size="12" value="'.substr($curmatch->m_time, 0, 5).'" /><input type="hidden" name="match_data[]" value="'.$this->rows[0]->m_date.'" />';
                    echo '<td><a href="'.$match_link.'">'.JText::_('BLBE_MATCHDETAILS').'</a></td>';
                    echo '</tr>';

                    $curseason = $curmatch->fullname;
                }
            }
            ?>
		</table>
            </div>
        </div>
    </div>
		
		
		<input type="hidden" name="task" value="matchday_list_today" />

		<input type="hidden" name="boxchecked" value="0" />

		<?php echo JHTML::_('form.token'); ?>
		</form>
