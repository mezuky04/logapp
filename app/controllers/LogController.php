<?php

/**
 * Class LogController
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class LogController extends BaseController {

    /**
     * @var string Log layout name
     */
    private $_logLayout = 'log';


    /**
     * Render log page
     *
     * @param int $logId
     * @return mixed
     */
    public function showLogPage($logId) {

        // Be sure that log id is numeric
        if (!is_numeric($logId)) {
            // Log here that someone accessed a log with a non numeric id
            App::abort(404);
        }

        // Redirect not logged in users
        if (!$this->_loggedIn) {
            return Redirect('login?next=log/'.$logId);
        }

        // Check if log belongs to the current user
        $logsModel = new LogsModel();
        $log = $logsModel->get($logId);

        if (!$log) {
            App::abort(404);
        }

        // Get log details and render view
        return View::make($this->_logLayout, $log);
    }
}