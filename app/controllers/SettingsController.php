<?php

/**
 * Class SettingsController
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class SettingsController extends BaseController {

    /**
     * @var string Email input
     */
    private $_emailInput = '';

    /**
     * @var string Current password input
     */
    private $_currentPasswordInput = '';

    /**
     * @var string New password input
     */
    private $_newPasswordInput = '';

    /**
     * @var string Confirmation password input
     */
    private $_confirmPasswordInput = '';

    /**
     * @var string Phone number input
     */
    private $_phoneNumberInput = '';

    /**
     * @var int Minimum password length
     */
    private $_minPasswordLength = null;

    /**
     * @var int Minimum phone number length
     */
    private $_minPhoneNumberLength = null;

    /**
     * @var int Maximum phone number length
     */
    private $_maxPhoneNumberLength = null;

    /**
     * @var string Account config file
     */
    private $_accountConfig = 'account.';

    /**
     * @var string Settings layout name
     */
    private $_settingsLayout = 'settings';


    /**
     * Initialize stuff
     */
    public function __construct() {

        parent::__construct();

        // Set config values
        $this->_getConfigValues();
    }


    /**
     * Render settings page
     *
     * @return mixed
     */
    public function index() {

        // Redirect not logged in users
        if (!$this->_loggedIn) {
            return Redirect::to('login?next=settings');
        }

        // Log user action
        try {
            $userActionLogsModel = new UserActionLogsModel();
            $userActionLogsModel->logAction("Accessed settings page", $this->_userId, 2);
        } catch (Exception $e) {
            // todo an exception handler
            exit($e->getMessage());
        }

        return View::make($this->_settingsLayout, $this->_getUserSettings());
    }


    /**
     * Edit user email
     */
    public function editEmail() {
        // Check user to be logged in
        $this->_checkIfUserIsLoggedIn();

        // Get new email
        $this->_getEmail();

        // Validate email
        $this->_validateEmail();

        // Update email
        $this->_updateEmail();

        // Update session
        $this->_updateEmailInSession();

        try {
            // Log user action
            $userActionLogsModel = new UserActionLogsModel();
            $userActionLogsModel->logAction("Email edited", $this->_userId);
        } catch (Exception $e) {
            exit($e->getMessage());
            // todo an exception handler
        }

        $this->_returnResponse('success', 'Email updated!', array('newEmail' => $this->_emailInput));
    }


    /**
     * Edit user password
     */
    public function editPassword() {
        // Check user to be logged in
        $this->_checkIfUserIsLoggedIn();

        // Get new password
        $this->_getNewPassword();

        // Validate new password
        $this->_validatePassword();

        // Update password
        $this->_updatePassword();
    }


    /**
     * Edit user phone number
     */
    public function editPhoneNumber() {
        // Check user to be logged in
        $this->_checkIfUserIsLoggedIn();

        // Get new phone number
        $this->_getPhoneNumber();

        // Validate new phone number
        $this->_validatePhoneNumber();

        // Update phone number
        $this->_updatePhoneNumber();
    }


    /**
     * Enable and disable two factor authentication
     */
    public function editTwoFactorAuth() {

        $this->_checkIfUserIsLoggedIn();

        $twoFactorAuth = Input::get('two-factor-auth');

        if ($twoFactorAuth === 'active') {
            // Enable two factor auth
            $this->_usersModel->handleTwoFactorAuthStatus($this->_userId, 1);
            $this->_returnResponse('success', 'Two factor authentication was enabled');
        }
        // Disable two factor auth
        $this->_usersModel->handleTwoFactorAuthStatus($this->_userId, 0);
        $this->_returnResponse('success', 'Two factor authentication was disabled');
    }


    /**
     * Get user email
     */
    private function _getEmail() {
        $this->_emailInput = Input::get('email');
    }


    /**
     * Validate the given email
     */
    private function _validateEmail() {

        // Check email to don't be empty
        if (empty($this->_emailInput)) {
            $this->_returnResponse('fail', 'Please enter your new email');
        }

        // Check email to be in valid format
        if (!filter_var($this->_emailInput, FILTER_VALIDATE_EMAIL)) {
            $this->_returnResponse('fail', 'Please enter a valid email');
        }

        // Check if email is already used
        if ($this->_usersModel->checkEmail('Email', $this->_emailInput)) {
            $this->_returnResponse('fail', 'This email is already used by another user');
        }
    }


    /**
     * Update user email in database
     */
    private function _updateEmail() {
        $this->_usersModel->update(array('Email' => $this->_emailInput), array('UserId' => $this->_userId));
    }


    /**
     * Update user email in user session
     */
    private function _updateEmailInSession() {
        $userSession = Session::get('user');
        $userSession['Email'] = $this->_emailInput;
        Session::forget('user');
        Session::put('user', $userSession);
        $userSession = Session::get('user');
        //exit(json_encode($userSession));
    }


    /**
     * Get user new password
     */
    private function _getNewPassword() {
        $this->_currentPasswordInput = Input::get('current-password');
        $this->_newPasswordInput = Input::get('new-password');
        $this->_confirmPasswordInput = Input::get('confirmation-password');
    }


    /**
     * Validate user new password
     */
    private function _validatePassword() {

        // Check the current password to don't be empty
        if (!$this->_currentPasswordInput) {
            $this->_returnResponse('fail', 'Please enter your current password');
        }
        if (!$this->_newPasswordInput) {
            $this->_returnResponse('fail', 'Please enter a new password');
        }
        if (!$this->_confirmPasswordInput) {
            $this->_returnResponse('fail', 'Please enter your new password again');
        }

        // Check if given current password is valid
        $currentPasswordHash = $this->_usersModel->getUserPassword($this->_userEmail);
        if (!Hash::check($this->_currentPasswordInput, $currentPasswordHash)) {
            $this->_returnResponse('fail', 'Your current password is not valid');
        }

        if (strleng($this->_newPasswordInput) < $this->_minPasswordLength) {
            $this->_returnResponse('fail', 'The new password should have at least '.$this->_minPasswordLength.' characters');
        }

        if ($this->_newPasswordInput !== $this->_confirmPasswordInput) {
            $this->_returnResponse('fail', 'Password confirmation does not match the new password');
        }
    }


    /**
     * Update user password in database
     */
    private function _updatePassword() {
        $this->_usersModel->update(array('Password' => Hash::make($this->_newPasswordInput)), array('UserId', $this->_userId));
    }


    /**
     * Get user phone number
     */
    private function _getPhoneNumber() {
        $this->_phoneNumberInput = Input::get('phone-number');
    }


    /**
     * Validate phone number
     */
    private function _validatePhoneNumber() {
        if (empty($this->_phoneNumberInput)) {
            $this->_returnResponse('fail', 'Please enter your phone number');
        }
        if (!ctype_digit($this->_phoneNumberInput)) {
            $this->_returnResponse('fail', 'Please enter a valid phone number');
        }
        if (strlen($this->_phoneNumberInput) < $this->_minPhoneNumberLength) {
            $this->_returnResponse('fail', 'Phone number should have at least '.$this->_minPhoneNumberLength.' digits length');
        }
        if (strlen($this->_phoneNumberInput) > $this->_maxPhoneNumberLength) {
            $this->_returnResponse('fail', 'Phone number can not have more than '.$this->_maxPhoneNumberLength.' digits length');
        }
    }


    /**
     * Update phone number in database
     */
    private function _updatePhoneNumber() {
        $phoneNumbersModel = new PhoneNumbersModel();
        $phoneNumbersModel->update(array('PhoneNumber' => $this->_phoneNumberInput), array('UserId' => $this->_userId));
    }


    /**
     * Check if user is logged in
     */
    private function _checkIfUserIsLoggedIn() {
        if (!$this->_loggedIn) {
            $this->_returnResponse('fail', 'Please log in to continue', array('redirect' => URL::to('login?next=settings')));
        }
    }


    /**
     * Get current user settings
     *
     * @return array
     */
    private function _getUserSettings() {
        // Get current setting details from database
        $userSettings = $this->_usersModel->getUserSettings($this->_userId);
        $settings = array();

        // Get current email
        $settings['currentEmail'] = $userSettings->Email;

        // Get current phone number
        if (!isset($userSettings->PhoneNumber)) {
            $settings['currentPhoneNumber'] = "You don't have a phone number set";
        } else {
            $settings['currentPhoneNumber'] = $userSettings->PhoneNumber;
        }

        // Get last password update time
        if ($userSettings->Updated === 'No') {
            $settings['lastPasswordUpdate'] = "Never updated";
        } else {
            $settings['lastPasswordUpdate'] = $this->_getPasswordLastUpdateTime($userSettings->Timestamp);
        }

        // Get two factor auth option status
        $settings['twoFactorAuthStatus'] = $this->_usersModel->getUserTwoFactorAuthStatus($this->_userId);

        return $settings;
    }


    /**
     * Get time in human format passed since the given timestamp
     *
     * @param string $timestamp
     * @return string
     */
    private function _getPasswordLastUpdateTime($timestamp) {
        $time = strtotime($timestamp);
        // Get the time since that moment
        $time = time() - $time;

        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) {
                continue;
            }
            $numberOfUnits = floor($time / $unit);
            return 'Updated ' . $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'') . ' ago';
        }
    }


    /**
     * Set values from config file
     */
    private function _getConfigValues() {
        $this->_minPasswordLength = Config::get($this->_accountConfig.'minPasswordLength');
        $this->_minPhoneNumberLength = Config::get($this->_accountConfig.'minPhoneNumberLength');
        $this->_maxPhoneNumberLength = Config::get($this->_accountConfig.'maxPhoneNumberLength');
    }


    /**
     * Output a response
     *
     * @param string $status
     * @param string $message
     * @param array $otherFields
     */
    private function _returnResponse($status, $message, $otherFields = array()) {
        $response = array(
            'status' => $status,
            'message' => $message
        );
        if (count($otherFields)) {
            $response = array_merge($response, $otherFields);
        }
        exit(json_encode($response));
    }
}