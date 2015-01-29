
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
                success: function (response) {
                    console.log(response);
                    if (!response) {
                        $('.logIn').toggle();
                        $(".form").toggle();

                    }
                },
                error: function (error) {
                    console.log(error);
                    alert('error: block get status');
                }
            });
        }
        // else if (response.status === 'not_authorized') {
        //    if(window.location!==url+"app/login")
        //    window.location=url+"app/login";
        //} else {
        //    var path = window.location;
        //    if(path+""!==url+"app/login"){
        //        window.location=url+"app/login";
        //    }
        //
        //}
        //$.ajax({
        //    url: url + 'app/check/',
        //    type: 'GET',
        //    success: function(response) {
        //        console.log(response);
        //    },
        //    error: function(error) {
        //        console.log(error);
        //        alert('error: block get status');
        //    }
        //});
    }

    window.fbAsyncInit = function() {
        FB.init({
            appId      : '330194637170000',
            cookie     : true,
            xfbml      : true,
            version    : 'v2.2'
        });
        //FB.getLoginStatus(function(response) {
        //    statusChangeCallback(response);
        //});
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
                FB.login(function(resp){
                    console.log("logIN RESP: "+resp.authResponse)
                    FB.getLoginStatus(function(response) {
                        statusChangeCallback(response);
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
$(document).ready(function(){
    check_user("regist","signIn");
    $(".form").toggle();
})

function check_user(sectionFrom,sectionSignIn){
    $.ajax({
        url: url + 'app/check/',
        type: 'GET',
        success: function (response) {
            console.log(response);
            if (response==="regist") {
                $("#"+sectionFrom).toggle();
                $("#"+sectionSignIn).toggle();
            }
            if (response==="ok") {
                window.location=url+"app/calendar";
            }
        },
        error: function (error) {
            console.log(error);
            alert('error: block get status');
        }
    });
}