<?php

/**
 * Class LogsModel
 *
 * Handle database operations on Logs table
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class LogsModel extends BaseModel {

    /**
     * @var string Table name
     */
    protected $_tableName = 'Logs';

    /**
     * @var array Table fields
     */
    protected $_tableFields = array(
        'LogId',
        'UserId',
        'ApplicationId',
        'Level',
        'Message',
        'Line',
        'File',
        'Timestamp'
    );

    /**
     * @var string Applications table name
     */
    private $_applicationsTable = 'Applications';

    /**
     * @var int The number of logs displayed in a feed post
     */
    private $_numberOfLogsInPost = 7;


    /**
     * Get details about the log that match the given $logId
     *
     * @param int $logId
     * @return object
     */
    public function get($logId) {
        $query = DB::table($this->_tableName);
        $query->where('LogId', $logId);
        return $query->first();
    }

    public function getUserFeed($userId) {
        // Validate parameter
        if (!is_numeric($userId)) {
            throw new Exception("Invalid user id");
        }

        // Get all applications of the current user that contains logs
        $applicationsModel = new ApplicationsModel();
        $userApplications = $applicationsModel->getUserApplicationWithLogs($userId, 1);

        $results = array();

        // Get logs of each application
        foreach ($userApplications as $userApplication) {
            $results[] = array(
                'ApplicationId' => $userApplication->ApplicationId,
                'Name' => $userApplication->Name,
                'Logs' => $this->getApplicationLogs($userApplication->ApplicationId),
                'NumberOfLogs' => $this->countAppLogs($userApplication->ApplicationId),
                'LogsInPost' => $this->_numberOfLogsInPost
            );
        }
        return $results;
    }

    public function getApplicationLogs($applicationId, $limit = 0) {
        // Validate application id
        if (!is_numeric($applicationId)) {
            throw new Exception("Invalid application id");
        }

        if (!$limit || $limit < 1) {
            $limit = $this->_numberOfLogsInPost;
        }

        // Build sql
        $sql = "SELECT Level, Message, Line, File ";
        $sql .= "FROM {$this->_tableName} ";
        $sql .= "WHERE ApplicationId = ? ";
        $sql .= "LIMIT {$limit}";

        return DB::select($sql, array($applicationId));
    }

    /**
     * Get last $limit logs
     *
     * @param int $limit
     * @param int $userId
     * @return mixed
     */
    public function getLastLogs($limit, $userId) {

        $query = DB::table($this->_tableName);
        $query->where('UserId', $userId);
        $query->orderBy('LogId', 'desc');
        $query->take($limit);
        return $query->get();
    }


    /**
     * Get records used for pagination
     *
     * @param int $page
     * @param int $limit
     * @param int $applicationId
     * @return array With records
     */
    public function getByPage($page = 1, $limit = 10, $applicationId) {

        $results = array();

        $query = DB::table($this->_tableName);
        $query->where('ApplicationId', $applicationId);

        // Get total logs
        $results['totalLogs'] = $query->count();

        $query->skip($limit * ($page - 1));
        $query->orderBy('LogId', 'desc');
        $query->take($limit);

        $results['logs'] = $query->get();

        return $results;
    }


    /**
     * Count all logs for given application id and (optional) by the given $level
     *
     * @param int $applicationId
     * @param bool|string $level
     * @return mixed
     */
    public function countAppLogs($applicationId, $level = false) {

        $query = DB::table($this->_tableName);
        $query->where('ApplicationId', $applicationId);

        // Count all logs for given application id
        if (!$level) {
            return $query->count();
        }

        // Count only logs for the given log level
        $query->where('Level', $level);
        return $query->count();
    }


    /**
     * Insert new record
     *
     * @param array $record key => value
     * @return int Insert id
     */
//    public function insert($record) {
//
//        // Execute query and return insert id
//        return DB::table($this->_tableName)->insertGetId($record);
//    }
}