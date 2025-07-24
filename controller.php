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
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
if (isset($_GET['tmpl']) && $_GET['tmpl'] == 'component') {
} else {
    $doc = JFactory::getDocument();
    JHtml::_('jquery.ui', array('core', 'sortable'));
    $doc->addCustomTag('<script type="text/javascript" src="components/com_joomsport/js/main.js"></script>');
    $doc->addCustomTag('<script type="text/javascript" src="components/com_joomsport/js/knockcomplex.js"></script>');
    $doc->addCustomTag('<link rel="stylesheet" type="text/css" href="components/com_joomsport/css/be_joomsport.css" />');
    $doc->addCustomTag('<script type="text/javascript" src="components/com_joomsport/js/chosen.order.jquery.min.js"></script>');
    
    //$doc->addCustomTag( '<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">');
    JHtml::_('bootstrap.tooltip');
    JHtml::_('behavior.multiselect');
    JHtml::_('behavior.combobox');
    JHtml::_('dropdown.init');
    JHtml::_('formbehavior.chosen', 'select[size!=10]');
}
global $joomsportVersion;
$extension = JTable::getInstance('extension');
$id = $extension->find(array('element' => 'com_joomsport'));
$extension->load($id);
$componentInfo = json_decode($extension->manifest_cache, true);

$joomsportVersion = $componentInfo['version'];

class JoomSportController extends JControllerLegacy
{
    protected $js_prefix = '';
    protected $mainframe = null;
    protected $option = 'com_joomsport';
    protected $curver = '';

    public function __construct()
    {
        global $joomsportVersion;
        parent::__construct();
        $this->curver = $joomsportVersion;
        $this->mainframe = JFactory::getApplication();
        $curtask = JRequest::getCmd('task', 'tour_list');

        $tmpl = JRequest::getVar('tmpl', '', 'get', 'string');
        if ($tmpl != 'component' && $curtask != 'getformat' && $curtask != 'getformatkn') {
            $this->JS_LMenu($curtask);
        }
        $this->js_SetPrefix();
        $this->js_GetDBTables();
    }

    private function js_SetPrefix()
    {
        $this->js_prefix = '';
        $db = JFactory::getDBO();
        $query = "SELECT name FROM #__bl_addons WHERE published='1'";
        $db->setQuery($query);
        $addon = $db->loadResult();

        if ($addon) {
            $this->js_prefix = $addon;
        }

    }
    private function js_GetDBTables()
    {
        $path = JPATH_SITE.'/components/com_joomsport/tables/';
        if ($this->js_prefix) {
            if (is_file($path.$this->js_prefix.'.php')) {
                require $path.$this->js_prefix.'.php';
            } else {
                require $path.'default.php';
            }
        } else {
            require $path.'default.php';
        }
    }
    private function js_Model($name)
    {
        $newclass = false;
        $path = dirname(__FILE__).'/models/';
        if ($this->js_prefix) {
            if (is_file($path.$this->js_prefix.'/'.$name.'.php')) {
                require $path.$this->js_prefix.'/'.$name.'.php';
                $newclass = true;
            } else {
                if (file_exists($path.'default/'.$name.'.php')) {
                    require $path.'default/'.$name.'.php';
                }
            }
        } else {
            if (file_exists($path.'default/'.$name.'.php')) {
                require $path.'default/'.$name.'.php';
            }
        }

        return $newclass;
    }
    private function js_Layout($task)
    {
        $path = dirname(__FILE__).'/views/'.$task;

        require $path.'/view.html.php';
    }
    
    public function getAddonsMenu(){
        $db = JFactory::getDBO();
        $query = "SELECT options FROM #__bl_addons WHERE published='1' AND options != ''";
        $db->setQuery($query);
        $addons = $db->loadColumn();
        $html = '';
        for($intA=0;$intA<count($addons);$intA++){
            $options = json_decode($addons[$intA], true);
            if(isset($options['langugesBE'])){
                $lang = JFactory::getLanguage();
                $extension = $options['langugesBE'];

                $reload = true;
                $lang->load($extension);
            }

            if(isset($options['menus']['menu'])){
                $html .= '<li>';
                $html .= '<h3 class="'.((isset($options['menus']['menugroupico']) && $options['menus']['menugroupico'])?$options['menus']['menugroupico']:'').'">'.JText::_($options['menus']['menugroup']).'</h3>';
                $html .= '<ul>';
                if(isset($options['menus']['menu'][0])){
                    foreach($options['menus']['menu'] as $menu){
                        $html .= '<li>';
                        $html .= '<div class="jslm_item">';

                        $html .= '<a href="index.php?option=com_joomsport&task='.$menu['task'].'">';
                        if(isset($menu['menuico']) && $menu['menuico']){
                            $html .= '<img src="components/com_joomsport/img/'.$menu['menuico'].'" />';
                        }
                        $html .= JText::_($menu['title']).'</a>';
                        $html .= '</div>';
                        $html .= '</li>';
                    }
                }else{
                    $menu = $options['menus']['menu'];
                    $html .= '<li>';
                    $html .= '<div class="jslm_item">';

                    $html .= '<a href="index.php?option=com_joomsport&task='.$menu['task'].'">';
                    if(isset($menu['menuico']) && $menu['menuico']){
                        $html .= '<img src="components/com_joomsport/img/'.$menu['menuico'].'" />';
                    }
                    $html .= JText::_($menu['title']).'</a>';
                    $html .= '</div>';
                    $html .= '</li>';
                }
                $html .= '</ul>';
                $html .= '</li>';
            }
            
            
        }
        return $html;
    }

    //jommsport menu
    public function JS_LMenu($curtask)
    {
        $db = JFactory::getDBO();
        $query = "SELECT name FROM #__bl_addons WHERE published='1' AND name='Payments'";
        $db->setQuery($query);
        $is_payments = $db->loadResult();



        ?>
                <div class="table-responsive">    
                    <div class="jsrespdiv2 jsrespmargin2 <?php echo ($curtask == 'matchday_edit' || $curtask == 'matchday_add')?" jseditmd":""?>">

						
						<div class="jslmenu">
							<div class="jlsm_header">
								JOOMSPORT MENU
                                                                <div class="respmenucoll">
                                                                    <img id="collapsemenujs" src="components/com_joomsport/img/nav-header-button.png" />
                                                                </div>    
							</div>
							
							<div class="jlsm_cen">
                                                            <div>
                                                                <ul>
                                                                        <li>
								<?php
                                                                        $collapsed = false;
        $taskarray = array(
                                                                            'tour_list',
                                                                            'tour_edit',
                                                                            'season_list',
                                                                            'season_edit',
                                                                            'matchday_list',
                                                                            'matchday_edit',
                                                                            'match_edit',
                                                                            'matchday_list_today',
                                                                        );
        if (!$curtask || in_array($curtask, $taskarray)) {
            $collapsed = true;
        }
        $menuar = array();
        $menuar[] = array('task' => 'tour_list', 'ico' => 'tournament_icon.png', 'text' => JText::_('BLBE_TOURNAMENT'), 'pages' => array('tour_list', 'tour_edit'));
        $menuar[] = array('task' => 'season_list', 'ico' => 'season_icon.png', 'text' => JText::_('BLBE_SEASON'), 'pages' => array('season_list', 'season_edit'));
        $menuar[] = array('task' => 'matchday_list', 'ico' => 'match16.png', 'text' => JText::_('BLBE_MATCHDAY'), 'pages' => array('matchday_list', 'matchday_edit', 'match_edit'));
        $menuar[] = array('task' => 'matchday_list_today', 'ico' => 'match16.png', 'text' => JText::_('BLBE_MATCHTODAY'), 'pages' => array('matchday_list_today'));
        ?>
                                                                            <h3 class="headbg1"><?php echo JText::_('BLBE_MENU_STRUCTURE');
        ?></h3>
                                                                            <ul>
                                                                            <?php 
                                                                            foreach ($menuar as $sub) {
                                                                                $active_class = '';
                                                                                if (in_array($curtask, $sub['pages'])) {
                                                                                    $active_class = ' jslmactive';
                                                                                }
                                                                                ?>
                                                                                <li>
                                                                                    <div class="jslm_item<?php echo $active_class;
                                                                                ?>">
                                                                                        <a href="index.php?option=com_joomsport&task=<?php echo $sub['task'];
                                                                                ?>"><img src="components/com_joomsport/img/<?php echo $sub['ico'];
                                                                                ?>" /><?php echo $sub['text'];
                                                                                ?></a>
                                                                                    </div>
                                                                                </li>
                                                                            <?php 
                                                                            }
        ?>
                                                                            </ul>    
                                                                        </li>
                                                                        <li>
                                                                    <?php
                                                                        $collapsed = false;
        $taskarray = array(
                                                                            'club_list',
                                                                            'club_edit',
                                                                            'team_list',
                                                                            'team_edit',
                                                                            'player_list',
                                                                            'player_edit',
                                                                            'venue_list',
                                                                            'venue_edit',
                                                                            'payments_list',
                                                                            'person_category_list',
                                                                            'person_list',
                                                                        );
        if (!$curtask || in_array($curtask, $taskarray)) {
            $collapsed = true;
        }

        $query = "SELECT cfg_value FROM #__bl_config WHERE cfg_name='enbl_club'";
        $db->setQuery($query);
        $enbl_club = $db->loadResult();
        $menuar = array();
        if ($enbl_club) {
            $menuar[] = array('task' => 'club_list', 'ico' => 'club_icon.png', 'text' => JText::_('BLBE_CLUBS'), 'pages' => array('club_list', 'club_edit'));
        }
        $menuar[] = array('task' => 'team_list', 'ico' => 'team_icon.png', 'text' => JText::_('BLBE_MENTEAMS'), 'pages' => array('team_list', 'team_edit'));

        $menuar[] = array('task' => 'player_list', 'ico' => 'players_icon.png', 'text' => JText::_('BLBE_MENPL'), 'pages' => array('player_list', 'player_edit'));
        $query = "SELECT cfg_value FROM #__bl_config WHERE cfg_name='unbl_venue'";
        $db->setQuery($query);
        $enbl_venue = $db->loadResult();
        if ($enbl_venue == '1') {
            $menuar[] = array('task' => 'venue_list', 'ico' => 'venue_icon.png', 'text' => JText::_('BLBE_VENUES'), 'pages' => array('venue_list', 'venue_edit'));
        }
        if ($is_payments) {
            $menuar[] = array('task' => 'payments_list', 'ico' => 'payments_icon.png', 'text' => JText::_('BLBE_MENU_PAYMENT'), 'pages' => array('payments_list'));
        }
        $menuar[] = array('task' => 'person_category_list', 'ico' => 'persons_category.png', 'text' => JText::_('BLBE_PERSON_CATEGORIES'), 'pages' => array('person_category_list', 'person_category_edit'));
        $menuar[] = array('task' => 'person_list', 'ico' => 'persons.png', 'text' => JText::_('BLBE_PERSONS'), 'pages' => array('person_list', 'person_edit'));
        
        ?>
                                                                        <h3 class="headbg2"><?php echo JText::_('BLBE_MENU_PDATA');
        ?></h3>
                                                                            <ul>
                                                                            <?php 
                                                                            foreach ($menuar as $sub) {
                                                                                $active_class = '';
                                                                                if (in_array($curtask, $sub['pages'])) {
                                                                                    $active_class = ' jslmactive';
                                                                                }
                                                                                ?>
                                                                                <li>
                                                                                    <div class="jslm_item<?php echo $active_class;
                                                                                ?>">
                                                                                        <a href="index.php?option=com_joomsport&task=<?php echo $sub['task'];
                                                                                ?>"><img src="components/com_joomsport/img/<?php echo $sub['ico'];
                                                                                ?>" /><?php echo $sub['text'];
                                                                                ?></a>
                                                                                    </div>
                                                                                </li>
                                                                            <?php 
                                                                            }
        ?>
                                                                            </ul>    
                                                                        </li>
                                                                        <li>
                                                                <?php 
                                                                        $collapsed = false;
        $taskarray = array(
                                                                            'event_list',
                                                                            'event_edit',
                                                                            'map_list',
                                                                            'map_edit',
                                                                            'moder_list',
                                                                            'moder_edit',
                                                                            'fields_list',
                                                                            'fields_edit',
                                                                            'boxfields_list',
                                                                            'boxfields_edit',
                                                                            'config',
                                                                            'adons',
                                                                            'help',
                                                                        );
        if (!$curtask || in_array($curtask, $taskarray)) {
            $collapsed = true;
        }

        $menuar = array();
        $menuar[] = array('task' => 'event_list', 'ico' => 'events_icon.png', 'text' => JText::_('BLBE_MENEV'), 'pages' => array('event_list', 'event_edit'));
        $menuar[] = array('task' => 'boxfields_list', 'ico' => 'box_stat_BE.png', 'text' => JText::_('BLBE_BOXMENAF'), 'pages' => array('boxfields_list', 'boxfields_edit'));
        
        $menuar[] = array('task' => 'map_list', 'ico' => 'stages_icon.png', 'text' => JText::_('BLBE_MAPS'), 'pages' => array('map_list', 'map_edit'));

        $menuar[] = array('task' => 'moder_list', 'ico' => 'moderators_icon.png', 'text' => JText::_('BLBE_MODERATORS'), 'pages' => array('moder_list', 'moder_edit'));
        $menuar[] = array('task' => 'fields_list', 'ico' => 'extrafields_icon.png', 'text' => JText::_('BLBE_MENAF'), 'pages' => array('fields_list', 'fields_edit'));
        
        $menuar[] = array('task' => 'config', 'ico' => 'configuration_icon.gif', 'text' => JText::_('BLBE_MENCONF'), 'pages' => array('config'));
        $menuar[] = array('task' => 'adons', 'ico' => 'addons_icon.png', 'text' => JText::_('BLBE_ADDONS'), 'pages' => array('adons'));
        $menuar[] = array('task' => 'help', 'ico' => 'about16.png', 'text' => JText::_('BLBE_MENHLP'), 'pages' => array('help'));

        ?>
                                                                            <h3 class="headbg3"><?php echo JText::_('BLBE_MENU_SETTINGS');
        ?></h3>
                                                                            <ul>
                                                                            <?php 
                                                                            foreach ($menuar as $sub) {
                                                                                $active_class = '';
                                                                                if (in_array($curtask, $sub['pages'])) {
                                                                                    $active_class = ' jslmactive';
                                                                                }
                                                                                ?>
                                                                                <li>
                                                                                    <div class="jslm_item<?php echo $active_class;
                                                                                ?>">
                                                                                        <a href="index.php?option=com_joomsport&task=<?php echo $sub['task'];
                                                                                ?>"><img src="components/com_joomsport/img/<?php echo $sub['ico'];
                                                                                ?>" /><?php echo $sub['text'];
                                                                                ?></a>
                                                                                    </div>
                                                                                </li>
                                                                            <?php 
                                                                            }
        ?>
                                                                            </ul>    
                                                                        </li>
                                                                        <?php
                                                                        echo $this->getAddonsMenu();
                                                                        ?>
                                                                    </ul>
                                                                </div>
							</div>
							<div class="jlsm_bot">
								
								<div class="jslm_version">
									<?php echo JText::_('BLBE_CURVERS');
        ?>&nbsp;<span class="jslatverred" id="span_survr"><?php echo $this->curver;
        ?></span>
								</div>
								
							</div>
						</div>
						
					
					<?php
                    $db = JFactory::getDBO();
        $query = "SELECT name FROM #__bl_addons WHERE published='1' AND name='betting'";
        $db->setQuery($query);
        $is_betting = $db->loadResult();
        ?>
					<?php if ($is_betting):?>
						<div style="width:220px; margin-top:10px">
							<div class="jslmenu">
								<div class="jlsm_header"><h3>Betting Menu</h3></div>
								<div class="jlsm_cen">
									<div class="jslm_item">
											<a href="index.php?option=com_joomsport&task=template_list"><?php echo JText::_('BLBE_BET_TMLIST')?></a>
									</div>
									<div class="jslm_item">
											<a href="index.php?option=com_joomsport&task=betting_users"><?php echo JText::_('BLBE_BET_USERS')?></a>
									</div>
									<div class="jslm_item">
											<a href="index.php?option=com_joomsport&task=betting_cash_requests_list"><?php echo JText::_('BLBE_BET_CASH_REQUESTS')?></a>
									</div>
									<div class="jslm_item">
											<a href="index.php?option=com_joomsport&task=betting_points_requests_list"><?php echo JText::_('BLBE_BET_POINTS_REQUEST')?></a>
									</div>                                                
									<div class="jslm_item">
											<a href="index.php?option=com_joomsport&task=betting_log"><?php echo JText::_('BLBE_BET_LOG')?></a>
									</div>
									<div class="jslm_item">
											<a href="index.php?option=com_joomsport&task=betting_config"><?php echo JText::_('BLBE_BET_CONFIG')?></a>
									</div>
								</div>
								<div class="jlsm_bot">
								</div>
							</div>
						</div>
					<?php endif;
        ?>
				</div>	
		<?php

    }

    public function display($cachable = false, $urlparams = false)
    {
        $document = JFactory::getDocument();
        $db = JFactory::getDBO();
        $vName = JRequest::getCmd('task', 'tour_list');

        $editmodes = 1;
        if (substr_count($vName, 'add')) {
            $vName = str_replace('add', 'edit', $vName);
            $editmodes = 0;
        }
        $newclass = $this->js_Model($vName);

        if ($vName == 'autogeneration') {
            $query = "SELECT name FROM #__bl_addons WHERE name='matchgeneration' AND published='1'";
            $db->setQuery($query);
            $addon = $db->loadResult();
            if (!$addon) {
                echo "<div class='jsrespdiv10'>";
                echo '<br/><br/><br/><br/>This option should allow you to generate Matches for your competitions in couple of clicks!<br/> You need to buy <a href="http://joomsport.com/web-shop/joomsport-addons.html?utm_source=jsproBE&utm_medium=links&utm_campaign=buyGM">Matches Generator add-on</a> to add the functionality to your product version.';
                echo '</div>';
                echo '</div>';

                return $this;
            }
        }

        $classname = $vName.'JSModel';
        if (class_exists($classname)) {
            if ($this->js_prefix && $newclass) {
                $classname .= '_'.$this->js_prefix;
            }
            $model = new $classname();
            if (!$editmodes) {
                $model->_mode = 0;
            }
            $this->js_Layout($vName);
            $classname_l = 'JoomsportView'.$vName;

            $layout = new $classname_l($model);

            $tpl = '';
            $path = dirname(__FILE__).'/views/'.$vName.'/tmpl/default_'.$this->js_prefix.'.php';
            if (is_file($path)) {
                $tpl = $this->js_prefix;
            }
            if (isset($_GET['tmpl']) && $_GET['tmpl'] == 'component') {
            } else {
                echo "<div class='jsrespdiv10".($vName == 'matchday_edit'?" jseditmdC":"")."'>";
            }
            $layout->display($tpl);
            if (isset($_GET['tmpl']) && $_GET['tmpl'] == 'component') {
            } else {
                echo '</div>';
            }
            echo '</div>';
        }

        return $this;
    }
    public function tour_unpublish()
    {
        $vName = 'tour_list';
        $task = 'tour_list';
        $table = '#__bl_tournament';
        $this->contr_unpublish($vName, $task, $table);
    }
    public function tour_publish()
    {
        $vName = 'tour_list';
        $task = 'tour_list';
        $table = '#__bl_tournament';
        $this->contr_publish($vName, $task, $table);
    }
    public function tour_del()
    {
        $vName = 'tour_list';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $cid = JRequest::getVar('cid', array(0), '', 'array');
        JArrayHelper::toInteger($cid, array(0));
        $model->delTourn($cid);
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=tour_list');
    }
    public function tour_save()
    {
        $vName = 'tour_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveTourn();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=tour_list');
    }
    public function tour_save_new()
    {
        $vName = 'tour_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveTourn();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=tour_edit&cid[]=0');
    }
    public function tour_apply()
    {
        $vName = 'tour_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveTourn();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=tour_edit&cid[]='.$model->_id);
    }

    public function season_unpublish()
    {
        $vName = 'season_list';
        $task = 'season_list';
        $table = '#__bl_seasons';
        $this->contr_unpublish($vName, $task, $table);
    }
    public function season_publish()
    {
        $vName = 'season_list';
        $task = 'season_list';
        $table = '#__bl_seasons';
        $this->contr_publish($vName, $task, $table);
    }
    public function season_del()
    {
        $vName = 'season_list';
        $task = 'season_list';
        $table = '#__bl_seasons';
        $this->contr_del($vName, $task, $table);
    }

    public function season_copy()
    {
        $vName = 'season_list';
        $task = 'season_list';
        $table = '#__bl_seasons';
        $this->contr_copy($vName, $task, $table);
    }
    public function season_save()
    {
        $vName = 'season_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveSeason();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=season_list');
    }
    public function season_save_new()
    {
        $vName = 'season_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveSeason();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=season_edit&cid[]=0');
    }
    public function season_apply()
    {
        $vName = 'season_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveSeason();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=season_edit&cid[]='.$model->_id);
    }
    public function season_ordering()
    {
        $vName = 'season_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->orderSeason();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=season_list');
    }
    //club
    public function club_save()
    {
        $vName = 'club_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveClub(); ///�������� ������� saveClub
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=club_list');
    }
    public function club_save_new()
    {
        $vName = 'club_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveClub();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=club_edit&cid[]=0');
    }
    public function club_apply()
    {
        $vName = 'club_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveClub();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=club_edit&cid[]='.$model->_id);
    }
    public function club_del()
    {
        $vName = 'club_list';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $cid = JRequest::getVar('cid', array(0), '', 'array');
        JArrayHelper::toInteger($cid, array(0));
        $model->delClub($cid);
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=club_list');
    }
    //teams

    public function team_del()
    {
        $vName = 'team_list';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $cid = JRequest::getVar('cid', array(0), '', 'array');
        JArrayHelper::toInteger($cid, array(0));
        $model->delTeam($cid);
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=team_list');
    }
    public function team_save()
    {
        $vName = 'team_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveTeam();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=team_list');
    }
    public function team_save_new()
    {
        $vName = 'team_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveTeam();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=team_edit&cid[]=0');
    }
    public function team_apply()
    {
        $vName = 'team_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveTeam();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=team_edit&cid[]='.$model->_id);
    }
    //players

    public function player_del()
    {
        $vName = 'player_list';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $cid = JRequest::getVar('cid', array(0), '', 'array');
        JArrayHelper::toInteger($cid, array(0));
        $model->delPlayer($cid);
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=player_list');
    }
    public function player_save()
    {
        $vName = 'player_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->savePlayer();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=player_list');
    }
    public function player_save_new()
    {
        $vName = 'player_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->savePlayer();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=player_edit&cid[]=0');
    }
    public function player_apply()
    {
        $vName = 'player_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->savePlayer();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=player_edit&cid[]='.$model->_id);
    }
    //matchday

    public function matchday_del()
    {
        $this->js_Model('matchday_edit');
        $classname = 'matchday_editJSModel';
        $model = new $classname();
        $cid = JRequest::getVar('cid', array(0), '', 'array');
        JArrayHelper::toInteger($cid, array(0));
        $model->deleteMday($cid);

        $this->mainframe->redirect('index.php?option='.$this->option.'&task=matchday_list');
    }
    public function matchday_save()
    {
        $vName = 'matchday_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveMday();
        if (isset($_GET['tmpl']) && $_GET['tmpl'] == 'component') {
            echo $model->_id;
            exit();
        }
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=matchday_list');
    }
    public function matchday_today_save()
    {
        $vName = 'matchday_list_today';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveMdayToday();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=matchday_list_today');
    }

    public function matchday_save_new()
    {
        $vName = 'matchday_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveMday();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=matchday_edit&cid[]=0');
    }
    public function matchday_apply()
    {
        $vName = 'matchday_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveMday();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=matchday_edit&cid[]='.$model->_id);
    }
    public function matchday_apply_complex()
    {
        $vName = 'matchday_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname(false);
        $model->saveMday();
        exit();
    }
    public function matchday_ordering()
    {
        $vName = 'matchday_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->orderMDay();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=matchday_list');
    }
    public function matchday_addmatch()
    {
        $vName = 'matchday_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->SaveMatch();
        exit();
        //$this->mainframe->redirect( 'index.php?option='.$this->option.'&task=matchday_list');
    }

    //match
    public function match_save()
    {
        $vName = 'match_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveMatch();
        $mdid = $model->getMdID();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=matchday_edit&cid[]='.$mdid);
    }
    public function match_apply()
    {
        $vName = 'match_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveMatch();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=match_edit&cid[]='.$model->_id);
    }
    //events

    public function event_del()
    {
        $this->js_Model('event_edit');
        $classname = 'event_editJSModel';
        $model = new $classname();
        $cid = JRequest::getVar('cid', array(0), '', 'array');
        JArrayHelper::toInteger($cid, array(0));
        $model->deleteEvent($cid);

        $this->mainframe->redirect('index.php?option='.$this->option.'&task=event_list');
    }
    public function event_save()
    {
        $vName = 'event_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveEvent();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=event_list');
    }
    public function event_save_new()
    {
        $vName = 'event_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveEvent();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=event_edit&cid[]=0');
    }
    public function event_apply()
    {
        $vName = 'event_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveEvent();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=event_edit&cid[]='.$model->_id);
    }
    public function event_ordering()
    {
        $vName = 'event_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->orderEvent();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=event_list');
    }
    //group

    public function group_del()
    {
        $vName = 'group_list';
        $task = 'group_list';
        $table = '#__bl_groups';
        
        
        //recalculate table
        $sid = JRequest::getVar('s_id', 0, '', 'int');
        $cid = JRequest::getVar('cid', array(0), '', 'array');
        JArrayHelper::toInteger($cid, array(0));
        if ($sid && count($cid)) {
            $db = JFactory::getDBO();
            $query = 'DELETE FROM #__bl_season_table '
                .' WHERE season_id = '.$sid
                .' AND group_id IN ('.implode(',',$cid).') ';
            $db->setQuery($query);
            $db->query();
        }
        
        $this->contr_del($vName, $task, $table);
        
        
    }
    public function group_save()
    {
        $vName = 'group_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveGroup();
        ?>
                <script>window.parent.SqueezeBox.close();</script>    
                <?php
        //$this->mainframe->redirect( 'index.php?option='.$this->option.'&task=group_list');
    }
    public function group_save_new()
    {
        $vName = 'group_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveGroup();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=group_edit&cid[]=0');
    }
    public function group_apply()
    {
        $vName = 'group_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveGroup();
        ?>
                <script>window.parent.SqueezeBox.close();</script>    
                <?php
        //$this->mainframe->redirect( 'index.php?option='.$this->option.'&task=group_edit&cid[]='.$model->_id);
    }
    public function group_ordering()
    {
        $vName = 'group_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->orderGroup();
        //$this->mainframe->redirect( 'index.php?option='.$this->option.'&task=group_list');
    }
    //moderators

    public function moder_del()
    {
        $this->js_Model('moder_edit');
        $classname = 'moder_editJSModel';
        $model = new $classname();
        $cid = JRequest::getVar('cid', array(0), '', 'array');
        JArrayHelper::toInteger($cid, array(0));
        $model->deleteModer($cid);
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=moder_list');
    }
    public function moder_save()
    {
        $vName = 'moder_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveModer();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=moder_list');
    }
    public function moder_save_new()
    {
        $vName = 'moder_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveModer();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=moder_edit&cid[]=0');
    }
    public function moder_apply()
    {
        $vName = 'moder_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveModer();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=moder_edit&cid[]='.$model->_id);
    }

    //map

    public function map_del()
    {
        $vName = 'map_list';
        $task = 'map_list';
        $table = '#__bl_maps';
        $this->contr_del($vName, $task, $table);
    }
    public function map_save()
    {
        $vName = 'map_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveMap();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=map_list');
    }
    public function map_save_new()
    {
        $vName = 'map_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveMap();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=map_edit&cid[]=0');
    }
    public function map_apply()
    {
        $vName = 'map_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveMap();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=map_edit&cid[]='.$model->_id);
    }
    ///countries
    public function apply_countr()
    {
        $vName = 'list_countr';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveCountr();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=list_countr');
    }
    public function del_countr()
    {
        $vName = 'list_countr';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->deleteCountr();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=list_countr');
    }
    //fields

    public function fields_del()
    {
        $vName = 'fields_list';
        $task = 'fields_list';
        $table = '#__bl_extra_filds';
        $this->contr_del($vName, $task, $table);
    }
    public function fields_save()
    {
        $vName = 'fields_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveFields();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=fields_list');
    }
    public function fields_save_new()
    {
        $vName = 'fields_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveFields();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=fields_edit&cid[]=0');
    }
    public function fields_apply()
    {
        $vName = 'fields_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveFields();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=fields_edit&cid[]='.$model->_id);
    }
    public function saveorder()
    {
        $vName = 'fields_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->orderFields();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=fields_list');
    }
    public function fields_unpublish()
    {
        $vName = 'fields_list';
        $task = 'fields_list';
        $table = '#__bl_extra_filds';
        $this->contr_unpublish($vName, $task, $table);
    }
    public function fields_publish()
    {
        $vName = 'fields_list';
        $task = 'fields_list';
        $table = '#__bl_extra_filds';
        $this->contr_publish($vName, $task, $table);
    }
    
    //boxfields

    public function boxfields_del()
    {
        $vName = 'boxfields_list';
        $task = 'boxfields_list';
        $table = '#__bl_box_fields';
        $this->contr_del($vName, $task, $table);
    }
    public function boxfields_save()
    {
        $vName = 'boxfields_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveFields();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=boxfields_list');
    }
    public function boxfields_save_new()
    {
        $vName = 'boxfields_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveFields();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=boxfields_edit&cid[]=0');
    }
    public function boxfields_apply()
    {
        $vName = 'boxfields_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveFields();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=boxfields_edit&cid[]='.$model->_id);
    }
    public function boxfield_Ordering()
    {
        $vName = 'boxfields_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->orderFields();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=boxfields_list');
    }
    public function boxfields_unpublish()
    {
        $vName = 'boxfields_list';
        $task = 'boxfields_list';
        $table = '#__bl_box_fields';
        $this->contr_unpublish($vName, $task, $table);
    }
    public function boxfields_publish()
    {
        $vName = 'boxfields_list';
        $task = 'boxfields_list';
        $table = '#__bl_box_fields';
        $this->contr_publish($vName, $task, $table);
    }

    //venue

    public function venue_del()
    {
        $vName = 'venue_list';
        $task = 'venue_list';
        $table = '#__bl_venue';
        $this->contr_del($vName, $task, $table);
    }
    public function venue_save()
    {
        $vName = 'venue_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveVenue();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=venue_list');
    }
    public function venue_save_new()
    {
        $vName = 'venue_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveVenue();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=venue_edit&cid[]=0');
    }
    public function venue_apply()
    {
        $vName = 'venue_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveVenue();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=venue_edit&cid[]='.$model->_id);
    }
    //payments
    public function save_payments()
    {
        $vName = 'payments_list';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->savePayments();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=payments_list');
    }
    //config
    public function save_config()
    {
        $vName = 'config';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveConfig();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=config');
    }
    public function template_save()
    {
        $vName = 'template_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveTemplate();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=template_list');
    }

    public function template_apply()
    {
        $vName = 'template_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveTemplate();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=template_edit&cid[]='.$model->_id);
    }

    public function template_delete()
    {
        $vName = 'template_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->deleteTemplate();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=template_list');
    }

    //config
    public function save_betting_config()
    {
        $vName = 'betting_config';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->saveConfig();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=betting_config');
    }

    public function betting_edit_points_save()
    {
        $vName = 'betting_users';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->savePoints();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=betting_users');
    }

    public function betting_cash_requests_doapprove()
    {
        $vName = 'betting_cash_requests_approve';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->approve();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=betting_cash_requests_list');
    }

    public function betting_cash_requests_doreject()
    {
        $vName = 'betting_cash_requests_reject';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->reject();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=betting_cash_requests_list');
    }

    public function betting_cash_requests_dopostpone()
    {
        $vName = 'betting_cash_requests_postpone';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->postpone();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=betting_cash_requests_list');
    }

    public function betting_points_requests_doapprove()
    {
        $vName = 'betting_points_requests_approve';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->approve();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=betting_points_requests_list');
    }

    public function betting_points_requests_doreject()
    {
        $vName = 'betting_points_requests_reject';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->reject();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=betting_points_requests_list');
    }

    public function betting_points_requests_dopostpone()
    {
        $vName = 'betting_points_requests_postpone';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->postpone();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=betting_points_requests_list');
    }

    //help
    public function help()
    {
        JToolBarHelper::title(JText::_('BLBE_MENHLP'), 'about.png');
        ?>
			<td valign='top'>
				<?php include_once JPATH_COMPONENT.DIRECTORY_SEPARATOR.'jbl_help.php';
        ?>
				</td>
			</tr>
		</table>
		<?php

    }

    /////Menu/////
    public function season_menu()
    {
        $this->menubox('season_menu');
    }
    public function team_menu()
    {
        $this->menubox('team_menu');
    }
    public function match_menu()
    {
        $this->menubox('match_menu');
    }
    public function matchday_menu()
    {
        $this->menubox('matchday_menu');
    }
    public function player_menu()
    {
        $this->menubox('player_menu');
    }
    public function group_menu()
    {
        $this->menubox('group_menu');
    }
    public function venue_menu()
    {
        $this->menubox('venue_menu');
    }

    //addons
    public function save_adons()
    {
        $vName = 'adons';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->addonInstall();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=adons');
    }
    public function del_adons()
    {
        $vName = 'adons';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->addonDel();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=adons');
    }
    /*public function adons_unpublish(){
        $vName = 'adons';
        $task = 'adons';
        $table = '#__bl_addons';
        $this->contr_unpublish($vName,$task,$table);
    }
    public function adons_publish(){
        $vName = 'adons';
        $task = 'adons';
        $table = '#__bl_addons';
        $this->contr_publish($vName,$task,$table);
    }*/
    public function adons_unpubl()
    {
        $vName = 'adons';
        $task = 'adons';
        $table = '#__bl_addons';
        $this->contr_unpublish($vName, $task, $table);
    }
    public function adons_publ()
    {
        $vName = 'adons';
        $task = 'adons';
        $table = '#__bl_addons';
        $this->contr_publish($vName, $task, $table);
    }
    //getparcip
    public function getparcip()
    {
        $vName = 'getparcip';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
    }
    //getformat
    public function getformat()
    {
        $vName = 'getformat';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
    }
    /*function getformatkn(){
        $vName = 'getformatkn';
        $this->js_Model($vName);
        $classname = $vName."JSModel";
        $model = new $classname();
    }*/
    public function knockoutkn()
    {
        $vName = 'knockout';
        //$this->js_Model($vName);
        $path = JPATH_SITE.'/components/com_joomsport/models/default/';
        require $path.$vName.'.php';
        $classname = 'JS_Knockout';
        $model = new $classname();
        $model->getFormatkn();
    }
    public function knockoutcomplex()
    {
        $vName = 'knockout';
        //$this->js_Model($vName);
        $path = JPATH_SITE.'/components/com_joomsport/models/default/';
        require $path.$vName.'.php';
        $classname = 'JS_Knockout';
        $model = new $classname();
        echo $model->getFormatComplex();
        die();
    }
    public function knockout()
    {
        $vName = 'knockout';
        $path = JPATH_SITE.'/components/com_joomsport/models/default/';
        require $path.$vName.'.php';
        $classname = 'JS_Knockout';
        $model = new $classname();
        $model->getFormat();
        die();
    }
    
    //person category
        public function person_category_del()
    {
        $vName = 'person_category_list';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $cid = JRequest::getVar('cid', array(0), '', 'array');
        JArrayHelper::toInteger($cid, array(0));
        $model->delPersonCategory($cid);
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=person_category_list');
    }
    public function person_category_save()
    {
        $vName = 'person_category_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->savePersonCategory();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=person_category_list');
    }
    public function person_category_save_new()
    {
        $vName = 'person_category_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->savePersonCategory();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=person_category_edit&cid[]=0');
    }
    public function person_category_apply()
    {
        $vName = 'person_category_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->savePersonCategory();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=person_category_edit&cid[]='.$model->_id);
    }
    //person 
        public function person_del()
    {
        $vName = 'person_list';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $cid = JRequest::getVar('cid', array(0), '', 'array');
        JArrayHelper::toInteger($cid, array(0));
        $model->delPerson($cid);
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=person_list');
    }
    public function person_save()
    {
        $vName = 'person_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->savePerson();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=person_list');
    }
    public function person_save_new()
    {
        $vName = 'person_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->savePerson();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=person_edit&cid[]=0');
    }
    public function person_apply()
    {
        $vName = 'person_edit';
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $model->savePerson();
        $this->mainframe->redirect('index.php?option='.$this->option.'&task=person_edit&cid[]='.$model->_id);
    }

    ///mains///
    public function menubox($vName)
    {
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();

        $this->js_Layout($vName);
        $classname_l = 'JoomsportView'.$vName;

        $layout = new $classname_l($model);
        echo "<td valign='top'>";
        $layout->display();
        echo '</td></tr></table>';
    }
    public function contr_publish($vName, $task, $table)
    {
        if (!JFactory::getUser()->authorise('core.edit', 'com_joomsport')) {
            return JError::raiseError(303, '');
        }
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $cid = JRequest::getVar('cid', array(0), '', 'array');
        JArrayHelper::toInteger($cid, array(0));
        $model->js_publish($table, $cid);

        $this->mainframe->redirect('index.php?option='.$this->option.'&task='.$task);
    }
    public function contr_unpublish($vName, $task, $table)
    {
        if (!JFactory::getUser()->authorise('core.edit', 'com_joomsport')) {
            return JError::raiseError(303, '');
        }
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $cid = JRequest::getVar('cid', array(0), '', 'array');
        JArrayHelper::toInteger($cid, array(0));
        $model->js_unpublish($table, $cid);

        $this->mainframe->redirect('index.php?option='.$this->option.'&task='.$task);
    }
    public function contr_del($vName, $task, $table)
    {
        if (!JFactory::getUser()->authorise('core.delete', 'com_joomsport')) {
            return JError::raiseError(303, '');
        }
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $cid = JRequest::getVar('cid', array(0), '', 'array');
        JArrayHelper::toInteger($cid, array(0));
        $model->js_delete($table, $cid, $task);

        $this->mainframe->redirect('index.php?option='.$this->option.'&task='.$task);
    }
    public function contr_copy($vName, $task, $table)
    {
        if (!JFactory::getUser()->authorise('core.edit', 'com_joomsport')) {
            return JError::raiseError(303, '');
        }
        $this->js_Model($vName);
        $classname = $vName.'JSModel';
        $model = new $classname();
        $cid = JRequest::getVar('cid', array(0), '', 'array');
        JArrayHelper::toInteger($cid, array(0));
        $model->js_copy($table, $cid, $task);

        $this->mainframe->redirect('index.php?option='.$this->option.'&task='.$task);
    }
    public function chkvers()
    {
        echo  "<div style='float:left;width:80px;'>";
        if(function_exists('curl_exec')){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://joomsport.com/index2.php?option=com_chkversion&id=3&no_html=1&tmpl=component');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,"; 
            $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5"; 
            $header[] = "Cache-Control: max-age=0"; 
            $header[] = "Connection: keep-alive"; 
            $header[] = "Keep-Alive: 300"; 
            $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7"; 
            $header[] = "Accept-Language: en-us,en;q=0.5"; 
            $header[] = "Pragma: "; // browsers keep this blank. 
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = trim(curl_exec($ch));
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // Check if login was successful
            if ($http_code == 200) {
              echo $response;
            }else{
                echo trim(@file_get_contents('http://joomsport.com/index2.php?option=com_chkversion&id=3&no_html=1&tmpl=component'));
            }

            curl_close($ch);
        }else{
            echo trim(@file_get_contents('http://joomsport.com/index2.php?option=com_chkversion&id=3&no_html=1&tmpl=component'));
        }
        echo '</div>';
    }
    public function uploadGallery()
    {
        require_once JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'jsupload'.DIRECTORY_SEPARATOR.'UploadHandler.php';
        new UploadHandler();
        die();
    }
    public function uploadLogo()
    {
        if (isset($_FILES['logo']['name']) && $_FILES['logo']['tmp_name'] != '' && isset($_FILES['logo']['tmp_name'])) {
            $bl_filename = strtolower($_FILES['logo']['name']);
            $ext = pathinfo($_FILES['logo']['name']);
            $bl_filename = 'bl'.time().rand(0, 3000).'.'.$ext['extension'];
            $bl_filename = str_replace(' ', '', $bl_filename);
            require_once dirname(__FILE__).'/models/models.php';
            $model = new JSPRO_Models();
            if ($model->uploadFile($_FILES['logo']['tmp_name'], $bl_filename)) {
                echo '<img class="thumbnail" width="100" src="'.JURI::base().'../media/bearleague/'.$bl_filename.'">';
                echo '<input type="hidden" name="istlogo" value="1" />';
                echo '<input type="hidden" name="uplLogo" value="'.$bl_filename.'" />';
                echo '<a href="javascript:void(0);" title="'.JText::_('BLBE_REMOVE').'" onClick="javascript:delete_logo();"><img src="'.JURI::base().'components/com_joomsport/img/publish_x.png" title="Remove" /></a>';
            }
        }
        die();
    }
    public function getPlayerFieldsBySeason()
    {
        $db = JFactory::getDBO();
        $editor = JEditor::getInstance();
        $sid = JRequest::getVar('sid', 0, '', 'int');
        $player_id = JRequest::getVar('player_id', 0, '', 'int');

        if ($sid == -1) {
            $query = "SELECT DISTINCT(team_id) FROM #__bl_players_team WHERE confirmed='0' AND player_id='".$player_id."' AND season_id='".$sid."'";
            $db->setQuery($query);
            $plars = $db->loadColumn();
            $error = $db->getErrorMsg();
            if ($error) {
                return JError::raiseError(500, $error);
            }

            $query = "SELECT t.* FROM #__bl_players_team as p,#__bl_teams as t WHERE p.confirmed='0' AND p.player_join='0' AND t.id=p.team_id AND p.player_id='".$player_id."' AND p.season_id=".$sid;
            $db->setQuery($query);
            $f_inteams = $db->loadObjectList();
            $error = $db->getErrorMsg();
            if ($error) {
                return JError::raiseError(500, $error);
            }
            $lists['in_teams'] = @JHTML::_('select.genericlist',   $f_inteams, 'in_teams[]', 'class="chzn-done" size="10" multiple ondblclick="javascript:JS_addSelectedToList(\'adminForm\',\'in_teams\',\'allteams\');"', 'id', 't_name', 0);

            $selected = array();
            for ($intA = 0; $intA < count($f_inteams); ++$intA) {
                $selected[] = $f_inteams[$intA]->id;
            }
            $query = 'SELECT * FROM #__bl_teams as t '
                        .' WHERE 1=1'
                        .(count($plars) ? ' AND t.id NOT IN ('.implode(',', $plars).')' : '')
                        .(count($selected) ? ' AND t.id NOT IN ('.implode(',', $selected).')' : '')
                        .' ORDER BY t.t_name';
            $db->setQuery($query);
            $f_teams = $db->loadObjectList();
            $error = $db->getErrorMsg();
            if ($error) {
                return JError::raiseError(500, $error);
            }

            $lists['allteams'] = @JHTML::_('select.genericlist',   $f_teams, 'allteams', 'class="chzn-done" size="10" multiple ondblclick="javascript:JS_addSelectedToList(\'adminForm\',\'allteams\',\'in_teams\');"', 'id', 't_name', 0);

            ?>
                <div class="jsBepanel">
                    <div class="jsBEheader">
                        <?php echo JText::_('BLBE_GENERAL');
            ?>
                    </div>
                    <div class="jsBEsettings">
                        <table class="adminlistsNoBorder">
               
                            <tr>
                                <td width="150">
                                        <?php echo JText::_('BLBE_ASSIGNPLAYERS');
            ?>
                                </td>

                                <td width="150">
                                    <div class="selectedlistdescr"><?php echo JText::_('BLBE_AVAILABLE')?></div>
                                        <?php echo $lists['allteams'];
            ?>
                                </td>
                                <td valign="middle" width="60" align="center">
                                        <input class="btn" type="button" style="cursor:pointer;" value=">>" onClick="javascript:JS_addSelectedToList('adminForm','allteams','in_teams');" /><br />
                                        <input class="btn" type="button" style="cursor:pointer;" value="<<" onClick="javascript:JS_delSelectedFromList('adminForm','in_teams','allteams');" />
                                </td>
                                <td >
                                    <div class="selectedlistdescr"><?php echo JText::_('BLBE_SELECTED')?></div>
                                        <?php echo $lists['in_teams'];
            ?>
                                </td>

                            </tr>
                
                        </table>
                    </div>
                </div>
                <?php

        }

        if ($sid <= 0) {
            exit();
        }

        $query = 'SELECT t.* '
                    .'FROM #__bl_tournament as t'
                    .' JOIN #__bl_seasons as s ON t.id = s.t_id'
                    ." WHERE s.s_id = {$sid}";

        $db->setQuery($query);
        $tourn = $db->loadObject();
        if ($tourn->t_single == '0' && $sid > 0) {
            $query = "SELECT DISTINCT(team_id) FROM #__bl_players_team WHERE confirmed='0' AND player_id='".$player_id."' AND season_id='".$sid."'";
            $db->setQuery($query);
            $plars = $db->loadColumn();
            $error = $db->getErrorMsg();
            if ($error) {
                return JError::raiseError(500, $error);
            }

            $query = 'SELECT * FROM #__bl_teams as t JOIN #__bl_season_teams as st ON (t.id = st.team_id AND st.season_id='.$sid.') '.(count($plars) ? ' WHERE t.id NOT IN ('.implode(',', $plars).')' : '').' ORDER BY t.t_name';
            $db->setQuery($query);
            $f_teams = $db->loadObjectList();
            $error = $db->getErrorMsg();
            if ($error) {
                return JError::raiseError(500, $error);
            }

            $lists['allteams'] = @JHTML::_('select.genericlist',   $f_teams, 'allteams', 'class="chzn-done" size="10" multiple ondblclick="javascript:JS_addSelectedToList(\'adminForm\',\'allteams\',\'in_teams\');"', 'id', 't_name', 0);
            $query = "SELECT t.* FROM #__bl_players_team as p,#__bl_teams as t WHERE p.confirmed='0' AND p.player_join='0' AND t.id=p.team_id AND p.player_id='".$player_id."' AND p.season_id=".$sid;
            $db->setQuery($query);
            $f_inteams = $db->loadObjectList();
            $error = $db->getErrorMsg();
            if ($error) {
                return JError::raiseError(500, $error);
            }
            $lists['in_teams'] = @JHTML::_('select.genericlist',   $f_inteams, 'in_teams[]', 'class="chzn-done" size="10" multiple ondblclick="javascript:JS_addSelectedToList(\'adminForm\',\'in_teams\',\'allteams\');"', 'id', 't_name', 0);

            ?>
                <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_GENERAL');
            ?>
                </div>
                <div class="jsBEsettings">
                <table class="adminlistsNoBorder">
               
                            <tr>
                                    <td width="150">
                                            <?php echo JText::_('BLBE_ASSIGNPLAYERS');
            ?>
                                    </td>

                                    <td width="150">
                                        <div class="selectedlistdescr"><?php echo JText::_('BLBE_AVAILABLE')?></div>
                                            <?php echo $lists['allteams'];
            ?>
                                    </td>
                                    <td valign="middle" width="60" align="center">
                                            <input class="btn" type="button" style="cursor:pointer;" value=">>" onClick="javascript:JS_addSelectedToList('adminForm','allteams','in_teams');" /><br />
                                            <input class="btn" type="button" style="cursor:pointer;" value="<<" onClick="javascript:JS_delSelectedFromList('adminForm','in_teams','allteams');" />
                                    </td>
                                    <td >
                                        <div class="selectedlistdescr"><?php echo JText::_('BLBE_SELECTED')?></div>
                                            <?php echo $lists['in_teams'];
            ?>
                                    </td>

                            </tr>
                
                            </table>
                </div>
            </div>
                <?php

        } elseif ($tourn->t_single == '1') {
            $query = "SELECT st.bonus_point FROM  #__bl_seasons as s LEFT JOIN #__bl_tournament as t ON t.id = s.t_id, #__bl_season_players as st WHERE s.s_id = st.season_id AND st.player_id='".$player_id."' AND s.s_id = {$sid}";
            $db->setQuery($query);
            $lists['bonuses'] = $db->loadResult();
            ?>
                <div class="jsBepanel">
                    <div class="jsBEheader">
                        <?php echo JText::_('BLBE_BONUSES');
            ?>
                    </div>
                    <div class="jsBEsettings">
                        <table class="table table-striped">
                        
                        <?php
                            echo '<tr><td width="100">'.JText::_('BLBE_BONUS').'</td><td><input type="text" name="bonuses" value="'.floatval($lists['bonuses']).'" />'.'</td></tr>';
            ?>
                        </table>
                    </div>
                </div>
               <?php 
        }
    	$query = "SELECT t.id, t.t_name, n.number FROM #__bl_players_team AS p "
    	. " JOIN #__bl_teams AS t ON t.id = p.team_id "
    	. " LEFT JOIN #__bl_players_team AS n ON p.player_id = n.player_id AND n.team_id = t.id AND n.season_id='{$sid}'"
    	. " WHERE p.confirmed='0' AND p.player_join='0' AND p.player_id='{$player_id}' AND p.season_id='{$sid}'";
    	$db->setQuery($query);
        $lists['player_numbers'] = $db->loadObjectList();
    	
        
        $query = "SELECT cfg_value FROM #__bl_config WHERE cfg_name='enbl_player_system_num'";
        $db->setQuery($query);
        $enbl_player_system_num = $db->loadResult();
        
        if ($enbl_player_system_num) { ?>
    		<div class="jsBepanel efieldsseas">
    			<div class="jsBEheader">
    				<?php echo JText::_('BLBE_PLAYER_NUMBER'); ?>
    			</div>
    			<div class="jsBEsettings">
    				<table class="adminlistsNoBorder" id="playerNumbers">
    					<?php 
                                        if(count($lists['player_numbers']) ){
                                        foreach($lists['player_numbers'] as $number) {?>
    						<tr id="row<?php echo $number->id;?>">
    							<td width="100"><?php echo $number->t_name;?></td>
    							<td><input size="10" type="text" name="numbers[<?php echo $number->id;?>]" value="<?php echo $number->number;?>" /></td>
    						</tr>
                                        <?php } } ?>
    				</table>
    			</div>
    		</div>            
    	<?php }

        require_once dirname(__FILE__).'/models/models.php';
        $model = new JSPRO_Models();
        $lists['ext_fields_sr'] = $model->getAdditfields(0, $player_id, $sid);

        ?>
            <div class="jsBepanel efieldsseas">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_FIELDSBYSEASON');
        ?>
                </div>
                <div class="jsBEsettings">
                <?php

                if (count($lists['ext_fields_sr'])) {
                    ?>    
                <table class="adminlistsNoBorder">
                    <?php
                    for ($p = 0;$p < count($lists['ext_fields_sr']);++$p) {
                        if ($lists['ext_fields_sr'][$p]->field_type == '3' && !isset($lists['ext_fields_sr'][$p]->selvals)) {
                        } else {
                            if ($lists['ext_fields_sr'][$p]->season_related == 1 && $sid != -1) { //update, instead of $lists["bonuses"]
            ?>
			<tr>
				<td width="100">
					<?php echo $lists['ext_fields_sr'][$p]->name;
                                ?>
				</td>
				<td>
					<?php

                        switch ($lists['ext_fields_sr'][$p]->field_type) {

                            case '1':    echo $lists['ext_fields_sr'][$p]->selvals;
                                        break;
                            case '2':    echo $editor->display('extraf['.$lists['ext_fields_sr'][$p]->id.']',  htmlspecialchars(isset($lists['ext_fields_sr'][$p]->fvalue_text) ? ($lists['ext_fields_sr'][$p]->fvalue_text) : '', ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore'));
                                        break;
                            case '3':    echo $lists['ext_fields_sr'][$p]->selvals;
                                        break;
                            case '5':    echo $lists['ext_fields_sr'][$p]->selvals;
                                        break;           
                            case '0':
                            default:    echo '<input type="text" maxlength="255" size="60" name="extraf['.$lists['ext_fields_sr'][$p]->id.']" value="'.(isset($lists['ext_fields_sr'][$p]->fvalue) ? htmlspecialchars($lists['ext_fields_sr'][$p]->fvalue) : '').'" />';
                                        break;

                        }
                                ?>
					<input type="hidden" name="extra_ftype[<?php echo $lists['ext_fields_sr'][$p]->id?>]" value="<?php echo $lists['ext_fields_sr'][$p]->field_type?>" />
					<input type="hidden" name="extra_id[<?php echo $lists['ext_fields_sr'][$p]->id?>]" value="<?php echo $lists['ext_fields_sr'][$p]->id?>" />
				</td>
			</tr>
			
			<?php	
                            }
                            if ($lists['ext_fields_sr'][$p]->season_related == 0) { //update
                    ?>
					<tr>
						<td width="100">
							<?php echo $lists['ext_fields_sr'][$p]->name;
                                ?>
						</td>
						<td>
						<?php

                        switch ($lists['ext_fields_sr'][$p]->field_type) {

                            case '1':    echo $lists['ext_fields_sr'][$p]->selvals;
                                        break;
                            case '2':    echo $editor->display('extraf['.$lists['ext_fields_sr'][$p]->id.']',  htmlspecialchars(isset($lists['ext_fields_sr'][$p]->fvalue_text) ? ($lists['ext_fields_sr'][$p]->fvalue_text) : '', ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore'));
                                        break;
                            case '3':    echo $lists['ext_fields_sr'][$p]->selvals;
                                        break;
                            case '5':    echo $lists['ext_fields_sr'][$p]->selvals;
                                        break;           
                            case '0':
                            default:    echo '<input type="text" maxlength="255" size="60" name="extraf['.$lists['ext_fields_sr'][$p]->id.']" value="'.(isset($lists['ext_fields_sr'][$p]->fvalue) ? htmlspecialchars($lists['ext_fields_sr'][$p]->fvalue) : '').'" />';
                                        break;

                        }
                                ?>
							<input type="hidden" name="extra_ftype[<?php echo $lists['ext_fields_sr'][$p]->id?>]" value="<?php echo $lists['ext_fields_sr'][$p]->field_type?>" />
							<input type="hidden" name="extra_id[<?php echo $lists['ext_fields_sr'][$p]->id?>]" value="<?php echo $lists['ext_fields_sr'][$p]->id?>" />
						</td>
					</tr>
				<?php

                            }
                        }
                    }
        //}
            ?>
                    </table>
                    <?php

                } else {
                    echo JText::_('BLBE_EXTRAFIELDS_NOTIF');
                }
        ?>
                </div>
            </div>
            <?php


            exit();
    }
    public function getTeamFieldsBySeason()
    {
        $db = JFactory::getDBO();
        $editor = JEditor::getInstance();
        $sid = JRequest::getVar('sid', 0, '', 'int');
        $team_id = JRequest::getVar('team_id', 0, '', 'int');

        $plint = array();
        if (!empty($team_id)) {
            $query = "SELECT p.id FROM #__bl_players as p, #__bl_players_team as t WHERE t.confirmed='0' AND t.player_id=p.id AND t.team_id=".$team_id.' AND t.season_id='.$sid;
            $db->setQuery($query);
            $plint = $db->loadColumn();
        }

        $query = "SELECT CONCAT(first_name,' ',last_name) as name,id FROM #__bl_players ".(count($plint) ? ' WHERE id NOT IN ('.implode(',', $plint).')' : '').' ORDER BY first_name,last_name';
        $db->setQuery($query);
        $playerz = $db->loadObjectList();
        $is_pl[] = JHTML::_('select.option',  0, JText::_('BLBE_SELPLAYER'), 'id', 'name');
        $playerz = array_merge($is_pl, $playerz);

        $lists['player'] = JHTML::_('select.genericlist',   $playerz, 'playerz_id', 'class="chosen-select" size="1" id="playerz"', 'id', 'name', 0);
        if ($team_id) {
            $query = "SELECT p.id,CONCAT(p.first_name,' ',p.last_name) as name FROM #__bl_players as p, #__bl_players_team as t WHERE t.confirmed='0' AND t.player_join='0' AND t.season_id = ".$sid.' AND t.player_id=p.id AND t.team_id='.$team_id;
            $db->setQuery($query);
            $lists['team_players'] = $db->loadObjectList();
        } else {
            $lists['team_players'] = array();
        }
        if ($sid <= 0) {
            exit();
        }
        ?>
            <div class="jsrespdiv6">    
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_MENPL');
        ?>
                </div>
                
                <div class="jsBEsettings">
                <?php 
        echo '<table class="table table-striped" id="add_pl">';

        for ($i = 0;$i < count($lists['team_players']);++$i) {
            $pl = $lists['team_players'][$i];
            echo '<tr><td><a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="'.JText::_('BLBE_DELETE').'"><img src="components/com_joomsport/img/publish_x.png"  border="0" alt="Delete"></a><input type="hidden" name="teampl[]" value="'.$pl->id.'" /></td><td>'.$pl->name.'</td></tr>';
        }
        ?>
			<tr>
				<td colspan="2" class="cntrl-newplayer">
					<?php echo $lists['player'];
        ?>
					<input type="button" class="btn" value="<?php echo JText::_('BLBE_ADD');
        ?>" onclick="addplayer();" />
				</td>
			</tr>
		</table>
                </div>
            </div>
            </div>
            <div class="jsrespdiv6 jsrespmarginleft2">    
            <?php 

                $query = "SELECT st.bonus_point FROM  #__bl_seasons as s LEFT JOIN #__bl_tournament as t ON t.id = s.t_id, #__bl_season_teams as st WHERE s.s_id = st.season_id AND st.team_id='".$team_id."' AND s.s_id = {$sid}";
        $db->setQuery($query);
        $lists['bonuses'] = $db->loadResult();
        ?>
                <div class="jsBepanel">
                    <div class="jsBEheader">
                        <?php echo JText::_('BLBE_BONUSES');
        ?>
                    </div>
                    <div class="jsBEsettings">
                        <table class="adminlistsNoBorder">
                        
                        <?php
                            echo '<tr><td width="100">'.JText::_('BLBE_BONUS').'</td><td><input type="text" name="bonuses" value="'.floatval($lists['bonuses']).'" />'.'</td></tr>';
        ?>
                        </table>
                    </div>
                </div>
               <?php 

            require_once dirname(__FILE__).'/models/models.php';
        $model = new JSPRO_Models();
        $lists['ext_fields_sr'] = $model->getAdditfields(1, $team_id, $sid);

        ?>
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_FIELDSBYSEASON');
        ?>
                </div>
                <div class="jsBEsettings">
                <table class="adminlistsNoBorder">
                    <?php
                    if (count($lists['ext_fields_sr']) && $sid > 0) {
                        for ($p = 0;$p < count($lists['ext_fields_sr']);++$p) {
                            if ($lists['ext_fields_sr'][$p]->field_type == '3' && !isset($lists['ext_fields_sr'][$p]->selvals)) {
                            } else {
                                if ($lists['ext_fields_sr'][$p]->season_related == 1 && $sid != -1) { //update, instead of $lists["bonuses"]
            ?>
			<tr>
				<td width="100">
					<?php echo $lists['ext_fields_sr'][$p]->name;
                                    ?>
				</td>
				<td>
					<?php

                        switch ($lists['ext_fields_sr'][$p]->field_type) {

                            case '1':    echo $lists['ext_fields_sr'][$p]->selvals;
                                        break;
                            case '2':    echo $editor->display('extraf['.$lists['ext_fields_sr'][$p]->id.']',  htmlspecialchars(isset($lists['ext_fields_sr'][$p]->fvalue_text) ? ($lists['ext_fields_sr'][$p]->fvalue_text) : '', ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore'));
                                        break;
                            case '3':    echo $lists['ext_fields_sr'][$p]->selvals;
                                        break;
                            case '5':    echo $lists['ext_fields_sr'][$p]->selvals;
                                        break;        
                            case '0':
                            default:    echo '<input type="text" maxlength="255" size="60" name="extraf['.$lists['ext_fields_sr'][$p]->id.']" value="'.(isset($lists['ext_fields_sr'][$p]->fvalue) ? htmlspecialchars($lists['ext_fields_sr'][$p]->fvalue) : '').'" />';
                                        break;

                        }
                                    ?>
					<input type="hidden" name="extra_ftype[<?php echo $lists['ext_fields_sr'][$p]->id?>]" value="<?php echo $lists['ext_fields_sr'][$p]->field_type?>" />
					<input type="hidden" name="extra_id[<?php echo $lists['ext_fields_sr'][$p]->id?>]" value="<?php echo $lists['ext_fields_sr'][$p]->id?>" />
				</td>
			</tr>
			
			<?php	
                                }
                                if ($lists['ext_fields_sr'][$p]->season_related == 0) { //update
                    ?>
					<tr>
						<td width="100">
							<?php echo $lists['ext_fields_sr'][$p]->name;
                                    ?>
						</td>
						<td>
						<?php

                        switch ($lists['ext_fields_sr'][$p]->field_type) {

                            case '1':    echo $lists['ext_fields_sr'][$p]->selvals;
                                        break;
                            case '2':    echo $editor->display('extraf['.$lists['ext_fields_sr'][$p]->id.']',  htmlspecialchars(isset($lists['ext_fields_sr'][$p]->fvalue_text) ? ($lists['ext_fields_sr'][$p]->fvalue_text) : '', ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore'));
                                        break;
                            case '3':    echo $lists['ext_fields_sr'][$p]->selvals;
                                        break;
                            case '5':    echo $lists['ext_fields_sr'][$p]->selvals;
                                        break;        
                            case '0':
                            default:    echo '<input type="text" maxlength="255" size="60" name="extraf['.$lists['ext_fields_sr'][$p]->id.']" value="'.(isset($lists['ext_fields_sr'][$p]->fvalue) ? htmlspecialchars($lists['ext_fields_sr'][$p]->fvalue) : '').'" />';
                                        break;

                        }
                                    ?>
							<input type="hidden" name="extra_ftype[<?php echo $lists['ext_fields_sr'][$p]->id?>]" value="<?php echo $lists['ext_fields_sr'][$p]->field_type?>" />
							<input type="hidden" name="extra_id[<?php echo $lists['ext_fields_sr'][$p]->id?>]" value="<?php echo $lists['ext_fields_sr'][$p]->id?>" />
						</td>
					</tr>
				<?php

                                }
                            }
                        }
                    } else {
                        echo JText::_('BLBE_WARN_NOTEAMEFASTOSEAS');
                    }
        //}
            ?>
                    </table>
                </div>
            </div>
            
            </div>    
            <?php
            exit();
    }

    public function getPlayerList()
    {
        $db = JFactory::getDBO();
        $players = JRequest::getVar('players', array(), '', 'array');
        $pl = array();
        if (count($players)) {
            foreach ($players as $p) {
                $pl[] = $p['value'];
            }
        }
        $query = "SELECT CONCAT(first_name,' ',last_name) as name,id FROM #__bl_players ".(count($pl) ? ' WHERE id NOT IN ('.implode(',', $pl).')' : '').' ORDER BY first_name,last_name';
        $db->setQuery($query);
        $playerz = $db->loadObjectList();
        echo '<option value="0">'.JText::_('BLBE_SELPLAYER').'</option>';
        for ($intA = 0; $intA < count($playerz); ++$intA) {
            echo '<option value="'.$playerz[$intA]->id.'">'.$playerz[$intA]->name.'</option>';
        }
        //$is_pl[] = JHTML::_('select.option',  0, JText::_('BLBE_SELPLAYER'), 'id', 'name' ); 

            exit();
    }
    public function getMapsList()
    {
        $db = JFactory::getDBO();
        $players = JRequest::getVar('players', array(), '', 'array');
        $pl = array();
        if (count($players)) {
            foreach ($players as $p) {
                $pl[] = $p['value'];
            }
        }
        $query = 'SELECT m_name as name,id FROM #__bl_maps as m '.(count($pl) ? ' WHERE id NOT IN ('.implode(',', $pl).')' : '').' ORDER BY m_name';
        $db->setQuery($query);
        $playerz = $db->loadObjectList();
        echo '<option value="0">'.JText::_('BLBE_SELMAP').'</option>';
        for ($intA = 0; $intA < count($playerz); ++$intA) {
            echo '<option value="'.$playerz[$intA]->id.'">'.$playerz[$intA]->name.'</option>';
        }
        //$is_pl[] = JHTML::_('select.option',  0, JText::_('BLBE_SELPLAYER'), 'id', 'name' ); 

            exit();
    }
    public function getSubEvents(){
        $db = JFactory::getDBO();
        $eventid = JRequest::getVar('eventid', 0, '', 'int');
        
        $query = "SELECT sub.e_name as name,sub.id"
                . " FROM #__bl_events as e"
                . " JOIN #__bl_events_depending as de ON de.event_id = e.id"
                . " JOIN #__bl_events as sub ON de.subevent_id = sub.id"
                . " WHERE e.id = ".intval($eventid).""
                . " ORDER BY e.e_name";
        $db->setQuery($query);
        $row = $db->loadObject();
        echo json_encode($row);
        exit();
    }
    
    
}
