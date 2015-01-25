var auth = (function() {
    return {
        check: function(access_token, service, reg) {
            $.ajax({
                url: url + 'app/login/check/' + access_token + '/' + service,
                type: 'GET',
                success: function(response) {
                    if (response.token == access_token) {
                       if (response.status == 'autorized') {
                           alert('true'); // перенаправляем кудись
                       } else if (response.status == 'no_autorized') {
                           alert('no_autorized');
                       } else {
                            reg();
                       }
                    }
                },
                error: function() {
                    alert('error: block get status');
                }
            });
        },
        register: function(data) {
            $.ajax({
                url: url + 'app/login/register',
                type: 'POST',
                dataType: 'json',
                data: data,
                success: function(response) {
                    var status = response.status;
                    if (status == 'register') {
                        alert('register');
                    } else if (status == 'no_register') {
                        alert('no_register');
                    } else if (status == 'no_data') {
                        alert('no_data');
                    } else if (status == 'invalid_data') {
                        alert('invalid_data');
                    } else {
                        alert('unknown status');
                    }
                },
                error: function() {
                    alert('error reg');
                }
            });
        }
    }
})();


var auth_fb = (function() {

    function statusChangeCallback(response) {
        console.log('statusChangeCallback');
        console.log(response);
        if (response.status === 'connected') {
            auth.check(response.authResponse.accessToken, 'facebook',
                function() {
                    alert('зараз відбудеття реєстрація');
                    auth.register(user_data)
                }
            );
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
            appId      : '386967351480465',
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
})();

var auth_g = (function() {
    var BASE_API_PATH = 'plus/v1/';

    return {
        onSignInCallback: function(authResult) {
            $('#gConnect').hide();
            gapi.client.load('plus','v1').then(function() {
                $('#authResult').html('Auth Result:<br/>');
                for (var field in authResult) {
                    $('#authResult').append(' ' + field + ': ' +
                        authResult[field] + '<br/>');
                }
                if (authResult['access_token']) {
                    $('#authOps').show('slow');
                    auth_g.profile();
                    auth_g.people();
                } else if (authResult['error']) {
                    console.log('There was an error: ' + authResult['error']);
                    $('#authResult').append('Logged out');
                    $('#authOps').hide('slow');
                    $('#gConnect').show();
                }
                console.log('authResult', authResult);
            });
        },
        disconnect: function() {
            $.ajax({
                type: 'GET',
                url: 'https://accounts.google.com/o/oauth2/revoke?token=' +
                    gapi.auth.getToken().access_token,
                async: false,
                contentType: 'application/json',
                dataType: 'jsonp',
                success: function(result) {
                    console.log('revoke response: ' + result);
                    $('#authOps').hide();
                    $('#profile').empty();
                    $('#visiblePeople').empty();
                    $('#authResult').empty();
                    $('#gConnect').show();
                },
                error: function(e) {
                    console.log(e);
                }
            });
        },
        people: function() {
            gapi.client.plus.people.list({
                'userId': 'me',
                'collection': 'visible'
            }).then(function(res) {
                var people = res.result;
                $('#visiblePeople').empty();
                $('#visiblePeople').append('Number of people visible to this app: ' +
                    people.totalItems + '<br/>');
                for (var personIndex in people.items) {
                    person = people.items[personIndex];
                    $('#visiblePeople').append('<img src="' + person.image.url + '">');
                }
            });
        },
        profile: function(){
            gapi.client.plus.people.get({
                'userId': 'me'
            }).then(function(res) {
                var profile = res.result;
                $('#profile').empty();
                $('#profile').append(
                    $('<p><img src=\"' + profile.image.url + '\"></p>'));
                $('#profile').append(
                    $('<p>Hello ' + profile.displayName + '!<br />Tagline: ' +
                        profile.tagline + '<br />About: ' + profile.aboutMe + '</p>'));
                $('#profile').append($('<p>Email: '+profile.emails[0].value+'</p>'));
                $('#profile').append($('<p>Id: '+profile.id+'</p>'));
                if (profile.cover && profile.coverPhoto) {
                    $('#profile').append(
                        $('<p><img src=\"' + profile.cover.coverPhoto.url + '\"></p>'));
                }
            }, function(err) {
                var error = err.result;
                $('#profile').empty();
                $('#profile').append(error.message);
            });
        }
    };
})();

var user_data = {
    name: 'vova',
    surname: 'konstanchuk',
    email: 'japh@ukr.net',
    phone: '23456789',
    role_id: 1,
    token: '3456',
    service: 'google'
};


$(document).ready(function() {
    $('#disconnect').click(auth_g.disconnect);
    $('#loaderror').hide();
    if ($('[data-clientid="955464663389-olgqchpjmpqnhugedsdj7tc6ak08ns0f.apps.googleusercontent.com"]').length > 0) {
    }

    /*test*/

    /*auth.check('3456', 'facebook',
        function() {
            alert('зараз відбудеття реєстрація');
            auth.register(user_data)
        }
    );*/

    /**/
});

function onSignInCallback(authResult) {
    auth_g.onSignInCallback(authResult);
}

