<?php

  session_start();
  if(isset($_SESSION['isAuth'])){
    header("Location: home.php ");
    exit();
  }

?>

<html lang="en">
  <head>
    <!-- https://developers.google.com/identity/sign-in/web/sign-in -->
    
    <!-- Google API -->
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="491184431140-06ob6ri8njp6gu0d3o2a6fknqo94nj4e.apps.googleusercontent.com">

    <link rel="stylesheet" href="public/css/style.css">
  </head>
  <body>
    <div class="container">

      <!-- https://developers.google.com/identity/sign-in/web/build-button -->
      <!-- Google Button -->
      <div id="my-signin2"></div>
    </div>

    <script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <script src="public/js/script.js"></script>
  </body>
</html>
