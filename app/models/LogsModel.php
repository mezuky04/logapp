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
        'File'
    );

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