<!doctype html>
<html>

<!-- BEGIN head -->
<head>
    <title>Login - LogApp.co</title>
    <link rel="stylesheet" href="<?php echo URL::to('style.css'); ?>">
    <script src="<?php echo URL::to('jquery-min.js'); ?>"></script>
    <link href="//maxcdn.bootstrapcdn.com/bootswatch/3.2.0/flatly/bootstrap.min.css" rel="stylesheet">
</head>
<!-- END head -->

<!-- BEGIN body -->
<body id="login-page">

    <!-- BEGIN Register button -->
    <a href="<?php echo URL::to('register'); ?>"><button class="register-btn">Register</button></a>
    <!-- END Register button -->

    <!-- BEGIN Login box -->
    <div class="login-box">
        <div class="login-logo">

        </div>
        <div class="app-title">
            LogApp
        </div>

        <!-- BEGIN Login form -->
        <form name="login" method="post" action="">

            <!-- BEGIN Email input -->
            <input type="text" name="email" class="login-input<?php if(isset($emailError)): ?>-error<?php endif; ?>" <?php if(isset($_POST['email'])): ?>value="<?php echo $_POST['email']; ?>"<?php endif; ?> placeholder="Email" <?php if(isset($emailError) || (!isset($emailError) && !isset($passwordError) && !isset($invalidLogin))): ?>autofocus<?php endif; ?> autocomplete="off">
            <?php if(isset($emptyEmail) && $emptyEmail): ?>
                <p class="text-danger login-error">Please enter your email</p>
            <?php elseif(isset($invalidEmail) && $invalidEmail): ?>
                <p class="text-danger login-error">Please enter a valid email</p>
            <?php endif; ?>
            <!-- END Email input -->

            <!-- BEGIN Password input -->
            <input type="password" name="password" class="login-input<?php if(isset($passwordError) || isset($invalidLogin)): ?>-error<?php endif; ?>" placeholder="Password" <?php if(isset($passwordError) || isset($invalidLogin)): ?>autofocus<?php endif; ?> autocomplete="off">
            <?php if(isset($emptyPassword) && $emptyPassword): ?>
                <p class="text-danger login-error">Please enter your password</p>
            <?php elseif(isset($invalidLogin) && $invalidLogin): ?>
                <p class="text-danger login-error">Invalid email or password</p>
            <?php endif; ?>
            <!-- END Password input -->

            <!-- BEGIN Submit button -->
            <input type="submit" class="login-button" name="login-btn" value="Log In">
            <!-- END Submit button -->

        </form>
        <!-- END Login form -->

    </div>
    <!-- END Login box -->

    <!-- BEGIN Forgot password -->
    <a href="<?php echo URL::to('recover'); ?>" class="forgot-password-link">Forgot password?</a>
    <!-- END Forgot password -->

</body>
<!-- END body -->

</html>