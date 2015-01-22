<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<fb:login-button id="fbLogin" scope="public_profile,email" onlogin="auth_fb.checkLoginState();">
</fb:login-button>

<div id="status"></div>
<script src="public/js/vendor/jquery-2.1.1.js"></script>
<script src="public/js/app/common.js"></script>
<script src="public/js/app/login.js"></script>
</body>
</html>