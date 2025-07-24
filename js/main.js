var partfull =[];
function getObj(name) {
		  if (document.getElementById)  {  return document.getElementById(name);  }
		  else if (document.all)  {  return document.all[name];  }
		  else if (document.layers)  {  return document.layers[name];  }
		}
		function JS_addSelectedToList( frmName, srcListName, tgtListName ) {
			var form = eval( 'document.' + frmName );
			var srcList = eval( 'form.' + srcListName );
			var tgtList = eval( 'form.' + tgtListName );

			var srcLen = srcList.length;
			var tgtLen = tgtList.length;
			var tgt = "x";

			//build array of target items
			for (var i=tgtLen-1; i > -1; i--) {
				tgt += "," + tgtList.options[i].value + ","
			}
			
			//Pull selected resources and add them to list
			//for (var i=srcLen-1; i > -1; i--) {
			for (var i=0; i < srcLen; i++) {
				
				if (srcList.options[i].selected && tgt.indexOf( "," + srcList.options[i].value + "," ) == -1) {
                                    if (jQuery('#playerNumbers').length){
                                            jQuery('#row'+srcList.options[i].value).remove();
                                            jQuery('#playerNumbers').append(`<tr id="row`+srcList.options[i].value+`">
                                                <td width="100">`+srcList.options[i].text+`</td>
                                                <td><input size="10" type="text" name="numbers[`+srcList.options[i].value+`]" value="" /></td>
                                            </tr>`);
                                    }
					opt = new Option( srcList.options[i].text, srcList.options[i].value );
					tgtList.options[tgtList.length] = opt;
				}
			}
			
			JS_delFFF(srcList);
		}
			
		function JS_delFFF(srcList){
			var srcLen = srcList.length;
			
			for (var i=srcLen-1; i > -1; i--) {
				if (srcList.options[i].selected) {
                                    if (srcList.id == 'in_teams') {
                                        jQuery('#row'+srcList.options[i].value).remove();
                                    }
					srcList.options[i] = null;
				}
			}
		}

		function JS_delSelectedFromList( frmName, srcListName, tgtListName ) {
			var form = eval( 'document.' + frmName );
			var srcList = eval( 'form.' + srcListName );

			var srcLen = srcList.length;
			JS_addSelectedToList(frmName,srcListName,tgtListName);
			for (var i=srcLen-1; i > -1; i--) {
				if (srcList.options[i] && srcList.options[i].selected) {                                    
					srcList.options[i] = null;
				}
			}
			
		}
		
		function JS_del_REGFE(srcListName,pid){
			var srcList = eval( 'document.adminForm.' + srcListName );
			var srcLen = srcList.length;
			for (var i=srcLen-1; i > -1; i--) {
			
				if (srcList.options[i].value == pid) {
					srcList.options[i] = null;
				}
			}
		}
		
function extractNumber(obj, decimalPlaces, allowNegative)
{
	var temp = obj.value;
	
	// avoid changing things if already formatted correctly
	var reg0Str = '[0-9]*';
	if (decimalPlaces > 0) {
		reg0Str += '\\.?[0-9]{0,' + decimalPlaces + '}';
	} else if (decimalPlaces < 0) {
		reg0Str += '\\.?[0-9]*';
	}
	reg0Str = allowNegative ? '^-?' + reg0Str : '^' + reg0Str;
	reg0Str = reg0Str + '$';
	var reg0 = new RegExp(reg0Str);
	if (reg0.test(temp)) return true;

	// first replace all non numbers
	var reg1Str = '[^0-9' + (decimalPlaces != 0 ? '.' : '') + (allowNegative ? '-' : '') + ']';
	var reg1 = new RegExp(reg1Str, 'g');
	temp = temp.replace(reg1, '');

	if (allowNegative) {
		// replace extra negative
		var hasNegative = temp.length > 0 && temp.charAt(0) == '-';
		var reg2 = /-/g;
		temp = temp.replace(reg2, '');
		if (hasNegative) temp = '-' + temp;
	}
	
	if (decimalPlaces != 0) {
		var reg3 = /\./g;
		var reg3Array = reg3.exec(temp);
		if (reg3Array != null) {
			// keep only first occurrence of .
			//  and the number of places specified by decimalPlaces or the entire string if decimalPlaces < 0
			var reg3Right = temp.substring(reg3Array.index + reg3Array[0].length);
			reg3Right = reg3Right.replace(reg3, '');
			reg3Right = decimalPlaces > 0 ? reg3Right.substring(0, decimalPlaces) : reg3Right;
			temp = temp.substring(0,reg3Array.index) + '.' + reg3Right;
		}
	}
	
	obj.value = temp;
}
function extractNumber2(obj, decimalPlaces, allowNegative)
{
	var temp = obj.value;
	
	// avoid changing things if already formatted correctly
	var reg0Str = '[0-9,-]*';
	if (decimalPlaces > 0) {
		reg0Str += '\\.?[0-9]{0,' + decimalPlaces + '}';
	} else if (decimalPlaces < 0) {
		reg0Str += '\\.?[0-9]*';
	}
	reg0Str = allowNegative ? '^-?' + reg0Str : '^' + reg0Str;
	reg0Str = reg0Str + '$';
	var reg0 = new RegExp(reg0Str);
	if (reg0.test(temp)) return true;

	// first replace all non numbers
	var reg1Str = '[^0-9,-]';
	var reg1 = new RegExp(reg1Str, 'g');
	temp = temp.replace(reg1, '');

	if (allowNegative) {
		// replace extra negative
		var hasNegative = temp.length > 0 && temp.charAt(0) == '-';
		var reg2 = /-/g;
		temp = temp.replace(reg2, '');
		if (hasNegative) temp = '-' + temp;
	}
	
	if (decimalPlaces != 0) {
		var reg3 = /\./g;
		var reg3Array = reg3.exec(temp);
		if (reg3Array != null) {
			// keep only first occurrence of .
			//  and the number of places specified by decimalPlaces or the entire string if decimalPlaces < 0
			var reg3Right = temp.substring(reg3Array.index + reg3Array[0].length);
			reg3Right = reg3Right.replace(reg3, '');
			reg3Right = decimalPlaces > 0 ? reg3Right.substring(0, decimalPlaces) : reg3Right;
			temp = temp.substring(0,reg3Array.index) + '.' + reg3Right;
		}
	}
	
	obj.value = temp;
}

function extractNumberEv(obj, decimalPlaces, allowNegative)
{
	var temp = obj.value;
	
        
        var regStrWPl = /[0-9]{1,4}\+[0-9]{0,2}$/;
        
	if (regStrWPl.test(temp)) return true;
        
	// avoid changing things if already formatted correctly
	var reg0Str = '[0-9]*';
	if (decimalPlaces > 0) {
		reg0Str += '\\.?[0-9]{0,' + decimalPlaces + '}';
	} else if (decimalPlaces < 0) {
		reg0Str += '\\.?[0-9]*';
	}
	reg0Str = allowNegative ? '^-?' + reg0Str : '^' + reg0Str;
	reg0Str = reg0Str + '$';
	var reg0 = new RegExp(reg0Str);
	if (reg0.test(temp)) return true;

	// first replace all non numbers
	var reg1Str = '[^0-9' + (decimalPlaces != 0 ? '.' : '') + (allowNegative ? '-' : '') + ']';
	var reg1 = new RegExp(reg1Str, 'g');
	temp = temp.replace(reg1, '');

	if (allowNegative) {
		// replace extra negative
		var hasNegative = temp.length > 0 && temp.charAt(0) == '-';
		var reg2 = /-/g;
		temp = temp.replace(reg2, '');
		if (hasNegative) temp = '-' + temp;
	}
	
	if (decimalPlaces != 0) {
		var reg3 = /\./g;
		var reg3Array = reg3.exec(temp);
		if (reg3Array != null) {
			// keep only first occurrence of .
			//  and the number of places specified by decimalPlaces or the entire string if decimalPlaces < 0
			var reg3Right = temp.substring(reg3Array.index + reg3Array[0].length);
			reg3Right = reg3Right.replace(reg3, '');
			reg3Right = decimalPlaces > 0 ? reg3Right.substring(0, decimalPlaces) : reg3Right;
			temp = temp.substring(0,reg3Array.index) + '.' + reg3Right;
		}
	}
	
	obj.value = temp;
}

function blockNonNumbers(obj, e, allowDecimal, allowNegative)
{
	var key;
	var isCtrl = false;
	var keychar;
	var reg;
		
	if(window.event) {
		key = e.keyCode;
		isCtrl = window.event.ctrlKey
	}
	else if(e.which) {
		key = e.which;
		isCtrl = e.ctrlKey;
	}
	
	if (isNaN(key)) return true;
	
	keychar = String.fromCharCode(key);
	
	// check for backspace or delete, or if Ctrl was pressed
	if (key == 8 || isCtrl)
	{
		return true;
	}

	reg = /\d/;
	var isFirstN = allowNegative ? keychar == '-' && obj.value.indexOf('-') == -1 : false;
	var isFirstD = allowDecimal ? keychar == '.' && obj.value.indexOf('.') == -1 : false;
	
	return isFirstN || isFirstD || reg.test(keychar);
}
function blockNonNumbers2(obj, e, allowDecimal, allowNegative)
{
	var key;
	var isCtrl = false;
	var keychar;
	var reg;
		
	if(window.event) {
		key = e.keyCode;
		isCtrl = window.event.ctrlKey
	}
	else if(e.which) {
		key = e.which;
		isCtrl = e.ctrlKey;
	}
	
	if (isNaN(key)) return true;
	
	keychar = String.fromCharCode(key);
	
	// check for backspace or delete, or if Ctrl was pressed
	if (key == 8 || isCtrl || keychar == '-' || keychar == ',')
	{
		return true;
	}

	reg = /\d/;
	var isFirstN = allowNegative ? keychar == '-' && obj.value.indexOf('-') == -1 : false;
	var isFirstD = allowDecimal ? keychar == '.' && obj.value.indexOf('.') == -1 : false;
	
	return isFirstN || isFirstD || reg.test(keychar);
}
function disableEnterKey(e)
{
	 var key;
	 if(window.event)
		  key = window.event.keyCode;     //IE
	 else
		  key = e.which;     //firefox
	 if(key == 13)
		  return false;
	 else
		  return true;
}

	function makeRequest(url) {

		var http_request = false;
	
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
			http_request = new XMLHttpRequest();
			if (http_request.overrideMimeType) {
				http_request.overrideMimeType('text/xml');
				// See note below about this line
			}
		} else if (window.ActiveXObject) { // IE
			try {
				http_request = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try {
					http_request = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) {}
			}
		}
	
		if (!http_request) {
			// alert('Giving up: Cannot create an XMLHTTP instance');
			return false;
		}
		http_request.onreadystatechange = function() { alertContents(http_request); };
		http_request.open('GET', url, true);
		http_request.send(null);
	}

    function alertContents(http_request) {

        if (http_request.readyState == 4) {
            if ((http_request.status == 200) && (http_request.responseText.length < 1925)) {
				document.getElementById('jfm_LatestVersion').innerHTML = http_request.responseText;
				if(curver_js == http_request.responseText){
					document.getElementById('span_survr').className = 'jslatvergreen';
				}
            } else {
                document.getElementById('jfm_LatestVersion').innerHTML = 'There was a problem with the request.';
            }
        }

    }

    function jfm_CheckVersion(uri) {
    	document.getElementById('jfm_LatestVersion').innerHTML = 'Checking latest version now...';
    	makeRequest(uri);
    	return false;
    }
	
	function JSPRO_order(field,way){
		var form = document.adminForm;
		form.sortfield.value = field;
		form.sortway.value = way;
		form.submit();
	}
	function JSPRO_order_seas(field,way){
		var form = document.adminForm;
		form.listsortfield.value = field;
		form.listsortway.value = way;
		form.submit();
	}
        
        
jQuery( document ).ready(function() {

    jQuery('#collapsemenujs').on('click', function(){
        //console.log(jQuery('.jlsm_cen').css('display'));
        if(jQuery('.jlsm_cen').css('display') == 'none'){
            jQuery('.jlsm_cen').show();
            jQuery('.jlsm_bot').show();
        }else{
            jQuery('.jlsm_cen').hide();
            jQuery('.jlsm_bot').hide();
        }
    });
    
    jQuery('.radio.btn-group-js label').addClass('btn');
    jQuery('.squardbut label:not(.active)').click(function()
		{
			var label = jQuery(this);
			var input = jQuery('#' + label.attr('for'));
                        
                        //console.log(input.prop('checked'));
			if (!input.prop('checked')) {
                            //console.log(input.val());
				label.closest('.btn-group-js').find('label').removeClass('active btn-success btn-danger btn-primary btn-warning');
				if (input.val() == '') {
					label.addClass('active btn-primary');
				} else if (input.val() == 0) {
					label.addClass('active btn-danger');
				} else if (input.val() == '2'){
                                    
                                        label.addClass('active btn-warning');
                                        
				}else{
					label.addClass('active btn-success');
				}
				input.prop('checked', true);
				input.trigger('change');
                                getSubsLists(label.prop("id"));
			}
		});
		jQuery('.btn-group-js input[checked=checked]').each(function()
		{
			if (jQuery(this).val() == '') {
				jQuery('label[for=' + jQuery(this).attr('id') + ']').addClass('active btn-primary');
			} else if (jQuery(this).val() == 0) {
				jQuery('label[for=' + jQuery(this).attr('id') + ']').addClass('active btn-danger');
                        } else if (jQuery(this).val() == 2) {
				jQuery('label[for=' + jQuery(this).attr('id') + ']').addClass('active btn-warning');
			        
			} else {
				jQuery('label[for=' + jQuery(this).attr('id') + ']').addClass('active btn-success');
			}
		});
    jQuery('.jscheckall').on('click', function(){
        
        var parent = jQuery(this).parent().parent();

        var chk = parent.find('input[type="radio"][value="1"]');
        chk.each(function(){
            
            jQuery(this).prop("checked", true);
            jQuery(this).trigger('change');
            var label = jQuery(this).parent();
            
			var input = jQuery('#' + label.attr('for'));
                        
                        //console.log(input.prop('checked'));
			//if (!input.prop('checked')) {
                            //console.log(input.val());
				label.closest('.btn-group-js').find('label').removeClass('active btn-success btn-danger btn-primary btn-warning');
				if (input.val() == '') {
					label.addClass('active btn-primary');
				} else if (input.val() == 0) {
					label.addClass('active btn-danger');
				} else if (input.val() == '2'){
                                    
                                        label.addClass('active btn-warning');
                                        
				}else{
					label.addClass('active btn-success');
				}
				input.prop('checked', true);
				input.trigger('change');
			//}
            
        });
        getSubsLists('squadradio1');
        getSubsLists('squadradio2');
        
    })
    jQuery('.jscheckallnot').on('click', function(){
        
        var parent = jQuery(this).parent().parent();

        var chk = parent.find('input[type="radio"][value="0"]');
        chk.each(function(){
            
            jQuery(this).prop("checked", true);
            jQuery(this).trigger('change');
            var label = jQuery(this).parent();
            
			var input = jQuery('#' + label.attr('for'));
                        
                        //console.log(input.prop('checked'));
			//if (!input.prop('checked')) {
                            //console.log(input.val());
				label.closest('.btn-group-js').find('label').removeClass('active btn-success btn-danger btn-primary btn-warning');
				if (input.val() == '') {
					label.addClass('active btn-primary');
				} else if (input.val() == 0) {
					label.addClass('active btn-danger');
				} else if (input.val() == '2'){
                                    
                                        label.addClass('active btn-warning');
                                        
				}else{
					label.addClass('active btn-success');
				}
				input.prop('checked', true);
				input.trigger('change');
			//}
            
        })
        getSubsLists('squadradio1');
        getSubsLists('squadradio2');
        
    })
    
    
    

     jQuery("input:file[id='logo']").change(function (){
       var fileName = jQuery(this).val();
       if(fileName){
           var formData = new FormData();
           //console.log(jQuery('input[id=logo]'));
           formData.append( 'logo', jQuery('input[id=logo]')[0].files[0]);
            
           //console.log(formData);
           jQuery.ajax({
                url: "index.php?option=com_joomsport&task=uploadLogo&tmpl=component&no_html=1",
                type: 'POST',
                data: formData,
                processData: false, // important
                contentType: false // important
              }).done(function(res) {
                jQuery("#logoiddiv").html(res);
              });
           //console.log(fileName);
       }

       
     });

    jQuery('input[type="radio"][name="s_reg"]').on('click', function(){

        if(jQuery(this).is(':checked') && jQuery(this).val() == '1'){
            jQuery("#partRegDiv").show();
        }else{
            jQuery("#partRegDiv").hide();
        }
    });
    jQuery('input[type="radio"][name="s_reg_to"]').on('click', function(){

        if(jQuery(this).is(':checked') && jQuery(this).val() == '1'){
            jQuery(".dependonilmit").css("display","inline-block");
        }else{
            jQuery(".dependonilmit").hide();
        }
    });
    jQuery('input[type="radio"][name="highlight_team"]').on('click', function(){

        if(jQuery(this).is(':checked') && jQuery(this).val() == '1'){
            jQuery(".jsEnblHGL").show();
        }else{
            jQuery(".jsEnblHGL").hide();
        }
    });
    jQuery('input[type="radio"][name="equalpts_chk"]').on('click', function(){

        if(jQuery(this).is(':checked') && jQuery(this).val() == '1'){
            jQuery("#divrankingsbox").hide();
            jQuery("#divcririadescr").show();
            
        }else{
            jQuery("#divrankingsbox").show();
            jQuery("#divcririadescr").hide();
        }
    });
    
    jQuery('input[type="radio"][name="spainranking_chk"]').on('click', function(){

        if(jQuery(this).is(':checked') && jQuery(this).val() == '1'){
            jQuery("#divrankingsboxequal").hide();
            jQuery("#divrankingsbox").hide();
            jQuery("#divcririadescr").hide();
            
        }else{
            jQuery("#divrankingsboxequal").show();
            if(jQuery('input[type="radio"][name="equalpts_chk"]:checked').val() == '1'){
                jQuery("#divrankingsbox").hide();
                jQuery("#divcririadescr").show();
            }else{
                jQuery("#divrankingsbox").show();
                jQuery("#divcririadescr").hide();
            }
            
        }
    });
    if(jQuery('input[type="radio"][name="spainranking_chk"]:checked').val() == '1'){
        jQuery("#divrankingsboxequal").hide();
        jQuery("#divrankingsbox").hide();
        jQuery("#divcririadescr").hide();
    }
    jQuery('input[type="radio"][name="enbl_teamlinks"]').on('click', function(){

        if(jQuery(this).is(':checked') && jQuery(this).val() == '1'){
            jQuery(".hdn_div_enblink").hide();
        }else{
            jQuery(".hdn_div_enblink").show();
        }
    });

    if(jQuery('input[type="radio"][name="enbl_teamlinks"]:checked').val() == '1'){
        jQuery(".hdn_div_enblink").hide();
    }
    jQuery('.jsShowBlockFieldsBtn').on('click', function(){

        if(jQuery(this).is(':checked') && jQuery(this).val() == '1'){
            jQuery(".jsShowBlockFields").show();
        }else{
            jQuery(".jsShowBlockFields").hide();
        }
    });
    if(jQuery('.jsShowBlockFieldsBtn:checked').val() == '0'){
        jQuery(".jsShowBlockFields").hide();
    }

	if(jQuery('input[type="radio"][name="enbl_playerlinks"]').is(':checked') && jQuery('input[type="radio"][name="enbl_playerlinks"]:checked').val() == '1'){
		jQuery(".hdn_div_enblink_player").hide();
	}else{
		jQuery(".hdn_div_enblink_player").show();
	}
	jQuery('input[type="radio"][name="enbl_playerlinks"]').on('click', function(){
		if(jQuery(this).is(':checked') && jQuery(this).val() == '1'){
			jQuery(".hdn_div_enblink_player").hide();
		}else{
			jQuery(".hdn_div_enblink_player").show();
		}
	});
});
function getSubsLists(labelId){
        if(labelId.substring(10,11) == '1'){
            parseSubsList('playersq1_id', 'playersq1_out_id', 'new_squard1', 't1_squard');
        }else if(labelId.substring(10,11) == '2'){
            parseSubsList('playersq2_id', 'playersq2_out_id', 'new_squard2', 't2_squard');
        }
    }
    
    function parseSubsList(subsout, subsin, tbl, hidden){
        var chk = jQuery('#'+tbl).find('input[type="radio"][value="1"]:checked');
        jQuery("#"+subsout).find('option').each(function(){
            if(jQuery(this).val() != '0'){
                jQuery(this).remove();
            }
        });
        chk.each(function(){
            //console.log(jQuery(this).parent().parent().parent().find('input[name^="t1_squard"]'));
            var plId = jQuery(this).parent().parent().parent().find('input[name^="'+hidden+'"]').val();
            var plName = jQuery(this).parent().parent().parent().parent().parent().parent().find('td:first').text();
            jQuery("#"+subsout).append("<option value='"+plId+"'>"+plName+"</option>");
            //console.log("<option value='"+plId+"'>"+plName+"</option>");
        });
        
        var plrs = (subsout == 'playersq1_id')?'playersq1_out_id_arr':'playersq2_out_id_arr';

        jQuery('input[name="'+plrs+'\[\]"]').each(function(){
            var plName = jQuery(this).parent().text();
            var plId = jQuery(this).val();
            jQuery("#"+subsout).append("<option value='"+plId+"'>"+plName+"</option>");
            
        });
        
        
        jQuery("#"+subsout).trigger("liszt:updated");
        
        
        
        var chk = jQuery('#'+tbl).find('input[type="radio"][value="2"]:checked');
        jQuery("#"+subsin).find('option').each(function(){
            if(jQuery(this).val() != '0'){
                jQuery(this).remove();
            }
        });
        chk.each(function(){
            //console.log(jQuery(this).parent().parent().parent().find('input[name^="t1_squard"]'));
            var plId = jQuery(this).parent().parent().parent().find('input[name^="'+hidden+'"]').val();
            var plName = jQuery(this).parent().parent().parent().parent().parent().parent().find('td:first').text();
            jQuery("#"+subsin).append("<option value='"+plId+"'>"+plName+"</option>");
            //console.log("<option value='"+plId+"'>"+plName+"</option>");
        });
        jQuery("#"+subsin).trigger("liszt:updated");
    }
    
//knockout
jQuery(document).ready(function(){
    jQuery("body").on('click', '.jsmatchconf2', function(){
        jQuery('#modalAj').show();
        var tdc = jQuery(this).closest("td");
        var intA = parseInt(tdc.attr("data-game"));
        var intB = parseInt(tdc.attr("data-level"));
        var formdata = jQuery('#adminForm').serialize();
        var di = parseInt(jQuery(this).attr("data-index"));
        var id = jQuery('input[name="id"]').val();
        //console.log(formdata);
        var data = {
            'action': 'mday_saveknock',
            'formdata': formdata,
            'yLevel' : intA,
            'xLevel' : intB,
            'dIndex' : di
            
        };
        
        jQuery.ajax({
            url: "index.php?option=com_joomsport&task=matchday_apply_complex&tmpl=component&no_html=1&t_knock=3&id="+id+"&cid[]="+id,
            type: 'POST',
            data: data
        }).done(function(res) {
            jQuery('#modalAj').hide();
            if(res){
                location.href = 'index.php?option=com_joomsport&task=match_edit&cid='+res;
            }else{
                alert('Specify Matchday name');
            }
        });
        
    });
});

jQuery(document).ready(function(){
    
    jQuery("body").on("click", ".jsMultilangIco", function(){
        
        var parent = jQuery(this).parent().parent();
        var container = parent.find('.jsTranslationContainer');
        if(container.css('display') == 'none'){
            container.show();
        }else{
            container.hide();
        }
    });
    
});
