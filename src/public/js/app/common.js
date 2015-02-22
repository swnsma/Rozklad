var url =window.location.origin +'/';
var disqusPublicKey="KDCo6JfYbQFJv9Dzk8c79JkR1KyhTfStAkhOSZMCfBEXu2n2h2zKOjQ10n4G3Hqc";
var disqusSecretKey="OWXEOra8Y8VKldsvJuGqW1XaueQy5LKXG3G6bRO79XO29lQnDdstwUhSnYn3tHdR";
var disqusShortname ="schedule";

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

        function addCss(filename) {
            var fileref=document.createElement("link");
            fileref.setAttribute("rel", "stylesheet");
            fileref.setAttribute("type", "text/css");
            fileref.setAttribute("href", filename);
            document.getElementsByTagName("head")[0].appendChild(fileref);
        }


        var func = function() {
            $(this).css('background-image', 'url(http://i143.photobucket.com/albums/r146/sconti1369/Funny_Pictures_Animated_Dancing_Cat.gif)')
        };

        if (l == url + 'app/groups') {
            $('.icon-container').each(func);
        }
        if (l == url + 'app/calendar') {
            $('td.fc-day.fc-widget-content').each(func);
        }

        addCss(url + 'public/css/common/bad-trip.css');
    },
    'Z'.charCodeAt(0),
    'X'.charCodeAt(0)
);