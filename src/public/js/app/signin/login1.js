
var auth_fb = (function() {
    function statusChangeCallback(response) {
        console.log('statusChangeCallback');
        console.log(response);
        if (response.status === 'connected') {
            $.ajax({
                url: url + 'app/check/check',
                type: 'GET',
                success: function (response) {
                    console.log(response);
                    if (!response) {
                        $('.logIn').toggle();
                        $(".form").toggle();

                    }
                },
                error: function (error) {
                    console.log(error);
                    debugger;
                    alert('error: block get status');
                }
            });
        }
    }

    window.fbAsyncInit = function() {
        FB.init({
            appId      : '384838578363750',
            cookie     : true,
            xfbml      : true,
            version    : 'v2.2'
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
                FB.login(function(resp){
                    console.log("logIN RESP: "+resp.authResponse)
                    FB.getLoginStatus(function(response) {
                        statusChangeCallback(response);
                        window.location.reload();
                    });
                });
            });
            $('#fbLogout').click(function() {
                FB.getLoginStatus(function(response) {
                    FB.logout(function(resp){
                        console.log("logOUT RESP: "+resp.authResponse)
                        $.ajax({
                            url: url + 'app/check/logout',
                            type: 'GET',
                            success: function (response) {
                                console.log(response);
                            },
                            error: function (error) {
                                console.log(error);
                                alert('error: block get status');
                            }
                        });
                    })
                });

            });
            $('#check').click(function() {
                FB.getLoginStatus(function(response) {
                    statusChangeCallback(response);
                });
            });
        }
    }
})().checkLoginState();

