<?php

/**
 * Class RegisterController
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class RegisterController extends BaseController {

    /**
     * @var string Page title
     */
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
        $this->_selectedPlan = Input::get('subscription-plan');
        if (!isset($this->_selectedPlan) || !$this->_isValidSelectedPlan()) {
            return Redirect::to('plans');
        }

        // Render register view with all needed information
        return $this->_renderRegisterView();
    }


    /**
     * Process user registration
     */
    public function processRegistration() {

        // Get and validate subscription plan
        $this->_selectedPlan = Input::get('subscription-plan');
        $subscriptionDetailsModel = new SubscriptionDetailsModel();
        $subscriptionPlanErrors = $subscriptionDetailsModel->validateSubscriptionPlan($this->_selectedPlan);
        if (count($subscriptionPlanErrors)) {
            return $this->_renderRegisterView($subscriptionPlanErrors);
        }

        // Get and validate email
        $this->_formEmail = Input::get('email');
        $emailErrors = $this->_usersModel->validateEmail($this->_formEmail);
        if (count($emailErrors)) {
            return $this->_renderRegisterView($emailErrors);
        }

        // Get and validate password
        $this->_userPassword = Input::get('password');
        $passwordErrors = $this->_usersModel->validatePassword($this->_userPassword);
        if (count($passwordErrors)) {
            return $this->_renderRegisterView($passwordErrors);
        }

        // Get and validate phone number prefix
        $this->_userPhoneNumberPrefix = Input::get('phone-number-prefix');
        $prefixesModel = new PrefixesModel();
        $phoneNumberPrefixErrors = $prefixesModel->validatePhoneNumberPrefix($this->_userPhoneNumberPrefix);
        if (count($phoneNumberPrefixErrors)) {
            return $this->_renderRegisterView($phoneNumberPrefixErrors);
        }

        // Get and validate phone number
        $this->_userPhoneNumber = Input::get('phone-number');
        $phoneNumbersModel = new PhoneNumbersModel();
        $phoneNumberErrors = $phoneNumbersModel->validatePhoneNumber($this->_userPhoneNumber);
        if (count($phoneNumberErrors)) {
            return $this->_renderRegisterView($phoneNumberErrors);
        }

        try {
            // Insert new user in database
            $this->_usersModel->createNewUser(array(
                'email' => $this->_formEmail,
                'password' => $this->_userPassword,
                'phoneNumber' => $this->_userPhoneNumber,
                'phoneNumberPrefix' => $this->_userPhoneNumberPrefix,
                'subscriptionPlan' => $this->_selectedPlan
            ));

            return Redirect::to('welcome');
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }


    /**
     * @return array
     */
    private function _getCountries() {
        if (!Cache::has('countries')) {
            $countriesModel = new CountriesModel();
            $countries = $countriesModel->get();
            Cache::forever('countries', $countries);
            return $countries;
        }
        return Cache::get('countries');
    }


    /**
     * @return bool
     */
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

        $variables['pageTitle'] = $this->_viewTitle;
        $variables['countries'] = $this->_getCountries();

        // Check if a subscription plan is selected or exists in the url
        $subscriptionDetailsModel = new SubscriptionDetailsModel();
        if (!empty($this->_selectedPlan)) {
            $variables['selectedPlan'] = $subscriptionDetailsModel->getNameByKey($this->_selectedPlan);
        } else {
            $selectedPlan = Input::get('selected-plan');
            $subscriptionPlanErrors = $subscriptionDetailsModel->validateSubscriptionPlan($selectedPlan);
            if (!count($subscriptionPlanErrors)) {
                $variables['selectedPlan'] = $subscriptionDetailsModel->getNameByKey($selectedPlan);
            }
        }

        // Get default country and prefix
        $prefixesModel = new PrefixesModel();
        $variables['defaultPrefix'] = $prefixesModel->getDefaultPrefixAndCountry();

        if (isset($this->_userPhoneNumberPrefix) && $this->_userPhoneNumberPrefix) {
            $countriesModel = new CountriesModel();
            $variables['prefixCountry'] = $countriesModel->getByPrefix($this->_userPhoneNumberPrefix);
        }

        return View::make($this->_registerView, $variables);
    }
}