<?php

/**
 * Class UserActionLogsModel
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class UserActionLogsModel extends BaseModel {

    /**
     * @var string Table name
     */
    protected $_tableName = "UserActionLogs";

    /**
     * @var array Table fields
     */
    protected $_tableFields = array('UserActionLogId', 'UserId', 'Description', 'IpAddress', 'Timestamp', 'VisibilityLevel');


    /**
     * Save given action in database
     *
     * @param string $action
     * @param int $userId
     * @param int $visibilityLevel
     * @throws Exception
     */
    public function logAction($action, $userId, $visibilityLevel = 1) {
        // Validate parameters
        if (strlen($action) < 1) {
            throw new Exception("Empty action parameter");
        }
        if (!is_numeric($userId)) {
            throw new Exception("Empty or invalid user id parameter");
        }
        if ($visibilityLevel != 1 && $visibilityLevel != 2) {
            throw new Exception("Invalid level parameter");
        }

        // Save in database
        $insertId = $this->insertGetId(array(
            'UserId' => $userId,
            'Description' => $action,
            'IpAddress' => Request::getClientIp(),
            'VisibilityLevel' => $visibilityLevel
        ));
        if (!$insertId) {
            throw new Exception("An error occurred and user action was not inserted");
        }
    }
}