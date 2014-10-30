<?php

/**
 * Class BaseController
 *
 * Common controller functions
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class BaseController extends Controller {
    protected $_viewName = '';
    protected $_bodyId = '';
    protected $_fixedHeader = false;
    /**
     * @var int User id
     */
    protected $_userId = null;

    /**
     * @var string User email
     */
    protected $_userEmail = '';

    /**
     * @var string User level
     */
    protected $_userLevel = '';

    /**
     * @var int Admin level
     */
    protected $_adminLevel = 2;

    /**
     * @var bool User status (logged or not logged in)
     */
    protected $_loggedIn = false;

    /**
     * @var null UsersModel reference
     */
    protected $_usersModel = null;

    /**
     * @var string View title
     */
    protected $_viewTitle = '';

    /**
     * @var string Default view title
     */
    private $_defaultViewTitle = 'LogApp';

    /**
     * @var string What to append at the end of view title
     */
    private $_appendToViewTitle = ' - LogApp';

    /**
     * @var array With allowed controllers when user is logged in but not entered the two factor auth verification code
     */
    private $_allowedControllersWithoutVerificationCode = array(
        'VerificationCodeController',
        'LoginController'
    );


    /**
     * Make available user information to all controllers
     */
    public function __construct() {

        // Set user status
        $this->_setUserInfo();

        // Make data available to all views
        $this->_makeAvailableInAllViews();

        // Call before filter
        $this->beforeFilter(function() {
            return $this->_isVerificationCodeRequired();
        });
    }


	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}


    /**
     * @param array $viewData To parse at the view
     * @return mixed
     * @throws Exception
     */
    protected function renderView($viewData = array()) {

        // If a view file is not given
        if (empty($this->_viewName)) {
            throw new Exception("No view name given");
        }

        // Append view title and body id
        if (!empty($this->_viewTitle)) {
            $viewData['pageTitle'] = $this->_viewTitle.$this->_appendToViewTitle;
        } elseif (!empty($this->_defaultViewTitle)) {
            $viewData['pageTitle'] = $this->_defaultViewTitle;
        }
        if (!empty($this->_bodyId)) {
            $viewData['bodyId'] = $this->_bodyId;
        }
        if ($this->_fixedHeader) {
            $viewData['fixedHeader'] = true;
        }

        // Render view
        return View::make($this->_viewName, $viewData);
    }


    /**
     * Log user action
     *
     * @param string $action
     * @param int $visibilityLevel Can be 1 or 2. Default is 1
     */
    protected function _logUserAction($action, $visibilityLevel = 1) {
        $userActionLogsModel = new UserActionLogsModel();
        $userActionLogsModel->logAction($action, $this->_userId, $visibilityLevel);
    }


    /**
     * Set user details
     */
    private function _setUserInfo() {

        // Set user status (logged or not logged in)
        $this->_loggedIn = Session::get('loggedIn');

        $this->_usersModel = new UsersModel();

        if (!isset($this->_loggedIn)) {
            return;
        }

        // User is logged in, set their details
        $user = Session::get('user');
        $this->_userId = $user['UserId'];
        $result = $this->_usersModel->getOne(array('Email', 'Level'), array('UserId' => $this->_userId));
        $this->_userEmail = $result->Email;
        $this->_userLevel = $result->Level;
    }


    /**
     * Make given variables available in all views
     */
    private function _makeAvailableInAllViews() {
        View::share(array(
            'loggedIn' => $this->_loggedIn,
            'email' => $this->_userEmail,
            'level' => $this->_userLevel,
            'adminLevel' => $this->_adminLevel
        ));
    }


    /**
     * If current controller is not an allowed controller to be accessed without
     * the two factor auth verification code call a before filter defined in routes.php
     */
    private function _isVerificationCodeRequired() {

        // Get current controller name
        $controller = explode("@", Route::currentRouteAction());

        // If current controller can be accessed when user is not fully logged in, exit
        if (!isset($controller[0]) || in_array($controller[0], $this->_allowedControllersWithoutVerificationCode)) {
            return;
        }

        // Else check if a verification code is required and redirect user to verification code page
        $twoFactorAuthVerificationCodesModel = new TwoFactorAuthVerificationCodesModel();
        if ($twoFactorAuthVerificationCodesModel->checkIfVerificationCodeIsRequired($this->_userId)) {
            return Redirect::to('verification-code');
        }
    }


//    /**
//     * Append application name to view title
//     */
//    private function _processViewTitle() {
//        if (!isset($this->_viewTitle)) {
//            $this->_viewTitle = $this->_defaultViewTitle;
//            return;
//        }
//
//        $this->_viewTitle = $this->_viewTitle . $this->_appendToViewTitle;
//    }
}
