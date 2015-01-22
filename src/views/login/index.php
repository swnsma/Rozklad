<!DOCTYPE html>
<html>
<head>
    <title>dd</title>
</head>
<!--тут-->
<script type="text/javascript">
    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/client:plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();
</script>
<!-- -->
<body>
<?php print $data ?>
<fb:login-button id="fbLogin" scope="public_profile,email" onlogin="auth_fb.checkLoginState();">
</fb:login-button>

<!--тут-->

<span id="signinButton">
  <span
      class="g-signin"
      data-callback="signinCallback"
      data-clientid="CLIENT_ID"
      data-cookiepolicy="single_host_origin"
      data-requestvisibleactions="http://schemas.google.com/AddActivity"
      data-scope="https://www.googleapis.com/auth/plus.login">
  </span>
</span>
<!-- -->
<div id="status"></div>
<script src="public/js/vendor/jquery-2.1.1.js"></script>
<script src="public/js/app/common.js"></script>
<script src="public/js/app/login.js"></script>
</body>

</html>