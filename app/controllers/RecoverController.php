<?php

/**
 * Class RecoverController
 *
 * Handle users password recover process
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class RecoverController extends BaseController {

    /**
     * @var string Recover layout name
     */
    private $_recoverLayout = 'recover';

    /**
     * @var string Set new password layout name
     */
    private $_setNewPasswordLayout = 'set-new-password';

    /**
     * @var string Config file name
     */
    private $_configName = 'account.';

    /**
     * @var int Length of recover code
     */
    private $_recoverCodeLength = 32;


    /**
     * Display recover page
     */
    public function index() {

        // Redirect logged in users
        if ($this->_loggedIn) {
            return Redirect::to('/');
        }

        return View::make($this->_recoverLayout);
    }


    /**
     * Process password recover
     *
     * @return mixed
     */
    public function recover() {

        // Redirect logged in users
        if ($this->_loggedIn) {
            return Redirect::to('/');
        }

        $email = Input::get('email');

        if (empty($email)) {
            // Empty email
            return View::make($this->_recoverLayout, array('emptyEmail' => true, 'emailError' => true));
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Invalid email format
            return View::make($this->_recoverLayout, array('invalidEmail' => true, 'emailError' => true));
        }

        $usersModel = new UsersModel();
        if (!$usersModel->check('Email', $email)) {
            // Email not exist in database
            return View::make($this->_recoverLayout, array('recoverMessageSent' => true));
        }



        // todo Send recover message with instructions
    }

    public function verify($userId, $recoverCode) {

        // Redirect logged in users
        if ($this->_loggedIn) {
            return Redirect::to('/');
        }

        if (!is_numeric($userId)) {
            // Log somewhere this action
            return Redirect::to('/');
        }

        if (strlen($recoverCode) > $this->_recoverCodeLength) {
            // And this
            return Redirect::to('/');
        }

        // Check if user id/verification code combination is correct
        $passwordRecoversModel = new PasswordRecoversModel();
        if (!$passwordRecoversModel->checkVerificationCode($userId, $recoverCode)) {
            return Redirect::to('/');
        }

        // Valid recover code, show new password view
        return View::make($this->_setNewPasswordLayout);
    }

    public function setNewPassword($userId, $recoverCode) {

        // Redirect logged in users
        if ($this->_loggedIn) {
            return Redirect::to('/');
        }

        if (!is_numeric($userId)) {
            // Log somewhere this action
            return Redirect::to('/');
        }

        if (strlen($recoverCode) > $this->_recoverCodeLength) {
            // And this
            return Redirect::to('/');
        }

        $newPassword = Input::get('new-password');
        $confirmPassword = Input::get('confirm-password');

        if (empty($newPassword)) {
            return View::make($this->_setNewPasswordLayout, array('newPasswordError' => true, 'emptyNewPassword' => true));
        }
        if (strlen($newPassword) < Config::get($this->_configName.'minPasswordLength')) {
            return View::make($this->_setNewPasswordLayout, array('newPasswordError' => true, 'tooShortNewPassword' => true));
        }
        if ($confirmPassword !== $newPassword) {
            return View::make($this->_setNewPasswordLayout, array('confirmPasswordError' => true, 'passwordsDoesNotMatch' => true));
        }

        // todo Save new password
    }
}