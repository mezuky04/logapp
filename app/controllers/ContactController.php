<?php

/**
 * Class ContactController
 *
 * Contact page
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class ContactController extends BaseController {

    /**
     * @var string Contact view name
     */
    private $_contactView = 'contact';

    /**
     * @var string Contact setting name
     */
    private $_contactConfig = 'contact.';


    /**
     * Display contact page
     *
     * @return mixed
     */
    public function showContactPage() {

        // Display only contact page for not logged in users
        if (!$this->_loggedIn) {
            return View::make($this->_contactView);
        }

        // Display contact page with more information for logged in users
        return View::make($this->_contactView);
    }


    /**
     * Validate and send contact message
     */
    public function sendContactMessage() {

        // Get contact information
        $subject = Input::get('subject');
        $name = Input::get('name');
        $email = Input::get('email');
        $message = Input::get('message');

        // Check if subject is empty
        if (empty($subject)) {
            $errors = array(
                'emptySubject' => true,
                'subjectError' => true
            );
            return View::make($this->_contactView, $errors);
        }

        // Check subject min length
        if (strlen($subject) < Config::get($this->_contactConfig.'subjectMinLength')) {
            $errors = array(
                'subjectTooShort' => true,
                'subjectMinLength' => Config::get($this->_contactConfig.'subjectMinLength'),
                'subjectError' => true
            );
            return View::make($this->_contactView, $errors);
        }

        // Check subject max length
        if (strlen($subject) > Config::get($this->_contactConfig.'subjectMaxLength')) {
            $errors = array(
                'subjectTooLong' => true,
                'subjectMaxLength' => Config::get($this->_contactConfig.'subjectMaxLength'),
                'subjectError' => true
            );
            return View::make($this->_contactView, $errors);
        }

        // Check if name is empty
        if (empty($name)) {
            $errors = array(
                'emptyName' => true,
                'nameError' => true
            );
            return View::make($this->_contactView, $errors);
        }

        // Check if name is valid
        if (!ctype_alpha($name)) {
            $errors = array(
                'invalidName' => true,
                'nameError' => true
            );
            return View::make($this->_contactView, $errors);
        }

        // Check name min length
        if (strlen($name) < Config::get($this->_contactConfig.'nameMinLength')) {
            $errors = array(
                'nameTooShort' => true,
                'nameMinLength' => Config::get($this->_contactConfig.'nameMinLength'),
                'nameError' => true
            );
            return View::make($this->_contactView, $errors);
        }

        // Check name max length
        if (strlen($name) > Config::get($this->_contactConfig.'nameMaxLength')) {
            $errors = array(
                'nameTooLong' => true,
                'nameMaxLength' => Config::get($this->_contactConfig.'nameMaxLength'),
                'nameError' => true
            );
            return View::make($this->_contactView, $errors);
        }

        // If user is not logged in email field is required
        if (!$this->_loggedIn && empty($email)) {
            $errors = array(
                'emptyEmail' => true,
                'emailError' => true
            );
            return View::make($this->_contactView, $errors);
        }

        // Check if email is valid
        if (!$this->_loggedIn && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors = array(
                'invalidEmail' => true,
                'emailError' => true
            );
            return View::make($this->_contactView, $errors);
        }

        // Check if message is empty
        if (empty($message)) {
            $errors = array(
                'emptyMessage' => true,
                'messageError' => true
            );
            return View::make($this->_contactView, $errors);
        }

        // Check message min length
        if (strlen($message) < Config::get($this->_contactConfig.'messageMinLength')) {
            $errors = array(
                'messageTooShort' => true,
                'messageMinLength' => Config::get($this->_contactConfig.'messageMinLength'),
                'messageError' => true
            );
            return View::make($this->_contactView, $errors);
        }

        // Check message max length
        if (strlen($message) > Config::get($this->_contactConfig.'messageMaxLength')) {
            $errors = array(
                'messageTooLong' => true,
                'messageMaxLength' => Config::get($this->_contactConfig.'messageMaxLength'),
                'messageError' => true
            );
            return View::make($this->_contactView, $errors);
        }



        // todo add a captcha or other spam preventing system
        // todo log contact message in database
        // todo Send email
    }


    /**
     * Log in database information about contact message
     */
    private function _logContactMessage() {
        //
    }


    /**
     * Log in database information about a failed contact message
     */
    private function _logFailedContactMessage() {
        //
    }
}