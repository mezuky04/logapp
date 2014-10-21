<?php

/**
 * Class UserHandler
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class UserHandler {

    public function onRegister($user) {
        // Send confirmation email
        $mailHelper = new MailHelper();
        $mailHelper->sendConfirmationEmail($user['email'], $user['subscriptionPlan']);

        // Send sms confirmation code
    }
}