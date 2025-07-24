<?php

// no direct access
defined('_JEXEC') or die;

class JhtmlJshtml
{
    public static function order($rows, $image = 'filesave.png', $task = 'saveorder')
    {
        return '&nbsp;&nbsp;<a href="javascript:saveorder('.(count($rows) - 1).', \''.$task.'\')" rel="tooltip" class="saveorder btn btn-micro" title="'
                .JText::_('JLIB_HTML_SAVE_ORDER').'"><i class="icon-menu-2"></i>'.JText::_('JLIB_HTML_SAVE_ORDER').'</a>';
    }
    public static function createmess($task)
    {
        ?>
            <div class="jscreateitem">
                <img src="<?php echo JUri::base().'components/com_joomsport/img/new-item.png'?>" /><br />
                <?php echo JText::_('BLBE_CNI_MESS');
        ?><br />
                <a href="javascript:void(0);" onclick="Joomla.submitbutton('<?php echo $task;
        ?>');"><?php echo JText::_('BLBE_CNI_LINK');
        ?></a>
            </div>
        <?php

    }
    public static function showFeedback(){
        ?>
	 <script type="text/javascript" id="UR_initiator"> (function () { var iid = 'uriid_'+(new Date().getTime())+'_'+Math.floor((Math.random()*100)+1); if (!document._fpu_) document.getElementById('UR_initiator').setAttribute('id', iid); var bsa = document.createElement('script'); bsa.type = 'text/javascript'; bsa.async = true; bsa.src = '//beardev.useresponse.com/sdk/supportCenter.js?initid='+iid+'&wid=6'; (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(bsa); })(); </script>
        <?php
    }
}
