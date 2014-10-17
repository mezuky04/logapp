<!doctype html>
<html>

<!-- BEGIN head -->
<head>
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="<?php echo URL::to('style.css'); ?>">
    <script src="<?php echo URL::to('jquery-min.js'); ?>"></script>
    <script src="<?php echo URL::to('javascript/ddslick.jquery.min.js'); ?>"></script>
    <script src="<?php echo URL::to('javascript/countriesDropdown.js'); ?>"></script>
    <link href="//maxcdn.bootstrapcdn.com/bootswatch/3.2.0/flatly/bootstrap.min.css" rel="stylesheet">
</head>
<!-- END head -->

<!-- BEGIN body -->
<body id="register-page">

<!-- BEGIN Countries popup -->
<div class="countries-popup">
    <div class="countries-list">
        <div class="country-item">
            <img class="country-item-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
            <div class="country-item-name">Romania</div>
        </div>
        <div class="country-item">
            <img class="country-item-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
            <div class="country-item-name">Romania</div>
        </div>
        <div class="country-item">
            <img class="country-item-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
            <div class="country-item-name">Romania</div>
        </div>
        <div class="country-item">
            <img class="country-item-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
            <div class="country-item-name">Romania</div>
        </div>
        <div class="country-item">
            <img class="country-item-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
            <div class="country-item-name">Romania</div>
        </div>
        <div class="country-item">
            <img class="country-item-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
            <div class="country-item-name">Romania</div>
        </div>
        <div class="country-item">
            <img class="country-item-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
            <div class="country-item-name">Romania</div>
        </div>
        <div class="country-item">
            <img class="country-item-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
            <div class="country-item-name">Romania</div>
        </div>
        <div class="country-item">
            <img class="country-item-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
            <div class="country-item-name">Romania</div>
        </div>
        <div class="country-item">
            <img class="country-item-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
            <div class="country-item-name">Romania</div>
        </div>
        <div class="country-item">
            <img class="country-item-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
            <div class="country-item-name">Romania</div>
        </div>
        <div class="country-item">
            <img class="country-item-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
            <div class="country-item-name">Romania</div>
        </div>
        <div class="country-item">
            <img class="country-item-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
            <div class="country-item-name">Romania</div>
        </div>
        <div class="country-item">
            <img class="country-item-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
            <div class="country-item-name">Romania</div>
        </div>
        <div class="country-item">
            <img class="country-item-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
            <div class="country-item-name">Romania</div>
        </div>
        <div class="country-item">
            <img class="country-item-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
            <div class="country-item-name">Romania</div>
        </div>
        <div class="country-item">
            <img class="country-item-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
            <div class="country-item-name">Romania</div>
        </div>
        <div class="country-item">
            <img class="country-item-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
            <div class="country-item-name">Romania</div>
        </div>
        <div class="country-item">
            <img class="country-item-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
            <div class="country-item-name">Romania</div>
        </div>
        <div class="country-item">
            <img class="country-item-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
            <div class="country-item-name">Romania</div>
        </div>
        <div class="country-item">
            <img class="country-item-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
            <div class="country-item-name">Romania</div>
        </div>
        <div class="country-item">
            <img class="country-item-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
            <div class="country-item-name">Romania</div>
        </div>
     </div>
    <div class="background"></div>
</div>
<!-- END Countries popup -->

<!-- BEGIN Register button -->
<a href="<?php echo URL::to('login'); ?>"><button class="login-btn">Login</button></a>
<!-- END Register button -->

<!-- BEGIN Register box -->
<div class="register-box">
    <div class="register-logo">

    </div>
    <div class="app-title">
        LogApp
    </div>

    <!-- BEGIN Register form -->
    <form name="register" method="post" action="">

        <!-- BEGIN Email input -->
        <input type="text" name="email" class="register-input<?php if(isset($emailError)): ?>-error<?php endif; ?>" <?php if(isset($_POST['email'])): ?>value="<?php echo $_POST['email']; ?>"<?php endif; ?> placeholder="Email" <?php if(isset($emailError) || (!isset($emailError) && !isset($passwordError) && !isset($invalidLogin))): ?>autofocus<?php endif; ?> autocomplete="off" onfocus="this.value = this.value;">
        <?php if(isset($emptyEmail) && $emptyEmail): ?>
            <p class="text-danger register-error">Please enter your email</p>
        <?php elseif(isset($invalidEmail) && $invalidEmail): ?>
            <p class="text-danger register-error">Please enter a valid email</p>
        <?php endif; ?>
        <!-- END Email input -->

        <!-- BEGIN Password input -->
        <input type="password" name="password" class="register-input<?php if(isset($passwordError) || isset($invalidLogin)): ?>-error<?php endif; ?>" placeholder="Password" <?php if(isset($passwordError) || isset($invalidLogin)): ?>autofocus<?php endif; ?> autocomplete="off">
        <?php if(isset($emptyPassword) && $emptyPassword): ?>
            <p class="text-danger register-error">Please enter your password</p>
        <?php elseif(isset($tooShortPassword) && $tooShortPassword): ?>
            <p class="text-danger register-error">
                <?php if(isset($passwordLength) && $passwordLength): ?>
                    Password should have at least <?php echo $passwordLength; ?> chars
                <?php else: ?>
                    Password is too short
                <?php endif; ?>
            </p>
        <?php elseif(isset($tooSimplePassword) && $tooSimplePassword): ?>
            <p class="text-danger register-error">Password should contain letters and numbers</p>
        <?php elseif(isset($invalidLogin) && $invalidLogin): ?>
            <p class="text-danger register-error">Invalid email or password</p>
        <?php endif; ?>
        <!-- END Password input -->

        <!-- BEGIN Phone number input -->
        <div class="phone-number">
            <div class="choose-country">
                <div class="country">
                    <img class="country-icon" src="<?php echo URL::to('icons/countries/Romania.png'); ?>">
                    <div class="prefix">+40</div>
                </div>
            </div>
            <input type="text" name="phone-number" class="phone-number-inp" placeholder="Phone number">
            <div class="why-phone-number-is-required">
                <a href="#">Why my phone number is needed?</a>
            </div>
        </div>
        <!-- END Phone number input -->

        <!-- BEGIN Terms -->
        <div class="terms">
            <div class="agree-terms">By creating an account you agree to our <a href="#">terms</a> and <a href="#">privacy</a></div>
        </div>
        <!-- END Terms -->

        <!-- BEGIN Submit button -->
        <input type="submit" class="register-button" name="register-btn" value="Register">
        <!-- END Submit button -->

    </form>
    <!-- END Login form -->

</div>
<!-- END Register box -->

<!-- BEGIN Forgot password -->
<span class="register-selected-plan">Free</span> - <a href="<?php echo URL::to('plans'); ?>" class="forgot-password-link">Select another plan</a>
<!-- END Forgot password -->

</body>
<!-- END body -->

</html>