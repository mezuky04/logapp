<?php

/**
 * Class ApplicationsModel
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class ApplicationsModel {

    /**
     * @var string Table name
     */
    private $_tableName = 'Applications';


    /**
     * Get all applications for the given user id
     *
     * @param int $userId
     * @return mixed
     */
    public function getUserApplications($userId) {

        $query = "SELECT Applications.*, count(Logs.LogId) as TotalLogs FROM Applications LEFT JOIN Logs ON Logs.ApplicationId = Applications.ApplicationId WHERE Applications.UserId = {$userId} GROUP BY Applications.ApplicationId ORDER BY Applications.ApplicationId DESC";
        //$query = "SELECT Applications.*, count(Logs.LogId) as TotalLogs FROM Applications INNER JOIN Logs ON Logs.ApplicationId = Applications.ApplicationId AND Applications.UserId = {$userId} LIMIT 2";
        $y = DB::select($query);return $y;
        //$query = DB::table($this->_tableName);
        //$query->where('UserId', $userId);

        //return $query->get();
    }


    /**
     * Update db record that match the given $filter
     *
     * @param array $record Key => value array with record to update
     * @param array $filter Key => value filter to apply at update
     */
    public function update($record = array(), $filter = array()) {

        $query = DB::table($this->_tableName);
        foreach ($filter as $key => $value) {
            $query->where($key, $value);
        }
        $query->update($record);
    }


    /**
     * Check if given application name is already used by given user id
     *
     * @param string $appName
     * @param int $userId
     * @return bool
     */
    public function appNameUsed($appName, $userId) {

        $query = DB::table($this->_tableName);
        $query->where('Name', $appName);
        $query->where('UserId', $userId);

        if ($query->count()) {
            // Name used
            return true;
        }

        // Name not used by current user
        return false;
    }


    /**
     * Insert new application in database
     *
     * @param string $appName
     * @param int $userId
     * @return int Insert id
     */
    public function addApp($appName, $userId) {

        // Save app details in database
        return DB::table($this->_tableName)->insertGetId(array(
            'Name' => $appName,
            'UserId' => $userId
        ));
    }


    /**
     * Get details abut an app that match the given application id and user id
     *
     * @param int $userId
     * @param int $applicationId
     * @return bool
     */
    public function getUserApp($userId, $applicationId) {

        $query = DB::table($this->_tableName);
        $query->where('UserId', $userId);
        $query->where('ApplicationId', $applicationId);

        $result = $query->first();

        if (!$result) {
            // No record found
            return false;
        }

        return $result;
    }


    /**
     * Return application id that match the given $apiKey
     *
     * @param string $apiKey
     * @return int Application Id
     */
    public function getAppId($apiKey) {
        $query = DB::table($this->_tableName);
        $query->where('ApiKey', $apiKey);
        return $query->pluck('ApplicationId');
    }

    /**
     * Check if given user has at least one application created
     *
     * @param int $userId
     * @return bool
     */
    public function checkIfUserHasApp($userId) {

        $query = DB::table($this->_tableName);
        $query->where('UserId', $userId);

        if (!$query->count()) {
            // User don't have any app created
            return false;
        }

        return true;
    }
}