<?php

/**
 * Class LoginController
 *
 * Handle user login process
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class LoginController extends BaseController {

    /**
     * @var string Login view name
     */
    private $_loginView = 'login';

    /**
     * @var string User id
     */
    protected $_userId = "";

    /**
     * @var string User email
     */
    private $_email = "";

    /**
     * @var string User password
     */
    private $_password = "";

    /**
     * @var string Redirect url after login
     */
    private $_next = "";

    /**
     * @var string
     */
    private $_loginIp = "";


    /**
     * Display login page or redirect to homepage if user is already logged in
     */
    public function showLoginPage() {

        // Redirect logged in users
        if ($this->_loggedIn) {
            return Redirect::to('home');
        }

        // Render view
        return View::make($this->_loginView);
    }


    /**
     * Handle users login
     *
     * @return mixed
     */
    public function processLogin() {

        // Redirect logged in users
        if ($this->_loggedIn) {
            return Redirect::to('home');
        }

        try {
            // Get login details
            $this->_getLoginDetails();

            // Validate redirect url after login
            $this->_processRedirectUrl();

            // Validate credentials
            $this->_validateInputs();

            // Prevent rapid fire login attempts
            $this->_preventRapidFireLoginAttempts();

            // Check if credentials are valid and login user
            $this->_loginUser();

            // Log user action
            $this->_logUserAction("Logged in");

            // Check if user has two factor auth enabled and send a verification code
            $this->_handleTwoFactorAuth();

            // Check if verification code page should be displayed
            if ($this->_checkVerificationCodePage()) {
                return Redirect::to('verification-code?next='.$this->_next);
            }

            return Redirect::to($this->_next);

        } catch (Exception $e) {
            // todo an exceptions handler
            exit($e->getMessage());
        }
    }


    /**
     * Log user out
     */
    public function logout() {

        // Redirect not logged in users
        if (!$this->_loggedIn) {
            return Redirect::to('home');
        }

        // Delete sessions
        Session::forget('loggedIn');
        Session::forget('verificationCodeRequired');
        Session::forget('user');

        try {
            // Log user action
            $this->_logUserAction("Logged out");

            // Delete user verification codes
            $this->_deleteUserVerificationCodes();
        } catch (Exception $e) {
            // todo an exceptions handler
            exit($e->getMessage());
        }

        return Redirect::to('home');
    }


    /**
     * Get login inputs
     */
    private function _getLoginDetails() {
        $this->_email = Input::get('email');
        $this->_password = Input::get('password');
        $this->_next = Input::get('next');
    }


    /**
     * Validate user email and password
     *
     * @return mixed
     */
    private function _validateInputs() {
        // Check for email and password to not be empty
        if (empty($this->_email)) {
            exit(View::make($this->_loginView, array('emptyEmail' => true, 'emailError' => true)));
        }
        if (empty($this->_password)) {
            exit(View::make($this->_loginView, array('emptyPassword' => true, 'passwordError' => true)));
        }

        // Invalid email
        if (!filter_var($this->_email, FILTER_VALIDATE_EMAIL)) {
            exit(View::make($this->_loginView, array('invalidEmail' => true, 'emailError' => true)));
        }
    }

    /**
     * Handle brute force attacks protection
     */
    private function _preventRapidFireLoginAttempts() {
        // Get login ip
        $this->_getLoginIp();

        $this->_checkLoginAttempts();
    }


    /**
     * Get login ip
     */
    private function _getLoginIp() {
        $this->_loginIp = Request::getClientIp();
    }


    /**
     * Check login attempts
     */
    private function _checkLoginAttempts() {
        $loginAttemptsModel = new LoginAttemptsModel();
        if ($loginAttemptsModel->countAttempts($this->_email)) {
            exit("Too much login attempts. Try again later");
        }
    }


    /**
     * Validate user email/password combination and save their details in session
     */
    private function _loginUser() {
        $usersModel = new UsersModel();
        $loginAttemptsModel = new LoginAttemptsModel();

        $user = $usersModel->getUser($this->_email);
        if (!$user) {
            exit(View::make($this->_loginView, array('invalidLogin' => true)));
        }

        if (!Hash::check($this->_password, $user->Password)) {
            // Incorrect password, insert in login attempts
            $loginAttemptsModel->log($this->_email);

            // Return view with proper message
            exit(View::make($this->_loginView, array('invalidLogin' => true)));
        }

        // Make user id available in all classes
        $this->_userId = $user->UserId;

        // Clear login attempts for this user and ip
        $loginAttemptsModel->clean($this->_email);

        // Valid credentials, log in user
        Session::put('user', array(
            'UserId' => $user->UserId,
            'Email' => $user->Email,
        ));
        Session::put('loggedIn', true);
    }


    /**
     * Check if user has two factor auth enabled and send a verification code
     */
    private function _handleTwoFactorAuth() {

        $usersModel = new UsersModel();
        if (!$usersModel->getUserTwoFactorAuthStatus($this->_userId)) {
            // Two factor auth is disabled
            return;
        }

        // Two factor auth is enabled
        $twoFactorAuthVerificationCodesModel = new TwoFactorAuthVerificationCodesModel();
        $twoFactorAuthVerificationCodesModel->sendTwoFactorAuthVerificationCode($this->_email);
        Session::put('verificationCodeRequired', true);
    }


    /**
     * Check if two factor auth verification code page should be displayed or not
     *
     * @return bool
     */
    private function _checkVerificationCodePage() {

        $twoFactorAuthVerificationCodesModel = new TwoFactorAuthVerificationCodesModel();
        if ($twoFactorAuthVerificationCodesModel->checkIfVerificationCodeIsRequired($this->_userId)) {
            // Verification code is not required
            return false;
        }
        return true;
    }


    /**
     * Delete user old verification codes
     */
    private function _deleteUserVerificationCodes() {
        $twoFactorAuthVerificationCodesModel = new TwoFactorAuthVerificationCodesModel();
        $twoFactorAuthVerificationCodesModel->deleteOldVerificationCodes($this->_userId);
    }


    /**
     * Validate redirect url after login
     */
    private function _processRedirectUrl() {

        $dividedUrl = parse_url($this->_next);
        if (!isset($dividedUrl['scheme'])) {
            return;
        }

        $this->_next = '/';
        return;
    }
}