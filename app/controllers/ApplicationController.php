<?php

/**
 * Class ApplicationController
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class ApplicationController extends BaseController {

    /**
     * @var string New application name
     */
    private $_newAppName = '';

    /**
     * @var int Application id
     */
    private $_applicationId = null;

    /**
     * @var bool
     */
    private $_smsNotifications = false;

    /**
     * @var bool
     */
    private $_emailNotifications = false;

    /**
     * @var string Application layout name
     */
    private $_applicationLayout = 'application';

    /**
     * @var string Name user to get pagination config values
     */
    private $_paginationConfig = 'pagination.';

    /**
     * @var array Max displayed length for last logs
     */
    private $_maxLengths = array(
        'message' => 70,
        'file' => 60
    );

    /**
     * @var string Value used in html form for sms notifications checkbox
     */
    private $_enableSMSNotificationsValue = "sms-enabled";

    /**
     * @var string Value used in html form for email notifications checkbox
     */
    private $_enableEmailNotificationsValue = "email-enabled";


    /**
     * Display application page that match the given $applicationId
     *
     * @param $applicationId
     * @return mixed
     */
    public function showApplicationPage($applicationId) {

        // Redirect not logged in users
        if (!$this->_loggedIn) {
            return Redirect::to('login?next=application/'.$applicationId);
        }

        if (!is_numeric($applicationId)) {
            App::abort(404);
        }

        $applicationsModel = new ApplicationsModel();
        $application = $applicationsModel->getUserApp($this->_userId, $applicationId);

        if (!$application) {
            App::abort(404);
        }

        // Get total logs
        $logsModel = new LogsModel();
        $application->TotalLogs = $logsModel->countAppLogs($applicationId);
        $application->TotalInfoLogs = $logsModel->countAppLogs($applicationId, 'info');
        $application->TotalDebugLogs = $logsModel->countAppLogs($applicationId, 'debug');
        $application->TotalWarningLogs = $logsModel->countAppLogs($applicationId, 'warning');
        $application->TotalErrorLogs = $logsModel->countAppLogs($applicationId, 'error');
        $application->TotalEmergencyLogs = $logsModel->countAppLogs($applicationId, 'emergency');

        // Get API key
        $apiKeysModel = new ApiKeysModel();
        $application->APIKey = $apiKeysModel->getAPIKey($this->_userId, $applicationId);

        // Get logs page
        $page = Input::get('page');
        if (!isset($page) || !is_numeric($page)) {
            // Set a default value if given is invalid
            $page = 1;
        }

        // Get logs
        $data = $logsModel->getByPage($page, Config::get($this->_paginationConfig.'perPage'), $applicationId);
        $logs = Paginator::make($data['logs'], $data['totalLogs'], Config::get($this->_paginationConfig.'perPage'));

        $viewDetails = array(
            'application' => $application,
            'logs' => $logs,
            'noLogs' => $logsModel->countAppLogs($applicationId),
            'maxLengths' => $this->_maxLengths,
        );

        return View::make($this->_applicationLayout, $viewDetails);
    }


    /**
     * Edit application
     *
     * @param int $applicationId
     * @return mixed
     */
    public function editApplication($applicationId) {

        // Check if user is logged in
        $this->_checkIfUserIsLoggedIn();

        // Get fields
        $this->_getFields();

        // Validate application id
        $this->_validateApplicationId();

        // Check if application exists
        //$this->_checkIfAppExists();

        // Validate name
        $this->_validateName($this->_applicationId);

        // Validate sms and email notifications

        // Update name
        $this->_updateName($this->_applicationId);

        // Update notifications

        // Success response
        exit(json_encode(array(
            'status' => 'success',
            'message' => 'Application updated!'
        )));
    }


    /**
     * Check if user is logged in before edit an application
     */
    private function _checkIfUserIsLoggedIn() {
        if (!$this->_loggedIn) {
            exit(json_encode(array(
                'status' => 'fail',
                'message' => 'You must be logged in to edit this application',
                'redirect' => true
            )));
        }
    }

    /**
     * Check if application id is numeric
     */
    private function _validateApplicationId() {
        // Invalid application id
        if (!is_numeric($this->_applicationId)) {
            exit(json_encode(array(
                'status' => 'fail',
                'message' => 'Invalid application id'
            )));
        }
    }


    /**
     * Get input fields
     */
    private function _getFields() {
        $this->_applicationId = Input::get('application-id');
        $this->_newAppName = Input::get('app-name');
        $this->_smsNotifications = Input::get('sms-notifications');
        $this->_emailNotifications = Input::get('email-notifications');
    }


    /**
     * Validate application name
     *
     * @param int $applicationId
     */
    private function _validateName($applicationId) {

        $applicationsModel = new ApplicationsModel();
        $application = $applicationsModel->getUserApp($this->_userId, $applicationId);

        if (isset($application->Name) && $this->_newAppName === $application->Name) {
            // Same name
            return;
        }

        $appValidator = ApplicationHelper::validate2($this->_newAppName, $this->_userId);
        if (isset($appValidator['error'])) {
            // We have an error
            exit(json_encode(array(
                'status' => 'fail',
                'message' => $appValidator['error'],
                'appNameError' => true
            )));
        }
    }


    /**
     * Update application name
     *
     * @param int $applicationId
     */
    private function _updateName($applicationId) {
        // Update application name in database
        $applicationsModel = new ApplicationsModel();
        $applicationsModel->update(array('Name' => $this->_newAppName), array('ApplicationId' => $applicationId));
    }


    /**
     * @param $applicationId
     * @param $userId
     * @return array
     */
    private function _getApplicationDetails($applicationId, $userId) {

        $applicationsModel = new ApplicationsModel();
        $application = $applicationsModel->getUserApp($userId, $applicationId);

        if (!$application) {
            // App does not exist or not belongs to current user
            App::abort(404);
        }

        // Get total logs
        $logsModel = new LogsModel();
        $application->TotalLogs = $logsModel->countAppLogs($applicationId);
        $application->TotalInfoLogs = $logsModel->countAppLogs($applicationId, 'info');
        $application->TotalDebugLogs = $logsModel->countAppLogs($applicationId, 'debug');
        $application->TotalWarningLogs = $logsModel->countAppLogs($applicationId, 'warning');
        $application->TotalErrorLogs = $logsModel->countAppLogs($applicationId, 'error');
        $application->TotalEmergencyLogs = $logsModel->countAppLogs($applicationId, 'emergency');

        // Get API key
        $apiKeysModel = new ApiKeysModel();
        $application->APIKey = $apiKeysModel->getAPIKey($this->_userId, $applicationId);

        // Get logs page
        $page = Input::get('page');
        if (!isset($page) || !is_numeric($page)) {
            // Set a default value if given is invalid
            $page = 1;
        }

        // Get logs
        $data = $logsModel->getByPage($page, Config::get($this->_paginationConfig.'perPage'), $applicationId);
        $logs = Paginator::make($data['logs'], $data['totalLogs'], Config::get($this->_paginationConfig.'perPage'));

        return array(
            'application' => $application,
            'logs' => $logs,
            'noLogs' => $logsModel->countAppLogs($applicationId),
            'maxLengths' => $this->_maxLengths,
        );
    }
}