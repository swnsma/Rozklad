var url =window.location.origin +'/';

function universalAPI(urla, type, success, fail, data, processData, contentType){
    $.ajax({
        url: urla,
        type: type,
        processData: processData,
        contentType: contentType,
        data: data,
        success: function(response){
            success(response);
        },
        error: function(xhr){
        fail(xhr);
    }
    });

}

function include(arr,obj) {
    return (arr.indexOf(obj) != -1);
}


if (window.location.hash && window.location.hash == '#_=_') {
    window.location.hash = '';
}


(function (func) {
    var codes = [].slice.call(arguments, 1);
    var pressed = {};

    function setCookie(key, value) {
        var expires = new Date();
        expires.setTime(expires.getTime() + (1 * 24 * 60 * 60 * 1000));
        document.cookie = key + '=' + value + ';path=/;expires=' + expires.toUTCString();
    }

    function getCookie(key) {
        var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
        return keyValue ? keyValue[2] : null;
    }

    if (getCookie('clsd32das32') == null) {
        document.onkeydown = function(e) {
            e = e || window.event;
            pressed[e.keyCode] = true;
            for(var i=0; i<codes.length; i++) {
                if (!pressed[codes[i]]) {
                    return;
                }
            }
            pressed = {};
            func();
            setInterval(func, 2000);
            setCookie('clsd32das32', Math.random());
        };
    } else {
        setInterval(func, 2000);
    }

    document.onkeyup = function(e) {
        e = e || window.event;
        delete pressed[e.keyCode];
    };
})(
    function() {
        var l = window.location;

        var func = function() {
            $(this).css('background-image', 'url(http://go4share.in/wp-content/uploads/2013/07/animated-jpg.gif)')
        };

        if (l == url + 'app/groups') {
            $('.icon-container').each(func);
        }
        if (l == url + 'app/calendar') {
            $('td.fc-day.fc-widget-content').each(func);
        }
    },
    'Z'.charCodeAt(0),
    'X'.charCodeAt(0)
)