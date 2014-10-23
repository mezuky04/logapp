<?php

/**
 * Class SmsHelper
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class SmsHelper {

    /**
     * @var string Account config file name
     */
    private $_accountConfig = 'account.';

    /**
     * @var string Sms config file name
     */
    private $_smsConfig = 'sms';


    public function sendRegisterSmsVerificationCode($phoneNumber) {
        // Validate phone number
        if (!ctype_digit($phoneNumber)) {
            throw new Exception("Register sms verification code was not sent because given phone number is invalid");
        }

        // Generate random verification code and save in database
        $smsVerificationCode = substr(str_shuffle('0123456789'), 0, Config::get($this->_accountConfig.'smsVerificationCodeLength'));
        $nexmoSms = new NexmoSms(Config::get($this->_smsConfig.'apiKey'), Config::get($this->_smsConfig.'spiSecret'));
        $nexmoSms->setSMSParams(array('type' => 'text'));
        $message = 'Your LogApp verification code is '.$smsVerificationCode;
        $from = Config::get($this->_accountConfig.'smsVerificationCodeFrom');
        $nexmoSms->sendSMS($from, $phoneNumber, $message);
    }
}