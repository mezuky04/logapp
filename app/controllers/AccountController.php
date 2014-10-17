<?php

/**
 * Class AccountController
 *
 * Handle account tasks like login, register, edit
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class AccountController extends BaseController {

    /**
     * @var string Login view name
     */
    private $_loginView = 'login';

    /**
     * @var string Register view name
     */
    private $_registerView = 'register';

    /**
     * @var string Setting view name
     */
    private $_settingsLayout = 'settings';

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
     * Display register page or redirect to homepage if user is already logged in
     */
    public function showRegisterPage() {

        // Redirect logged in users
        if ($this->_loggedIn) {
            return Redirect::to('home');
        }

        // Render view
        return View::make($this->_registerView);
    }


    /**
     * Display settings page
     *
     * @return mixed
     */
    /*public function showSettingsPage() {

        // Redirect not logged in users
        if (!$this->_loggedIn) {
            return Redirect::to('home');
        }

        // Render view
        return View::make($this->_settingsLayout);
    }*/

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

        // Get login details
        $email = Input::get('email');
        $password = Input::get('password');

        $next = Input::get('next');

        // Validate redirect url after login
        $this->_processRedirectUrl($next);

        // Check for email and password to not be empty
        if (empty($email)) {
            return View::make($this->_loginView, array('emptyEmail' => true, 'emailError' => true));
        }
        if (empty($password)) {
            return View::make($this->_loginView, array('emptyPassword' => true, 'passwordError' => true));
        }

        // Invalid email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return View::make($this->_loginView, array('invalidEmail' => true, 'emailError' => true));
        }

        $usersModel = new UsersModel();

        // Check if given email exists in database
        if (!$usersModel->check('Email', $email)) {
            return View::make($this->_loginView, array('invalidLogin' => true, 'passwordError' => true));
        }

        // todo Log login information (timestamp, user agent, etc)
        // todo Check for brute force attacks


        // Email exists, check the password now
        $user = $usersModel->getUser($email);
        if (!Hash::check($password, $user->Password)) {
            return View::make($this->_loginView, array('invalidLogin' => true));
        }

        // Valid credentials, log in user
        Session::put('user', array(
            'UserId' => $user->UserId,
            'Email' => $user->Email,
        ));
        Session::put('loggedIn', true);

        return Redirect::to($next);
    }


    /**
     * Handle users registration
     *
     * @return mixed
     */
    public function processRegistration() {

        // Redirect logged in users
        if ($this->_loggedIn) {
            return Redirect::to('home');
        }

        $email = Input::get('email');
        $password = Input::get('password');

        // Check if all fields was completed
        if (empty($email)) {
            return View::make($this->_registerView, array('emptyEmail' => true));
        }
        if (empty($password)) {
            return View::make($this->_registerView, array('emptyPassword' => true));
        }

        // Check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return View::make($this->_registerView, array('invalidEmail' => true));
        }

        // Check for password length
        if (strlen($password) < 8) {
            return View::make($this->_registerView, array('tooShortPassword' => true));
        }

        // Check if email is already used
        $usersModel = new UsersModel();
        if ($usersModel->getOne(array('Email'), array('Email' => $email))) {
            return View::make($this->_registerView, array('alreadyUsedEmail' => true));
        }

        // Insert user information in database
        $user = $usersModel->saveUser($email, $password);

        // Send verification email

        // Make user logged in
        Session::put('loggedIn', true);
        Session::put('user', $user);

        // Redirect user to homepage
        return Redirect::to('home');
    }


    /**
     * Generate api key
     */
    public function generateAPIKey() {

        // Redirect not logged in users
        if (!$this->_loggedIn) {
            return Redirect::to('home');
        }

        // Generate and save api key
        $apiKeysModel = new ApiKeysModel();
        $apiKeysModel->generateAPIKey($this->_userId);
    }


    /**
     * Generate a new api key and delete the old one
     */
    public function regenerateAPIKey($oldApiKey) {

        // Redirect not logged in users
        if (!$this->_loggedIn) {
            return Redirect::to('home');
        }

        // Delete old api key
        $apiKeysModel = new ApiKeysModel();
        $apiKeysModel->deleteApiKey($oldApiKey);

        // Generate new api key
        $apiKeysModel->generateAPIKey($this->_userId);
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
        Session::forget('user');

        return Redirect::to('home');
    }


    /**
     * Delete user account
     */
    public function deleteAccount() {
        //
    }


    /**
     * Log in database information about login
     */
    private function logLogin() {
        //
    }


    /**
     * Log in database information about register process
     */
    private function logRegister() {
        //
    }


    /**
     *
     */
    private function logPasswordRecover() {
        //
    }


    /**
     * Check the given url to not be the url of another website
     *
     * @param string $url To be checked
     */
    private function _processRedirectUrl(&$url) {

        $dividedUrl = parse_url($url);
        if (!isset($dividedUrl['scheme'])) {
            return;
        }

        $url = '/';

        return;
    }
}