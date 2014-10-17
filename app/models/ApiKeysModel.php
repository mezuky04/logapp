<?php

/**
 * Class ApiKeysModel
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class ApiKeysModel {

    /**
     * @var string Table name
     */
    protected $_tableName = 'ApiKeys';


    /**
     * Generate api key for the given user id and application id
     *
     * @param int $userId
     * @param int $applicationId
     * @return int Insert id
     */
    public function generateAPIKey($userId, $applicationId) {

        // Generate api key
        $apiKey = md5(Hash::make(uniqid().$userId.uniqid().$applicationId));

        // Save in database
        $apiKeyId = DB::table($this->_tableName)->insertGetId(array(
            'ApiKey' => $apiKey,
            'UserId' => $userId,
            'ApplicationId' => $applicationId
        ));

        return $apiKeyId;
    }


    /**
     * Get API key that match the given user id and application id
     *
     * @param int $userId
     * @param int $applicationId
     * @return string
     */
    public function getAPIKey($userId, $applicationId) {

        $query = DB::table($this->_tableName);
        $query->where('UserId', $userId);
        $query->where('ApplicationId', $applicationId);
        return $query->pluck('ApiKey');
    }


    /**
     * Get user id that match the given api key
     *
     * @param string $apiKey
     * @return int User id
     */
    public static function getUserId($apiKey) {

        $query = DB::select("SELECT UserId FROM ApiKeys WHERE ApiKey = ?", array($apiKey));
        if ($query) {
            return $query[0]->UserId;
        }
    }


    /**
     * Delete an api key from database
     *
     * @param string $apiKey to be deleted
     */
    public function deleteApiKey($apiKey) {
        DB::table($this->_tableName)->where('ApiKey', $apiKey)->delete();
    }
}