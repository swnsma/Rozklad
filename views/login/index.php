<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<fb:login-button id="fb_login" scope="public_profile,email" onlogin="auth_fb.checkLoginState();">
</fb:login-button>

<script src="public/js/app/login.js"></script>
</body>
</html>