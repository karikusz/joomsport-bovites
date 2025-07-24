var partfull =[];
jQuery(document).ready(function(){
    jQuery("body").on('click', '.jsmatchconf2', function(){
        jQuery('#modalAj').show();
        var tdc = jQuery(this).closest("td");
        var intA = parseInt(tdc.attr("data-game"));
        var intB = parseInt(tdc.attr("data-level"));
        var formdata = jQuery('#adminForm').serialize();
        var di = parseInt(jQuery(this).attr("data-index"));
        var id = jQuery('input[name="id"]').val();
        var sid = jQuery('input[name="sid"]').val();
        //console.log(formdata);
        var data = {
            'action': 'mday_saveknock',
            'formdata': formdata,
            'yLevel' : intA,
            'xLevel' : intB,
            'dIndex' : di
            
        };

        jQuery.ajax({
            url: jsFEUri + "index.php?option=com_joomsport&controller=admin&task=matchday_apply_complex&tmpl=component&no_html=1&sid="+sid+"&t_knock=3&id="+id+"&cid[]="+id,
            type: 'POST',
            data: data
        }).done(function(res) {
            jQuery('#modalAj').hide();
            if(res){
                location.href = jsFEUri + 'index.php?option=com_joomsport&task=edit_match&controller=admin&sid='+sid+'&cid='+res;
            }else{
                alert('Specify Matchday name');
            }
        });
        
    });
});