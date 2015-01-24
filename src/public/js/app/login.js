var auth_fb = (function() {

    function statusChangeCallback(response) {
        console.log('statusChangeCallback');
        console.log(response);
        if (response.status === 'connected') {
            testAPI();
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
            appId      : '1536442079974268',
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

var auth_gm = (function() {
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
                    auth_gm.profile();
                    auth_gm.people();
                } else if (authResult['error']) {
                    // There was an error, which means the user is not signed in.
                    // As an example, you can handle by writing to the console:
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

$(document).ready(function() {
    $('#disconnect').click(auth_gm.disconnect);
    $('#loaderror').hide();
    if ($('[data-clientid="955464663389-olgqchpjmpqnhugedsdj7tc6ak08ns0f.apps.googleusercontent.com"]').length > 0) {
    }
});

function onSignInCallback(authResult) {
    auth_gm.onSignInCallback(authResult);
}

