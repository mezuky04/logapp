<?php

/**
 * Class HomeController
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class HomeController extends BaseController {

    /**
     * @var string Home page layout name
     */
    private $_homeLayout = 'home';

    /**
     * @var int The number of last logs to display on homepage
     */
    private $_numberOfLastLogsToDisplay = 20;

    /**
     * @var array Max displayed length for last logs
     */
    private $_maxLengths = array(
        'message' => 70,
        'file' => 100
    );


    /**
     * Render homepage
     *
     * @return mixed
     */
    public function showHomepage() {

        // Get user las logs
        $logsModel = new LogsModel();
        $logs = $logsModel->getLastLogs($this->_numberOfLastLogsToDisplay, $this->_userId);

        $view = View::make($this->_homeLayout);

        if (!$logs) {
            return $view->with('noLogs', true);
        }

        // Log user action
        try {
            $userActionLogsModel = new UserActionLogsModel();
            $userActionLogsModel->logAction("Accessed homepage", $this->_userId, 2);
        } catch (Exception $e) {
            // todo an exception handler
            exit($e->getMessage());
        }


        $view->with('lastLogs', $logs);
        $view->with('numberOfLogs', count($logs));
        return $view->with('maxLengths', $this->_maxLengths);
    }

}
