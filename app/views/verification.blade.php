@extends('base-layout')
@section('content')
<!-- BEGIN Verification code panel -->
<div class="panel panel-default verification-panel">
    <div class="panel-heading">Two factor authentication</div>
    <div class="panel-body">
        <p>
            Your account has <a href="#">two factor authentication</a> enabled. To continue
            please enter the 4 digits verification code received via sms. If you have not received any
            sms from us, try to <a href="#">resend another verification code</a>. You can disable
            two factor authentication from your account settings page.
        </p>
        <form name="verification-code-form" redirect-url="<?php echo URL::to('/'); ?>" class="verification-code-form" role="form" method="post" action="<?php echo URL::to('verification-code'); ?>">
            <div id="verification-code" class="verification-code-input has-success">
                <input type="text" name="verification-code" class="form-control" placeholder="Verification code">
            </div>
            <button class="verification-code-button btn btn-info">Check</button>
            <div id="verification-code-error" class="text-danger">

            </div>
        </form>
    </div>
</div>
<!-- END Verification code panel -->
@stop