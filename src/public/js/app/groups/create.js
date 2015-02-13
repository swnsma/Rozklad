

function create_group(data, func) {
    $.ajax({
        url: url + 'app/groups/createNewGroup',
        type: 'POST',
        dataType: 'json',
        data: data,
        success: func.success,
        error: func.error,
        processData: false,
        contentType: false
    });
}
function removeError(){
    $(this).removeClass('error-input');
    er1.css('display', 'none');
    er2.css('display', 'none');
}
function trim(el){
    var val=el.val();
    val=val.replace(/^ */g, '');
    val=val.replace(/ *$/g, '');
    el.val(val);
}
function validScriptInsertion(el){
    var val=el.val()
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/\n/g, '<br/>')
        .replace(/\s/g,'&nbsp;');
    debugger;
    el.val(val);
}
function validLen(el){
    return el.val().length < 1;
}


var file_err = true;
$(document).on('change','#photo', function() {
    var types = ['image/jpeg', 'image/png', 'image/gif'];
    var file = document.getElementById('photo').files;
    if (file.length != 0) {
        var photo = file[0];
        if (photo.size > 4 * 1024 * 1024) {
            alert(1);
            file_err = false;
            return;
        }

        if (!include(types, photo.type)) {
            alert(2);
            file_err = false;
        }
    }
});


$('#createButton').click(function() {
    var flag_error=0;
    var el_name = $('#inputName');
    var el_descr = $('#inputDesc');
    var  el_photo= $('#photo');
    var er1 = $('#error1');
    var er2 = $('#error2');
    var er3 = $('#error3');
    var er4 = $('#error4');
    var name = el_name.val();
    var descr = el_descr.val();
    el_name.removeClass('error-input');
    el_descr.removeClass('error-input');
    er1.css('display', 'none');
    er2.css('display', 'none');
    er3.css('display', 'none');
    er4.css('display', 'none');

    trim(el_name);
    trim(el_descr);
    //validScriptInsertion(el_name);
    //validScriptInsertion(el_descr);
    if(validLen(el_name)){
        el_name.addClass('error-input');
        er1.css('display', 'block');
        flag_error=1;
    }
    if(validLen(el_descr)){
        el_descr.addClass('error-input');
        er2.css('display', 'block');
        flag_error=1;
    }
    if(flag_error){
        return false;
    }
    //if (name.match(/[^а-яА-Яa-zA-Z 0-9!?:;.,-<>]/)) {
    //    el_name.addClass('error-input');
    //    er3.css('display', 'block');
    //    flag_error=1;
    //}
    //if (!descr.match(/[^а-яА-Яa-zA-Z 0-9!?:;.,-<>]$/)) {
    //    el_descr.addClass('error-input');
    //    er4.css('display', 'block');
    //    flag_error=1;
    //}
    //if(flag_error){
    //    return false;
    //}

    alert(23323);

    if (file_err) {
        create_group(new FormData(document.getElementById('create1')), {
            success: function (response) {
                console.log(response);
                if (response.status == 'group_create') {
                    window.location = url + 'app/grouppage/id' + response.id;
                } else {
                    alert(response.status);
                }
            },
            error: function () {
                alert('error');
            }
        });
    }
    return false;
});

(function ($) {
    $.fn.autogrow = function (options) {
        var $this, minHeight, lineHeight, shadow, update;
        this.filter('textarea').each(function () {
            $this = $(this);
            minHeight = $this.height();
            lineHeight = $this.css('lineHeight');
            $this.css('overflow','hidden');
            shadow = $('<div></div>').css({
                position: 'absolute',
                'word-wrap': 'break-word',
                top: -10000,
                left: -10000,
                width: $this.width(),
                fontSize: $this.css('fontSize'),
                fontFamily: $this.css('fontFamily'),
                lineHeight: $this.css('lineHeight'),
                resize: 'vertical'
            }).appendTo(document.body);
            update = function () {
                shadow.css('width', $(this).width());
                var val = this.value.replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/\n/g, '<br/>')
                    .replace(/\s/g,'&nbsp;');
                if (val.indexOf('<br/>', val.length - 5) !== -1) { val += '#'; }
                shadow.html(val);
                $(this).css('height', Math.max(shadow.height()+15, minHeight));
            };
            $this.change(update).keyup(update).keydown(update);
            update.apply(this);
        });
        return this;
    };
    // On page-load, auto-expand textareas to be tall enough to contain initial content

}(jQuery));

$(document).ready(function(){
    var el_name = $('#inputName');
    var el_descr = $('#inputDesc');
    var  el_photo= $('#photo');
    var er1 = $('#error1');
    var er2 = $('#error2');
    var er3 = $('#error3');
    var er4 = $('#error4');
    $("textarea")
        .autogrow()
        .css("min-height","50px")
        .css("padding-top","10px");
    var er5 = $('#error5');
    er5.css("display","block");
    $(el_descr).on("focus",function(){
        $(".form-control").removeClass('error-input');
        er1.css('display', 'none');
        er2.css('display', 'none');
        er3.css('display', 'none');
        er4.css('display', 'none');
    });
    $(el_name).on("focus",function(){
        $('.form-control').removeClass('error-input');
        er1.css('display', 'none');
        er2.css('display', 'none');
        er3.css('display', 'none');
        er4.css('display', 'none');
    });
});