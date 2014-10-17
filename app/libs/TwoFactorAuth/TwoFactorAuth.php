<?php

/**
 * Class TwoFactorAuth
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class TwoFactorAuth {
    private $_from = "";
    private $_to = "";
    private $_content = "";
    private $_nexmoApiKey = "";
    private $_nexmoApiSecret = "";
    private $_smsConfig = 'sms.';

    public function __construct() {
        // Get config values
        $this->_getApiCredentials();
    }

    public function sendLoginCode($userId) {

        $this->_setConfigValues();

        // Generate unique verification code

        // Initialize NexmoSms library
        $nexmoSms = new NexmoSms($this->_nexmoApiKey, $this->_nexmoApiSecret);
        // Send sms
        $nexmoSms->sendSms($from, $to, $content);
    }

    private function _setConfigValues() {
        $this->_from = Config::get($this->_smsConfig.'from');
    }




    /**
     * Get api details from config file
     *
     * @throws Exception
     */
    private function _getApiCredentials() {
        $this->_nexmoApiKey = Config::get($this->_smsConfig.'apiKey');
        if (empty($this->_nexmoApiKey)) {
            throw new Exception("Empty Nexmo api key");
        }
        $this->_nexmoApiSecret = Config::get($this->_smsConfig.'apiSecret');
        if (empty($this->_nexmoApiSecret)) {
            throw new Exception("Empty Nexmo api secret");
        }
    }
}