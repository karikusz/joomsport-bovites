//knockout
jQuery(document).ready(function(){
    jQuery("body").on("click", ".jsproceednext", function(){
        var tdc = jQuery(this).closest("td");
        var intA = parseInt(tdc.attr("data-game"));
        var intB = parseInt(tdc.attr("data-level"));
        var home = tdc.find('.js_selpartichome');
        var away = tdc.find('.js_selparticaway');
        var is_final = jQuery(this).hasClass("jsknockfinal");
        if(intB == 0){
            var homeText = tdc.find('.js_selpartichome option:selected').text();
            var awayText = tdc.find('.js_selparticaway option:selected').text();
        }else{
            var homeText = jQuery("#knocktd_"+intA+"_"+(intB)).find(".knocktop .knwinner").html();
            var awayText = jQuery("#knocktd_"+intA+"_"+(intB)).find(".knockbot .knwinner").html();
            
        }
        var homeScore = tdc.find('.mglScoreHome');
        var awayScore = tdc.find('.mglScoreAway');
        //console.log(home.val() +'!= 0 && '+away.val() +'!= 0');
        if(home.val() != '0' && away.val() != '0' && home.val() != undefined && away.val() != undefined){
            if((home.val() == '-1' && away.val() != 0) || (home.val() != 0 && away.val() == '-1')){
                if(home.val() == '-1'){
                    var winner = awayText;
                    var winnerID = parseInt(away.val());
                }
                if(away.val() == '-1'){
                    var winner = homeText;
                    var winnerID = parseInt(home.val());
                }
                
                if(jQuery("#knocktd_"+intA+"_"+(intB+1)).length){
                    jQuery("#knocktd_"+intA+"_"+(intB+1)).find(".knocktop .knockplName").html('');
                    jQuery("#knocktd_"+intA+"_"+(intB+1)).find(".knocktop .knockplName").append("<div class='knwinner'>"+winner+"</div>");
                    jQuery("#knocktd_"+intA+"_"+(intB+1)).find(".knocktop .knockplName").append('<input type="hidden" class="js_selpartichome" name="set_home_team_'+intA+'_'+(intB+1)+'" value="'+parseInt(winnerID)+'">');
                }else{
                    jQuery("#knocktd_"+(intA - Math.pow(2,intB))+"_"+(intB+1)).find(".knockbot .knockplName").html('');
                    jQuery("#knocktd_"+(intA - Math.pow(2,intB))+"_"+(intB+1)).find(".knockbot .knockplName").append("<div class='knwinner'>"+winner+"</div>"); 
                    jQuery("#knocktd_"+(intA - Math.pow(2,intB))+"_"+(intB+1)).find(".knockbot .knockplName").append('<input type="hidden" class="js_selparticaway" name="set_away_team_'+(intA - Math.pow(2,intB))+'_'+(intB+1)+'" value="'+parseInt(winnerID)+'">');
                }
            }else
            if(homeScore.val() != '' && awayScore.val() != ''){
                var homewin = 0;
                var awaywin = 0;
                
                for(var i=0; i<homeScore.length;i++){
                    //console.log(awayScore[i]);
                    if(awayScore[i]){
                        if(parseInt(awayScore[i].value) > parseInt(homeScore[i].value)){
                            awaywin++;
                        }else
                        if(parseInt(awayScore[i].value) < parseInt(homeScore[i].value)){
                            homewin++;
                        }    
                    }
                }
                
                var winner = (homewin > awaywin) ? homeText : awayText;
                var winnerID = (homewin > awaywin) ? home.val() : away.val();
                
                if(homewin == awaywin){
                    jQuery( "#jsknock-selectwinner" ).html('<select id="jsselectw"><option value="0">'+homeText+'</option><option value="1">'+awayText+'</option></select>');
                    jQuery( "#jsknock-selectwinner" ).dialog({
                        modal: true,
                        buttons: {
                          Ok: function() {
                            jQuery( this ).dialog( "close" );
                            if(jQuery("#jsselectw").val() == '0'){
                                winner = homeText;
                                winnerID = home.val();
                                if(is_final){
                                    jsknockSetWinner(home);
                                }
                            }else if(jQuery("#jsselectw").val() == '1'){
                                winner = awayText;
                                winnerID = away.val();
                                if(is_final){
                                    jsknockSetWinner(away);
                                }
                            }
                            if(jQuery("#knocktd_"+intA+"_"+(intB+1)).length){
                                jQuery("#knocktd_"+intA+"_"+(intB+1)).find(".knocktop .knockplName").html('');
                                jQuery("#knocktd_"+intA+"_"+(intB+1)).find(".knocktop .knockplName").append("<div class='knwinner'>"+winner+"</div>");
                                jQuery("#knocktd_"+intA+"_"+(intB+1)).find(".knocktop .knockplName").append('<input type="hidden" class="js_selpartichome" name="set_home_team_'+intA+'_'+(intB+1)+'" value="'+parseInt(winnerID)+'">');
                            }else{
                                jQuery("#knocktd_"+(intA - Math.pow(2,intB))+"_"+(intB+1)).find(".knockbot .knockplName").html('');
                                jQuery("#knocktd_"+(intA - Math.pow(2,intB))+"_"+(intB+1)).find(".knockbot .knockplName").append("<div class='knwinner'>"+winner+"</div>"); 
                                jQuery("#knocktd_"+(intA - Math.pow(2,intB))+"_"+(intB+1)).find(".knockbot .knockplName").append('<input type="hidden" class="js_selparticaway" name="set_away_team_'+(intA - Math.pow(2,intB))+'_'+(intB+1)+'" value="'+parseInt(winnerID)+'">');
                            }
                          }
                        }
                      });
                    
                }else{
                    if(jQuery("#knocktd_"+intA+"_"+(intB+1)).length){
                        jQuery("#knocktd_"+intA+"_"+(intB+1)).find(".knocktop .knockplName").html('');
                        jQuery("#knocktd_"+intA+"_"+(intB+1)).find(".knocktop .knockplName").append("<div class='knwinner'>"+winner+"</div>");
                        jQuery("#knocktd_"+intA+"_"+(intB+1)).find(".knocktop .knockplName").append('<input type="hidden" class="js_selpartichome" name="set_home_team_'+intA+'_'+(intB+1)+'" value="'+parseInt(winnerID)+'">');
                    }else{
                        jQuery("#knocktd_"+(intA - Math.pow(2,intB))+"_"+(intB+1)).find(".knockbot .knockplName").html('');
                        jQuery("#knocktd_"+(intA - Math.pow(2,intB))+"_"+(intB+1)).find(".knockbot .knockplName").append("<div class='knwinner'>"+winner+"</div>"); 
                        jQuery("#knocktd_"+(intA - Math.pow(2,intB))+"_"+(intB+1)).find(".knockbot .knockplName").append('<input type="hidden" class="js_selparticaway" name="set_away_team_'+(intA - Math.pow(2,intB))+'_'+(intB+1)+'" value="'+parseInt(winnerID)+'">');
                    }
                    if(is_final){
                        if(winnerID == home.val()){
                            jsknockSetWinner(home);
                        }else{
                            jsknockSetWinner(away);
                        }
                    }
                }
                
                
            } 
        }
        chkKnockIcons();
    });
    
    function jsknockSetWinner(Obj){
        jQuery("#jsknock_winnerid").val(Obj.val());
        var parentObj = Obj.parent();
        jQuery('.jsknockwinnerDiv').remove();
        parentObj.append('<div class="jsknockwinnerDiv"></div>');
    }
    
    function chkKnockIcons(){
        jQuery(".jsproceednext").hide();
        jQuery(".jsmatchconf").hide();
        
        jQuery("#jsKnockTableBe td").each(function(){
            var tdc = jQuery(this);
            var home = tdc.find('.js_selpartichome');
            var away = tdc.find('.js_selparticaway');

            var homeScore = tdc.find('.mglScoreHome');
            var awayScore = tdc.find('.mglScoreAway');
            console.log(home.val()+' '+away.val());
            if((home.val() > '0' && away.val() > '0' && home.val() != undefined && away.val() != undefined) || home.length == '0' || away.length == '0'){
                tdc.find(".jsmatchconf").show();
            }
            
            if(home.val() != '0' && away.val() != '0' && home.val() != undefined && away.val() != undefined){
                if((home.val() == '-1' && away.val() != 0 && away.val() ) || (home.val() != 0 && away.val() == '-1' && home.val())){
                    tdc.find(".jsproceednext").show();
                }
                else if(homeScore.val() != '' && awayScore.val() != ''){
                    tdc.find(".jsproceednext").show();
                } 
            }   
            
        });
    }
    jQuery("body").on("change","#jsKnockTableBe .mglScore", function(){
        chkKnockIcons();
    });
    
    
    jQuery(".js_selpartic").on('change',function(){
        chkKnockIcons();
    });
    
    chkKnockIcons();
    
    
    function JSKN_recheckConf(td){
        var intA = 0;
        td.find('.jsmatchconf2').each(function(){
            jQuery(this).attr('data-index',intA);
            intA++;
        });
    }
    
    
    
    jQuery('#JSMD_matchday_type').on("change",function(){
       if(jQuery(this).val() == '1'){
           jQuery("#jsknock_only").show();
       }else{
           jQuery("#jsknock_only").hide();
       }
    });
    
    
    jQuery("body").on("click",".jsknockadd", function(){
        var tdc = jQuery(this).closest("td");
        var intA = parseInt(tdc.attr("data-game"));
        var intB = parseInt(tdc.attr("data-level"));
        var homeDIV = jQuery("#knocktd_"+intA+"_"+(intB)).find(".knocktop .knockscore");
        var awayDIV = jQuery("#knocktd_"+intA+"_"+(intB)).find(".knockbot .knockscore");
        
        var maximum = 0;

        jQuery("#knocktd_"+intA+"_"+(intB)).find('.knockscoreItem').each(function() {
            
          var value = parseFloat(jQuery(this).attr('data-index'));
          maximum = (value > maximum) ? value : maximum;
        });
        maximum++;
        
        var htmlHome = '<div class="knockscoreItem" data-index="'+maximum+'"><input type="text" class="mglScore mglScoreHome" value="" name="set_home_score_'+intA+'_'+intB+'[]" size="3" maxlength="3" onblur="extractNumber(this,0,false);" onkeyup="extractNumber(this,0,false);" onkeypress="return blockNonNumbers(this, event, false, false);" /><input type="hidden" name="match_id_'+intA+'_'+intB+'[]" value="" /><i class="fa fa-cog jsmatchconf2" aria-hidden="true"></i><i class="jsknockdel fa fa-minus-square" aria-hidden="true"></i></div>';
        var htmlAway = '<div class="knockscoreItem" data-index="'+maximum+'"><input type="text" class="mglScore mglScoreAway" value="" name="set_away_score_'+intA+'_'+intB+'[]" size="3" maxlength="3" onblur="extractNumber(this,0,false);" onkeyup="extractNumber(this,0,false);" onkeypress="return blockNonNumbers(this, event, false, false);" /></div>';
        
        homeDIV.append(htmlHome);
        awayDIV.append(htmlAway);
        JSKN_recheckConf(tdc);
    });
    
    jQuery("body").on("mouseover", ".knockscoreItem", function(){
        if(jQuery(this).find("i.jsmatchconf2").css("display") == "none"){
            var curI = jQuery(this);
            jQuery('.knockscoreItem').each(function(){
                if(curI !== jQuery(this)){
                    jQuery(this).find("i").hide('slow');
                }

            });
            var ccc = jQuery(this).closest('td.even').find('i.jsmatchconf2').length;
            if(ccc > 1){
                jQuery(this).find("i.jsknockdel").show('slow');
            }
            jQuery(this).find("i.jsmatchconf2").show('slow');
        }
        
        //jQuery(this).find("i").delay(5000).fadeIn();
    });
    jQuery("body").on("click", ".fa-minus-square", function(){
        var ind = jQuery(this).closest('.knockscoreItem').attr('data-index');
        var td = jQuery(this).closest('td.even');
        td.find('.knockscoreItem[data-index="'+ind+'"]').remove();
        JSKN_recheckConf(td);
    });
    jQuery(".jsknchange").on("click",function(){
        var div = jQuery(this).closest('tr');
        var hm = div.find('.jsSpanHome').html();
        div.find('.jsSpanHome').html(div.find('.jsSpanAway').html());
        div.find('.jsSpanAway').html(hm);
        
        var hmScore = div.find('.jsSpanHomeScore').html();
        div.find('.jsSpanHomeScore').html(div.find('.jsSpanAwayScore').html());
        div.find('.jsSpanAwayScore').html(hmScore);
    });
    
    //js_selpartic
    partfull = jQuery('#js_selpartic_0_0 option');
    //console.log(partfull);
    var partfull_cur = [];
    function recalcPartic(){
        var partfull_cur = [];
        for(i=0;i<partfull.length;i++){
            var cur = partfull[i].value;
            var exist = 0;
            //console.log(partfull[i].value);
            jQuery('select.js_selpartic').each(function(){
                //console.log(jQuery(this).val());
                if(jQuery(this).val() == cur){
                    exist = 1;
                }
            });
            if(exist == 0 || cur < 1){
                partfull_cur.push(partfull[i].value);
            }
        };
        jQuery('select.js_selpartic').each(function(){
            
            var teamV = jQuery(this).val();
            
            jQuery(this).html('');
            for(i=0;i<partfull.length;i++){
                if(jQuery.inArray(partfull[i].value, partfull_cur) != -1 || teamV == partfull[i].value){
                    var selected = (partfull[i].value == teamV) ? ' selected="selected"' : '';
                    jQuery(this).append('<option value="'+partfull[i].value+'" '+selected+'>'+partfull[i].text+'</option>');
                }

            }
            
        });
        jQuery('select.js_selpartic').trigger("liszt:updated");
    }
    recalcPartic();

    jQuery("body").on("change",'select.js_selpartic', function(){
        var team2 = jQuery(this).val();
        if(team2 == 0 || team2 == -1){
            return;
        }
        recalcPartic();
    });
    
    
});