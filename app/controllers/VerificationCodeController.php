<?php

/**
 * Class VerificationCodeController
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class VerificationCodeController extends BaseCOntroller {

    /**
     * @var string Page title
     */
    private $_pageTitle = "Two factor authentication";

    /**
     * @var string Page body id
     */
    private $_bodyId = "verification-code-page";

    /**
     * @var string Verification view name
     */
    private $_verificationView = 'verification';

    /**
     * @var string Verification code entered by the user
     */
    private $_verificationCode = "";

    /**
     * @var string Fail status
     */
    private $_failStatus = "fail";

    /**
     * @var string Success status
     */
    private $_successStatus = "success";

    /**
     * @var string Account config file name
     */
    private $_accountConfig = "account.";


    /**
     * Render verification page
     *
     * @return mixed
     */
    public function index() {

        // Redirect not logged in users
        if (!$this->_loggedIn) {
            return Redirect::to('/');
        }

        // Check if a verification code was sent to the user
        if (!$this->_isVerificationCodeRequired()) {
            return Redirect::to('/');
        }

        // Render view
        return $this->_renderVerificationCodeView();
    }


    /**
     * Process given verification code
     *
     * @return mixed
     */
    public function processVerificationCode() {

        // Redirect not logged in users
        if (!$this->_loggedIn) {
            return Redirect::to('/');
        }

        // Check if a verification code was sent to the user
        if (!Session::get('verificationCodeRequired')) {
            return Redirect::to('/');
        }

        // Check if user reached his limit of wrong consecutive verification codes
        $this->_checkConsecutiveFailedAttempts();

        // Get verification code
        $this->_getVerificationCode();

        // Validate verification code
        $this->_validateVerificationCode();

        // Check if verification code is valid
        $this->_checkVerificationCode();
    }


    /**
     * Get verification code entered by the user
     */
    private function _getVerificationCode() {
        $this->_verificationCode = Input::get('verification-code');
    }


    /**
     * Validate verification code
     */
    private function _validateVerificationCode() {

        // Check for verification code to not be empty
        if (!$this->_verificationCode) {
            $this->_returnResponse($this->_failStatus, "Empty verification code");
        }

        // Check if verification code contain only digit chars
        if (!ctype_digit($this->_verificationCode)) {
            $this->_returnResponse($this->_failStatus, "Invalid verification code");
        }

        // Check if verification code is too short
        if (strlen($this->_verificationCode) < Config::get($this->_accountConfig.'smsVerificationCodeLength')) {
            $this->_returnResponse($this->_failStatus, "Verification code too short");
        }

        // Check if verification code is too long
        if (strlen($this->_verificationCode) > Config::get($this->_accountConfig.'smsVerificationCodeLength')) {
            $this->_returnResponse($this->_failStatus, "Verification code too long");
        }
    }


    /**
     * Check if given verification code exists in database
     */
    private function _checkVerificationCode() {

        $twoFactorAuthVerificationCodesModel = new TwoFactorAuthVerificationCodesModel();
        if ($twoFactorAuthVerificationCodesModel->checkTwoFactorAuthVerificationCode($this->_verificationCode, $this->_userId)) {
            //Session::put('verificationCodeRequired', false);
            Session::forget('verificationCodeRequired');
            $this->_returnResponse($this->_successStatus, "Verification code okk");
        }

        // Save wrong verification code in database
        $twoFactorAuthLogsModel = new TwoFactorAuthLogsModel();
        $twoFactorAuthLogsModel->saveFailedLog($this->_userId);
        $this->_returnResponse($this->_failStatus, "Invalid verification code");
    }


    /**
     * @return bool
     */
    private function _checkConsecutiveFailedAttempts() {

        $twoFactorAuthLogsModel = new TwoFactorAuthLogsModel();
        if (!$twoFactorAuthLogsModel->checkFailedAttempts($this->_userId)) {
            // There are not too many failed attempts
            return true;
        }

        // To many failed attempts
        $this->_returnResponse($this->_failStatus, "Too much attempts. Please try again later");
    }


    /**
     * Check if verification code is required
     *
     * @return bool
     */
    private function _isVerificationCodeRequired() {

        $twoFactorAuthVerificationCodesModel = new TwoFactorAuthVerificationCodesModel();
        if ($twoFactorAuthVerificationCodesModel->checkIfVerificationCodeIsRequired($this->_userId)) {
            return true;
        }
        return false;
    }


    /**
     * Render verification code view with the given variables
     *
     * @param array $variables
     * @return mixed
     */
    private function _renderVerificationCodeView($variables = array()) {
        $variables['pageTitle'] = $this->_pageTitle;
        $variables['bodyId'] = $this->_bodyId;
        return View::make($this->_verificationView, $variables);
    }


    /**
     * Output in json format given status and message
     *
     * @param string $status
     * @param string $message
     */
    private function _returnResponse($status, $message) {
        Session::forget('verificationCodeRequired');
        exit(json_encode(array(
            'status' => $status,
            'message' => $message
        )));
    }
}