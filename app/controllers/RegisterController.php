<?php

/**
 * Class RegisterController
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class RegisterController extends BaseController {

    protected $_viewTitle = 'Create new account';

    /**
     * @var string User selected plan
     */
    private $_selectedPlan = '';

    /**
     * @var string Register view name
     */
    private $_registerView = 'register';

    /**
     * @var string Plans view name
     */
    private $_plansView = 'plans';

    /**
     * @var null Subscription plans
     */
    private $_subscriptionPlans = null;

    /**
     * @var string User email from register form
     */
    private $_formEmail = '';

    /**
     * @var string User password form register form
     */
    private $_userPassword = '';

    /**
     * @var string User phone number prefix from register form
     */
    private $_userPhoneNumberPrefix = '';

    /**
     * @var string User phone number form register form
     */
    private $_userPhoneNumber = '';

    /**
     * @var string Account config file name
     */
    private $_accountConfig = 'account.';


    /**
     * Render register form or subscription plans
     *
     * @return mixed
     */
    public function index() {

        // Redirect logged in users
        if ($this->_loggedIn) {
            return Redirect::to('/');
        }

        // Check if register form or subscription plan view should be displayed
        $this->_getUserPlan();
        if (!isset($this->_selectedPlan) || !$this->_isValidSelectedPlan()) {
            return Redirect::to('plans');
        }

        $this->renderView($this->_registerView, array(
            'pageTitle' => $this->_viewTitle,
            'countries' => $this->_getCountries()
        ));
        //$this->_renderRegisterView(array('pageTitle' => $this->_viewTitle));
    }


    public function processRegistration() {

        $this->_getUserEmail();
        $this->_getUserPassword();
        $this->_getUserPhoneNumberPrefix();
        $this->_getUserPhoneNumber();
        $this->_getUserPlan();
        $this->_createNewAccount();
    }


    /**
     * Get and validate user email
     */
    private function _getUserEmail() {

        $this->_formEmail = Input::get('email');

        // Validate given email
        if (empty($this->_formEmail)) {
            $this->_renderRegisterView(array('emailError' => true, 'emptyEmail' => true));
        }

        if (!filter_var($this->_formEmail, FILTER_VALIDATE_EMAIL)) {
            $this->_renderRegisterView(array('emailError' => true, 'invalidEmail' => true));
        }

        if ($this->_usersModel->check('Email', $this->_formEmail)) {
            $this->_renderRegisterView(array('emailError' => true, 'alreadyUsedEmail' => true));
        }
    }


    /**
     * Get and validate user password
     */
    private function _getUserPassword() {

        $this->_userPassword = Input::get('password');

        // Validate given password
        if (empty($this->_userPassword)) {
            $this->_renderRegisterView(array('passwordError' => true, 'emptyPassword' => true));
        }

        if (strlen($this->_userPassword) < Config::get($this->_accountConfig.'minPasswordLength')) {
            $this->_renderRegisterView(array(
                'passwordError' => true,
                'tooShortPassword' => true,
                'passwordLength' => Config::get($this->_accountConfig.'minPasswordLength')
            ));
        }

        if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])$/', $this->_userPassword)) {
            $this->_renderRegisterView(array('passwordError' => true, 'tooSimplePassword' => true));
        }
    }


    /**
     * Get and validate user phone number prefix
     */
    private function _getUserPhoneNumberPrefix() {

        $this->_userPhoneNumberPrefix = Input::get('phone-number-prefix');

        // Validate given phone number prefix
        if (empty($this->_userPhoneNumberPrefix)) {
            $this->_renderRegisterView(array('phoneNumberPrefixError' => true, 'emptyPhoneNumberPrefix' => true));
        }

        $prefixesModel = new PrefixesModel();
        if (!ctype_digit($this->_userPhoneNumberPrefix) || !$prefixesModel->checkIfPrefixExists($this->_userPhoneNumberPrefix)) {
            $this->_renderRegisterView(array('phoneNumberPrefixError' => true, 'invalidPhoneNumberPrefix' => true));
        }

        if (strlen($this->_userPhoneNumberPrefix) > Config::get($this->_accountConfig.'maxPhoneNumberPrefixLength')) {
            $this->_renderRegisterView(array('phoneNumberPrefixError' => true, 'tooLongPhoneNumberPrefix' => true));
        }

        if (strlen($this->_userPhoneNumberPrefix) < Config::get($this->_accountConfig.'minPhoneNumberPrefixLength')) {
            $this->_renderRegisterView(array('phoneNumberPrefixError' => true, 'tooShortPhoneNumberPrefix'));
        }
    }


    /**
     * Get and validate user phone number
     */
    private function _getUserPhoneNumber() {

        $this->_userPhoneNumber = input::get('phone-number');

        // Validate given phone number
        if (empty($this->_userPhoneNumber)) {
            $this->_renderRegisterView(array('phoneNumberError' => true, 'emptyPhoneNumber' => true));
        }

        if (!ctype_digit($this->_userPhoneNumber)) {
            $this->_renderRegisterView(array('phoneNumberError' => true, 'invalidPhoneNumber' => true));
        }

        if (strlen($this->_userPhoneNumber) > Config::get($this->_accountConfig.'maxPhoneNumberLength')) {
            $this->_renderRegisterView(array('phoneNumberError' => true, 'tooLongPhoneNumber' => true));
        }

        if (strlen($this->_userPhoneNumber) < Config::get($this->_accountConfig.'minPhoneNumberLength')) {
            $this->_renderRegisterView(array('phoneNumberError' => true, 'tooShortPhoneNumber' => true));
        }
        // todo Check if phone number is already used
    }


    /**
     * Get user selected subscription plan
     */
    private function _getUserPlan() {
        $this->_selectedPlan = Input::get('subscription-plan');
    }


    private function _getCountries() {
        $countriesModel = new CountriesModel();
        return $countriesModel->getAll(array('CountryId', 'Name'));
    }


    /**
     * Create new user
     */
    private function _createNewAccount() {
//        try {
            $this->_usersModel->createNewUser(array(
                'email' => $this->_formEmail,
                'password' => $this->_userPassword,
                'phoneNumber' => $this->_userPhoneNumber,
                'phoneNumberPrefix' => $this->_userPhoneNumberPrefix,
                'subscriptionPlan' => $this->_selectedPlan
            ));
//        } catch (MissingUserDetailException $missingUserDetailException) {
//            //
//        } catch (CouldNotInsertNewUserException $couldNotInsertNewUserException) {
//            //
//        }
    }


    private function _isValidSelectedPlan() {

        $this->_getSubscriptionPlans();
        if (!count($this->_subscriptionPlans)) {
            // No subscription plan is available
            return true;
        }

        foreach ($this->_subscriptionPlans as $plan) {
            if ($plan->Key === $this->_selectedPlan) {
                return true;
            }
        }
        return false;
    }


    /**
     * Get subscription plans from database
     */
    private function _getSubscriptionPlans() {
        $subscriptionDetailsModel = new SubscriptionDetailsModel();
        $this->_subscriptionPlans = $subscriptionDetailsModel->getSubscriptionPlans();
    }

    /**
     * Render and make available in register view the given key => value variables
     *
     * @param array $variables key => value pairs to make available in view
     */
    private function _renderRegisterView($variables = array()) {
        if (!count($variables)) {
            exit(View::make($this->_registerView));
        }
        exit(View::make($this->_registerView, $variables));
    }
}