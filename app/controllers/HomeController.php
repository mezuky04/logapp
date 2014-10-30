<?php

/**
 * Class HomeController
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class HomeController extends BaseController {

    /**
     * @var string View file name
     */
    protected $_viewName = 'home';

    /**
     * @var string View title
     */
    protected $_viewTitle = 'Home';

    /**
     * @var string View body id
     */
    protected $_bodyId = 'homepage';

    protected $_fixedHeader = false;

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
    public function index() {
        if (!$this->_loggedIn) {
            return $this->renderView();
        }

        $this->_fixedHeader = true;
        Event::fire('page.accessed', $this->_userId);
        return $this->renderView($this->_getViewData());
    }


    /**
     * Get required data for home view
     *
     * @return array
     */
    private function _getViewData() {
        $viewData = array();

        $logsModel = new LogsModel();
        $viewData['userFeed'] = $logsModel->getUserFeed($this->_userId);
//        // Get user last logs
//        $logsModel = new LogsModel();
//        $logs = $logsModel->getLastLogs($this->_numberOfLastLogsToDisplay, $this->_userId);
//        if (!$logs) {
//            return array('noLogs' => true);
//        }
//
//        $viewData['lastLogs'] = $logs;
//        $viewData['numberOfLogs'] = count($logs);
//        $viewData['maxLengths'] = $this->_maxLengths;
        return $viewData;
    }
}
