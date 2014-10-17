<?php

/**
 * Class TwoFactorAuthLogsModel
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class TwoFactorAuthLogsModel extends BaseModel {

    /**
     * @var string Table name
     */
    protected $_tableName = "TwoFactorAuthLogs";

    /**
     * @var array Table fields
     */
    protected $_tableFields = array(
        'TwoFactorAuthLogId',
        'UserId',
        'ValidVerificationCode',
        'Timestamp'
    );

    /**
     * @var int
     */
    private $_maxFailedAttempts = 0;

    /**
     * @var int
     */
    private $_failedAttemptsPeriod = 0;

    /**
     * @var string Account config file name
     */
    private $_accountConfig = 'account.';


    /**
     * Initialize properties and other stuff
     */
    public function __construct() {
        parent::__construct();
        $this->_setConfigValues();
    }


    /**
     * Check if current user has entered too many wrong verification codes
     *
     * @param int $userId
     * @return bool
     * @throws Exception
     */
    public function checkFailedAttempts($userId) {

        if (!$userId || !is_numeric($userId)) {
            throw new Exception("Missing or invalid user id parameter for ".__METHOD__);
        }

        $date = new DateTime();
        $date->setTimestamp(time() - $this->_failedAttemptsPeriod);
        $mysqlTimestamp = $date->format('Y-m-d H:i:s');

        // Build sql
        $sql = "SELECT COUNT(TwoFactorAuthLogId) AS FailedAttempts ";
        $sql .= "FROM {$this->_tableName} ";
        $sql .= "WHERE UserId = ? ";
        $sql .= "AND Timestamp > ? ";
        $sql .= "LIMIT {$this->_maxFailedAttempts};";

        // Run sql
        $query = DB::select($sql, array($userId, $mysqlTimestamp));

        if ($query[0]->FailedAttempts < 1) {
            return false;
        }

        return true;
    }


    /**
     * Insert in database a failed verification code attempt for the given user
     *
     * @param int $userId
     * @throws Exception
     */
    public function saveFailedLog($userId) {

        if (!$userId || !is_numeric($userId)) {
            throw new Exception("Invalid or missing user id parameter for ".__METHOD);
        }

        // Insert in database
        $insertId = $this->insertGetId(array(
            'UserId' => $userId,
            'ValidVerificationCode' => 0
        ));

        if (!$insertId) {
            throw new Exception("Failed verification code attempt not inserted");
        }
    }


    /**
     * Get values from account config file
     *
     * @throws Exception
     */
    private function _setConfigValues() {

        $this->_maxFailedAttempts = Config::get($this->_accountConfig . 'maxSmsVerificationCodeAttempts');
        $this->_failedAttemptsPeriod = Config::get($this->_accountConfig . 'smsVerificationCodePeriod');

        if (!$this->_maxFailedAttempts) {
            throw new Exception("Invalid or missing 'maxSmsVerificationCodeAttempts' config");
        }
        if (!$this->_failedAttemptsPeriod) {
            throw new Exception("Missing 'smsVerificationCodePeriod' config");
        }
    }
}