<?php
session_start();
if (isset($_SESSION['cid'])) {
  header("location: ../index.php");
}
?>

<!doctype html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" /> -->
</head>

<body>
  <div class="container">
    <div class="blueBg">
      <div class="box signin">
        <h2>Already Have an Account ?</h2>
        <button class="signinBtn">Sign in</button>
      </div>
      <div class="box signup">
        <h2>Don't Have an Account ?</h2>
        <button class="signupBtn">Sign up</button>
      </div>
    </div>
    <div class="formBx">
      <div class="form signinForm">
        <form method="POST" enctype="multipart/form-data">
          <h3>Sign In</h3>
          <div>
            <input type="text" name="email" placeholder="Username" required>
          </div>
          <div>
            <input type="password" name="pwd" placeholder="Password" required>
          </div>
          <div class="signInBtn">
            <input type="submit" name="" value="Login">
          </div>
          <div class="error-text"></div>
          <a href="#" class="forgot">Forgot Password</a>
        </form>
      </div>
      <div class="form signupForm">
        <form action="#" method="POST" enctype="multipart/form-data">
          <h3>Sign Up</h3>
          <div>
            <input type="text" name="fullName" placeholder="Full Name" required>
          </div>
          <div>
            <input type="text" name="userName" placeholder="Username" required>
          </div>
          <div>
            <input type="email" name="email" placeholder="Email Address" required>
          </div>
          <div>
            <input type="password" name="pwd" placeholder="Password" required>
          </div>
          <div>
            <input type="password" name="confirmPwd" placeholder="Confirm Password" required>
          </div>
          <div class="signUpBtn">
            <input type="submit" name="submit" value="Sign Up">
          </div>
          <div class="error-text"></div>
        </form>
      </div>
    </div>
  </div>
  <script src="js/index.js"></script>
  <script src="js/signUp.js"></script>
  <script src="js/signIn.js"></script>

  <body>

</html>