<!doctype html>
<html>
<!-- BEGIN head -->
<head>
    <title>Recover password - LogApp.co</title>
    <link rel="stylesheet" href="<?php echo URL::to('style.css'); ?>">
    <script src="<?php echo URL::to('jquery-min.js'); ?>"></script>
    <link href="//maxcdn.bootstrapcdn.com/bootswatch/3.2.0/flatly/bootstrap.min.css" rel="stylesheet">
</head>
<!-- END head -->

<!-- BEGIN body -->
<body id="login-page">
<a href="<?php echo URL::to('register'); ?>"><button class="register-btn">Register</button></a>
<div class="login-box">
    <div class="login-logo">
    </div>
    <div class="app-title">
        LogApp
    </div>
    <!-- BEGIN login form -->
    <form name="recover" method="post" action="">
        <div class="action-desc">
            Enter your email used with LogApp account to get a new password
        </div>
        <input type="text" name="email" class="login-input<?php if(isset($emailError)): ?>-error<?php endif; ?>" <?php if(isset($_POST['email'])): ?>value="<?php echo $_POST['email']; ?>"<?php endif; ?> placeholder="Email" <?php if(isset($emailError) || (!isset($emailError) && !isset($passwordError) && !isset($invalidLogin))): ?>autofocus<?php endif; ?> autocomplete="off">
        <?php if(isset($emptyEmail)): ?>
            <p class="text-danger login-error">Please enter your email</p>
        <?php elseif(isset($invalidEmail)): ?>
            <p class="text-danger login-error">Please enter a valid email</p>
        <?php endif; ?>
        <input type="submit" class="login-btn" name="login-btn" value="Recover password">
    </form>
    <!-- END login form -->
</div>
<a href="<?php echo URL::to('login'); ?>" class="forgot-password-link">Already have an account? Log In</a>
</body>
<!-- END body -->

</html>