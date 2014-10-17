<?php

/**
 * Class LoginAttemptsModel
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class LoginAttemptsModel extends BaseModel {

    /**
     * @var string Table name
     */
    protected $_tableName = 'LoginAttempts';

    /**
     * @var string Users table name
     */
    protected $_usersTable = 'Users';

    /**
     * @var array Table fields
     */
    protected $_tableFields = array('LoginAttemptId', 'UserId', 'Ip', 'Timestamp');

    /**
     * @var int Login attempts limit
     */
    private $_attemptsLimit = 0;


    /**
     * @var string Account config file name
     */
    private $_accountConfig = 'account.';

    /**
     * @var string Ip of user who make login request
     */
    private $_loginIp = "";


    /**
     * Initialize needed stuff
     */
    public function __construct() {
        parent::__construct();

        $this->_getIp();

        // Set login attempts limit
        $this->_setAttemptsLimit();
    }


    /**
     * Count user login attempts in last 24 hours
     *
     * @param string $userEmail
     * @return bool
     * @throws Exception
     */
    public function countAttempts($userEmail) {
        // Check parameters
        if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid user email parameter");
        }

        // Build SQL
        $sql = "SELECT COUNT(*) AS Attempts ";
        $sql .= "FROM {$this->_tableName} ";
        $sql .= "LEFT JOIN {$this->_usersTable} ";
        $sql .= "ON {$this->_tableName}.UserId = {$this->_usersTable}.UserId ";
        $sql .= "WHERE {$this->_usersTable}.Email = ? ";
        $sql .= "AND {$this->_tableName}.Timestamp >= DATE_SUB(NOW(), INTERVAL 1 DAY) ";
        $sql .= "AND {$this->_tableName}.Ip = ?";

        // Run SQL
        $query = DB::select($sql, array($userEmail, $this->_loginIp));
        if (!isset($query[0])) {
            return false;
        }
        $result = $query[0];

        if ($result->Attempts >= $this->_attemptsLimit) {
            // Too much login attempts
            return true;
        }
        return false;
    }


    /**
     * Insert in database new login attempt for given user email
     *
     * @param string $userEmail
     * @throws Exception
     */
    public function log($userEmail) {
        // Validate parameters
        $this->_validateEmail($userEmail);

        // Get user id that match the given email
        $usersModel = new UsersModel();
        $user = $usersModel->getOne(array('UserId'), array('Email' => $userEmail));

        if (!$user) {
            throw new Exception("No database record match the given user email");
        }

        // Insert attempt in database
        $logId = $this->insertGetId(array(
            'UserId' => $user->UserId,
            'Ip' => $this->_loginIp
        ));

        if (!$logId) {
            throw new Exception("Login attempt not inserted");
        }
    }


    /**
     * Clean login attempts that match the given email and login ip
     *
     * @param string $userEmail
     * @throws Exception
     */
    public function clean($userEmail) {
        $this->_validateEmail($userEmail);

        // Get user id that match the given email
        $usersModel = new UsersModel();
        $user = $usersModel->getOne(array('UserId'), array('Email' => $userEmail));

        if (!$user) {
            throw new Exception("No database record match the given user email");
        }

        // Remove attempts that belong to this user with this ip
        $this->delete(array(
            'UserId' => $user->UserId,
            'Ip' => $this->_loginIp
        ));
    }


    /**
     * Validate given email or throw exception in case of failure
     *
     * @param string $email
     * @throws Exception
     */
    private function _validateEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid user email parameter");
        }
    }


    /**
     * Get ip of user who make login request
     */
    private function _getIp() {
        $this->_loginIp = Request::getClientIp();
    }


    /**
     * Set login attempts limit to use in db query
     */
    private function _setAttemptsLimit() {
        $this->_attemptsLimit = Config::get($this->_accountConfig.'maxLoginAttempts');
    }
}