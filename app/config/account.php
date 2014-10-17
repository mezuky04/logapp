<?php
/**
 * Account configuration
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
return array(
    'minPasswordLength' => 8,
    'minPhoneNumberLength' => 8,
    'maxPhoneNumberLength' => 12,
    'minPhoneNumberPrefixLength' => 2,
    'maxPhoneNumberPrefixLength' => 4,
    'maxLoginAttempts' => 8,
    'smsVerificationCodeFrom' => 'LogApp',
    'smsVerificationCodeLength' => 4,
    'maxSmsVerificationCodeAttempts' => 5,
    'smsVerificationCodePeriod' => 3600,
);