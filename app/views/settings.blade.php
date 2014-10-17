@extends('base-layout')
@section('content')
<!-- BEGIN settings page -->
<div class="settings">

    <!-- BEGIN settings title -->
    <div class="first">
        <p class="settings-title">Settings</p>
    </div>
    <!-- END settings title -->

    <!-- BEGIN Divider -->
    <div class="divider"></div>
    <!-- END Divider -->

    <!-- BEGIN Email setting -->
    <div class="option" id="email-option">
        <div class="details">
            <div class="option-detail">Email</div>
            <div class="option-description"><?php if(isset($currentEmail)) echo $currentEmail; ?></div>
        </div>
        <button class="edit-btn btn btn-default account-settings-btn" expand="email-option">Edit</button>
    </div>
    <!-- END Email setting -->

    <!-- BEGIN Email setting expanded -->
    <div class="option-expanded" id="email-option-expanded">
        <div class="details">
            <div class="option-detail">Email</div>
            <form class="setting-form" method="post" action="<?php echo URL::to('settings/editEmail'); ?>">
                <div class="email-input has-success">
                    <input type="text" name="email" class="form-control" value="<?php if(isset($_POST['email'])) echo $_POST['email']; elseif(isset($currentEmail)) echo $currentEmail; ?>" autocomplete="off"/>
                </div>
                <div class="setting-desc">
                    This email will be used to send you notifications (if are enabled) and contact you for password recover process
                </div>
                @include('includes.setting-option-buttons')
            </form>
        </div>
    </div>
    <!-- END Email setting expanded -->

    <!-- BEGIN Divider -->
    <div class="divider"></div>
    <!-- END Divider -->

    <!-- BEGIN Phone number setting -->
    <div class="option" id="phone-number-option">
        <div class="details">
            <div class="option-detail">Phone number</div>
            <div class="option-description"><?php if(isset($currentPhoneNumber)) echo $currentPhoneNumber; ?></div>
        </div>
        <button class="edit-btn btn btn-default account-settings-btn" expand="phone-number-option">Edit</button>
    </div>
    <!-- END Phone number setting -->

    <!-- BEGIN Phone number setting expanded -->
    <div class="option-expanded" id="phone-number-option-expanded">
        <div class="details">
            <div class="option-detail">Phone number</div>
            <form method="post" action="<?php echo URL::to('settings/editPhoneNumber'); ?>">
                <div class="phone-number-input has-success">
                    <input type="text" name="phone-number" class="form-control" value="0725433317" />
                </div>
                <div class="setting-desc">
                    Your phone number is required if you have enabled two factor verification or SMS notifications enabled
                </div>
                @include('includes.setting-option-buttons')
            </form>
        </div>
    </div>
    <!-- END Phone number setting expanded -->

    <!-- BEGIN Divider -->
    <div class="divider"></div>
    <!-- END Divider -->

    <!-- BEGIN Password setting -->
    <div class="option" id="password-option">
        <div class="details">
            <div class="option-detail">Password</div>
            <div class="option-description"><?php echo $lastPasswordUpdate; ?></div>
        </div>
        <button class="edit-btn btn btn-default account-settings-btn" expand="password-option">Edit</button>
    </div>
    <!-- END Password setting -->

    <!-- BEGIN Password setting expanded -->
    <div class="option-expanded" id="password-option-expanded">
        <div class="details">
            <div class="option-detail">Password</div>
            <form method="post" action="<?php echo URL::to('settings/editPassword'); ?>">
                <div class="password-input has-success">
                    <input type="password" name="current-password" class="form-control" placeholder="Current password">
                    <input type="password" name="new-password" class="form-control" placeholder="New password">
                    <input type="password" name="confirmation-password" class="form-control" placeholder="Confirm new password">
                </div>
                @include('includes.setting-option-buttons')
            </form>
        </div>
    </div>
    <!-- END Password setting expanded -->

    <!-- BEGIN Divider -->
    <div class="divider"></div>
    <!-- END Divider -->

    <!-- BEGIN Two step verification setting -->
    <div class="option">
        <div class="switch-details">
            <div class="option-detail">Two step verification</div>
            <div class="option-description">Increase the security of your account</div>
        </div>
        <form class="two-factor-auth-form" action="<?php echo URL::to('settings/editTwoFactorAuth'); ?>">
            <div class="switch">
                <input name="two-factor-auth" id="cmn-toggle-4" class="cmn-toggle cmn-toggle-round-flat" type="checkbox" value="active" <?php if(isset($twoFactorAuthStatus) && $twoFactorAuthStatus): ?>checked<?php endif; ?>>
                <label for="cmn-toggle-4"></label>
            </div>
        </form>

    </div>
    <!-- END Two step verification setting -->
</div>
<!-- END settings page -->
@stop