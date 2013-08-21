$(document).ready(function(){
    $('html').css('overflow-x', 'hidden');

    fb_show();


    fb_form =  $("#fb-button").closest("form");
    $("#fb-button").live("click", function(e){
        fb_send(fb_form);
    });

    fb_form.submit(function(e) {
        fb_send(fb_form);
        e.preventDefault();  
    });

    fb_ajsend(fb_form);
    fb_cr_err(fb_form);    

    fb_input_cl(fb_form);        
});

function fb_input_cl(form) {
    inp = form.find("input");
    sel = form.find("select");
    textar = form.find("textarea");
    fb_inp_each(inp, form);
    fb_inp_each(sel, form);    
    fb_inp_each(textar, form);    
}

function fb_inp_each(el, form) {
    $.each(el, function (i, val){
        $(this).live("focusin", function(e){        
            color = rgb2hex($(this).css("border-color"));
            if (color == "#ff0000") {
                $(this).css("border-color", "#B2B2B2");
                fb_cr_err(form);
            }
        });
    });
}

function fb_send (form) {
    form_act = form.attr('action');
    if ((form_act == undefined) || (form_act=='')) {
        form_act = 'submit.php';
    }

    $.ajax({
        type: "POST",
        url: form_act,
        data: form.serialize(),
        dataType: "JSON",
        success: function(msg){
            if (typeof image_reload == 'function') {
                image_reload();
            }
            
            status = parseInt(msg.status);
            if (status == 0) {
                count = 0;
                $.each(msg.names, function(i, val){
                    if (count == 0) {
                        fb_err_pos(form, i, val);
                    }
                    $('[name="'+i+'"]').css("border", "1px solid red");
                    count++;
                });  
            } else if (status == 1){
                if($("#fb-show").length>0) { 
                    fb_hideshow (); 
                    form.get(0).reset();
                    // function reset form
                    //resetForm(form);
                } else {
                    form.html("<div style='color: red; font-size: 16px;text-shadow: 1px 0px #D89393;' class='fb-msgsent'>"+msg.msg+"</div>");
                }
            } else alert (msg.msg);
        },
        error: function (msg) {
        },
        beforeSend: function() {
            form.trigger("start.search")
        },
        complete: function() {
            form.trigger("finish.search")
        }
    });
}

function fb_ajsend(form) {
    ajload = form.find(".fb-ajload");
    form.on("start.search", function() {
       ajload.show();
    })
    form.on("finish.search", function() {
        ajload.hide();       
    })
}

/* err begin */
function fb_cr_err(form) {
    if (form.find('.fb-err').length) {
        form.find('.fb-err').hide();    
    } else {    
        form.prepend('<div class="fb-err"><div class="fb-triangle"><div class="fb-arrow-up"></div></div><div class="fb-err-text">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="clear:both;"></div></div>');
    }
}

function fb_err_pos(form, name, err_msg) {
    name = $('[name="'+name+'"]');

    n_left = name.position().left;
    n_top = name.position().top;

    n_left = n_left+50;    
    if (name.is('textarea')) {
        n_top = n_top+name.height()+20;        
    } else {
        n_top = n_top+35;
    }

    err =  form.find('.fb-err');
    err.css('top', n_top+'px');
    err.css('left', n_left+'px');    
    err_text = err.find('.fb-err-text');
    err_text.html(err_msg);
    err.show();
    err.effect("bounce", { times: 7, direction: 'right', distance: 50 }, 300);
}
/* err end */

function fb_show () {
    $("#fb-show").live("click", function(e){
        fb_hideshow();
    });
    fb_hover();
}

function fb_hover () {
    el = $("#fb-block");
    width = 300;

    $("#fb-show").hover(
        function(){
        pos = fb_get_pos ();
        numb = -width;
        if ((pos['push'] == 'out') && (pos['numb'] == width+10)) {            
            if (pos['pos'] == 'left') {            
	            el.stop().animate({left: +numb}, 100);
            } else {
	            el.stop().animate({right: +numb}, 100);
            }
        }
        },
        function(){
        pos = fb_get_pos ();
        numb = width+10;            
        if ((pos['push'] == 'out') && (pos['numb'] == width)) {            
            if (pos['pos'] == 'left') {             
	            el.stop().animate({left: -numb}, 100);
            } else {
                el.stop().animate({right: -numb}, 100);
            }
        }
        }
    );
}

function fb_hideshow () {
    el = $("#fb-block");
    padd = 20;
    pos = fb_get_pos ();
        
    if (pos['pos'] == 'left') {
        if (pos['push'] == 'in') {
            numb = pos['numb']+padd+padd;
            el.animate({left: '+='+padd,}, 100, function (){});
            el.animate({left : '-='+numb}, 500, function (){});
            el.animate({left: '+='+padd,}, 100, function (){});
        } else {
            numb = pos['numb'];
            el.animate({left : '+='+numb}, 500, function (){});
            el.animate({left : '-='+padd}, 100, function (){});
        }
    } else {
        if (pos['push'] == 'in') {
            numb = pos['numb']+padd+padd;
            el.animate({right: '+='+padd,}, 100, function (){});
            el.animate({right: '-='+numb, }, 500, function (){});
            el.animate({right: '+='+padd,}, 100, function (){});            
        } else {
            numb = pos['numb'];
            el.animate({right: '+='+numb}, 500, function (){});
            el.animate({right: '-='+padd}, 100, function (){});
        }
    }
}

function fb_get_pos () {
    res = [];
    width = 300;
    width_min = 20;
    el = $("#fb-block");
    shift = $("#fb-block").offset();
    shift_l = el.css('left');
    shift_l = shift_l.replace('px','');
    shift_r = el.css('right');
    shift_r = shift_r.replace('px','');

    if ((shift_l == -width_min) || (shift_l == -width-10) || (shift_l == -width)) {
        res['pos'] = 'left';
        res['shift'] = shift_l;
        res['shift'] = res['shift'].replace('-','');
    }
    if ((shift_r == -width_min) || (shift_r == -width-10) || (shift_r == -width)) {
        res['pos'] = 'right';
        res['shift'] = shift_r;
        res['shift'] = res['shift'].replace('-','');
    }
    if (res['shift'] == width_min) {
        res['px'] = width_min;
        res['numb'] = width-10;
        res['push'] = 'in';
    } else if (res['shift'] == width-10) {
        res['px'] = width;
        res['numb'] = width-10;
        res['push'] = 'out';
    } else {
        res['px'] = 1;        
        res['numb'] = parseFloat(res['shift'].replace('+=',''));
        res['push'] = 'out';        
    }
    return res;
}

function rgb2hex(rgb){
  rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
  return "#" +
   ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
   ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
   ("0" + parseInt(rgb[3],10).toString(16)).slice(-2);
}

function resetForm(selector) {
	$(':text, :password, :file, textarea', selector).val('');
	$(':input, select option', selector)
		.removeAttr('checked')
		.removeAttr('selected');
	$('select option:first', selector).attr('selected',true);
}
