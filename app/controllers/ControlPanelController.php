<?php

/**
 * Class ControlPanelController
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class ControlPanelController extends BaseController {

    /**
     * @var int Page access level
     */
    private $_pageAccessLevel = 2;

    /**
     * @var string Admin panel view name
     */
    private $_adminPanelView = 'admin-panel';

    /**
     * @var string Users view name
     */
    private $_usersView = 'users';

    /**
     * @var array Data to parse to views
     */
    private $_viewData = array();

    /**
     * @var string Admin panel statistics view name
     */
    private $_adminPanelStatisticsView = 'statistics';


    public function __construct() {
        parent::__construct();
        $this->beforeFilter(function() {
            // Check if user is logged in and has admin rights
            if (!$this->_loggedIn) {
                return Redirect::to('/');
            }

            if (!$this->_usersModel->compareUserLevel($this->_userId, $this->_pageAccessLevel)) {
                // User does not have rights to access this page
                // todo Log user action as a dangerous one
                return Redirect::to('/');
            }

            // Get admin bar data
            $this->_getAdminBarData();
        });
    }


    /**
     * Render admin panel index page
     *
     * @return mixed
     */
    public function index() {
        // Render view
        $pageInfo = array();
        $pageInfo['totalUsers'] = $this->_usersModel->count();
        return View::make($this->_adminPanelView, $pageInfo);
    }


    /**
     * Render admin panel statistics page
     *
     * @return mixed
     */
    public function statistics() {
        // Get and format statistics form database
//        $statistics = array();
//
//        // Number of total users, total logs and logs per user average
//        $statistics['totalUsers'] = $this->_usersModel->count();
//        $logsModel = new LogsModel();
//        $statistics['totalLogs'] = $logsModel->count();
//        $statistics['logsPerUserAverage'] = $statistics['totalLogs'] / $statistics['totalUsers'];

        // Render view
        return View::make($this->_adminPanelStatisticsView, $this->_viewData);
    }

    public function settings() {
        // Render application settings page
    }

    public function subscriptions() {
        // Render application subscription page
    }

    public function users() {
        // Render application users page
//        $pageInfo = array();
//        $pageInfo['numberOfUsers'] = $this->_usersModel->count();
//        $this->_usersModel->searchUserByEmail('alexandru.bugarin@gmail.com');
        return View::make($this->_usersView, $this->_viewData);
    }

    public function searchUsers() {
        //
    }

    public function user() {
        //
    }

    private function _getAdminBarData() {
        $this->_viewData['totalUsers'] = $this->_usersModel->count();
    }
}