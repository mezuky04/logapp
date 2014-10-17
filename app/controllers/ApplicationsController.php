<?php

/**
 * Class ApplicationsController
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class ApplicationsController extends BaseController {

    /**
     * @var string Applications layout name
     */
    private $_applicationsLayout = 'applications';

    /**
     * @var string Application layout
     */
    private $_applicationLayout = 'application';

    /**
     * @var string Application config file name
     */
    private $_configName = 'application.';


    /**
     * Render applications page
     *
     * @return mixed
     */
    public function showApplicationsPage() {

        // Redirect not logged in users
        if (!$this->_loggedIn) {
            return Redirect::to('login?next=applications');
        }

        // Log user action
        try {
            $userActionLogsModel = new UserActionLogsModel();
            $userActionLogsModel->logAction("Accessed applications page", $this->_userId);
        } catch (Exception $e) {
            // todo an exception handler
            exit($e->getMessage());
        }

        $details = array();

        $this->_getUserApplications($details);

        return View::make($this->_applicationsLayout, $details);
    }


    /**
     * Create new application
     *
     * @return mixed
     */
    public function addNewApp() {

        // Check if user is logged in
        if (!$this->_loggedIn) {
            exit(json_encode(array('status' => 'fail', 'message' => 'You should be logged in to create a new app')));
        }

        // Get app name
        $appName = Input::get('app-name');

        // Validate app name
        $this->_validateAppName($appName);

        // Insert new application in database
        $applicationId = $this->_insertNewApplication($appName);

        // Generate new api key
        $this->_generateAPIKey($applicationId);

        // Return success message
        exit(json_encode(array(
            'status' => 'success',
            'message' => 'Application successful created',
            'appName' => $appName,
            'appId' => $applicationId
        )));
    }


    /**
     * Render application details page
     *
     * @param int $applicationId
     * @return mixed
     */
    public function getAppDetails($applicationId) {

        // Redirect not logged in users
        if (!$this->_loggedIn) {
            return Redirect::to('login');
        }

        $applicationsModel = new ApplicationsModel();
        $applicationDetails = $applicationsModel->getUserApp($this->_userId, $applicationId);
        $applicationDetails = (array) $applicationDetails;

        // Show 404 page for users that are trying to access details about apps that are not their
        if (!$applicationDetails) {
            App::abort(404);
        }

        return View::make($this->_applicationLayout, array('application' => $applicationDetails));
    }


    /**
     * Add to the given array information about user applications
     *
     * @param array $viewArray
     */
    private function _getUserApplications(&$viewArray) {

        $applicationsModel = new ApplicationsModel();

        if (!$applicationsModel->checkIfUserHasApp($this->_userId)) {
            $viewArray['hasApplications'] = false;
        }

        $viewArray['hasApplications'] = true;
        $viewArray['applications'] = $applicationsModel->getUserApplications($this->_userId);
    }


    /**
     * Validate given $appName
     *
     * @param $appName
     */
    private function _validateAppName($appName) {
        // Check if app name is empty
        if (empty($appName)) {
            exit(json_encode(array('status' => 'fail', 'message' => 'Please enter a name for your new application')));
        }

        // Application name can contain only alpha-numeric characters
        if (!ctype_alnum($appName)) {
            exit(json_encode(array('status' => 'fail', 'message' => 'Application name can contain only alpha-numeric characters')));
        }

        // Check for app max length
        if (strlen($appName) > Config::get($this->_configName.'nameMaxLength')) {
            exit(json_encode(array('status' => 'fail', 'message' => 'Application name can have a max length of '.Config::get($this->_configName.'nameMaxLength'))));
        }

        // Check if name is already used
        $applicationsModel = new ApplicationsModel();
        if ($applicationsModel->appNameUsed($appName, $this->_userId)) {
            exit(json_encode(array('status' => 'fail', 'message' => 'You already have an application with this name')));
        }
    }


    /**
     * Insert new application in database
     *
     * @param string $appName
     * @return int Insert id
     */
    private function _insertNewApplication($appName) {
        $applicationsModel = new ApplicationsModel();
        return $applicationsModel->addApp($appName, $this->_userId);
    }


    /**
     * Generate and insert new api key in database
     *
     * @param int $applicationId
     */
    private function _generateAPIKey($applicationId) {
        $apiKeysModel = new ApiKeysModel();
        $apiKeysModel->generateAPIKey($this->_userId, $applicationId);
    }
}