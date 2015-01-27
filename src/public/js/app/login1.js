
var auth_fb = (function() {

    function statusChangeCallback(response) {
        console.log('statusChangeCallback');
        console.log(response);
        if (response.status === 'connected') {
            $.ajax({
                url: url + 'app/login/check/',
                type: 'GET',
                success: function(response) {
                    console.log("RESPONSE: "+response);
                },
                error: function(error) {
                    console.log(error);
                    alert('error: block get status');
                }
            });
        } else if (response.status === 'not_authorized') {
            document.getElementById('status').innerHTML = 'Please log ' +
            'into this app.';
        } else {
            document.getElementById('status').innerHTML = 'Please log ' +
            'into Facebook.';
        }
    }

    window.fbAsyncInit = function() {
        FB.init({
            appId      : '399004123614787',
            cookie     : true,
            xfbml      : true,
            version    : 'v2.1'
        });

        FB.getLoginStatus(function(response) {
            statusChangeCallback(response);
        });

    };

    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    function testAPI() {
        console.log('Welcome!  Fetching your information.... ');
        FB.api('/me', function(response) {
            console.log(response);
            console.log('Successful login for: ' + response.name);
            document.getElementById('status').innerHTML =
                'Thanks for logging in, ' + response.name + '!';
        });
    }

    return {
        checkLoginState: function() {
            $('#fbLogin').click(function() {
                FB.getLoginStatus(function(response) {
                    statusChangeCallback(response);
                });
            });
        }
    }
})().checkLoginState();
