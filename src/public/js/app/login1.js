
var auth_fb = (function() {
    //var login_event = function(response) {
    //    console.log("login_event");
    //    console.log(response.status);
    //    console.log(response);
    //}
    //
    //var logout_event = function(response) {
    //    console.log("logout_event");
    //    console.log(response.status);
    //    console.log(response);
    //}
    function statusChangeCallback(response) {
        console.log('statusChangeCallback');
        console.log(response);
        if (response.status === 'connected') {
            $.ajax({
                url: url + 'app/check/',
                type: 'GET',
                success: function(response) {
                    console.dir("RESPONSE: "+response);
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
        //FB.Event.subscribe('auth.login', login_event);
        //FB.Event.subscribe('auth.logout', logout_event);

// In your JavaScript code:


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
                FB.login();
                FB.getLoginStatus(function(response) {
                    statusChangeCallback(response);

                });
            });
            $('#fbLogout').click(function() {
                FB.logout();
                FB.getLoginStatus(function(response) {
                    statusChangeCallback(response);

                });
            });
        }
    }
})().checkLoginState();
