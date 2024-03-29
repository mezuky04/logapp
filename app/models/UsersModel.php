<?php

/**
 * Class Users
 *
 * Handle database operations on Users table
 *
 * @author Alexandru Bugarin <alexandru.bbugarin@gmail.com>
 */
class UsersModel extends BaseModel {

    /**
     * @var string Table name
     */
    protected $_tableName = 'Users';

    /**
     * @var array Table fields
     */
    protected $_tableFields = array('UserId', 'Email', 'Password');

    /**
     * @var string Name of PhoneNumbers table
     */
    private $_phoneNumbersTable = 'PhoneNumbers';

    /**
     * @var string Name of AccountPasswords table
     */
    private $_accountPasswordsTable = 'AccountPasswords';

    /**
     * @var string Name of Prefixes table
     */
    private $_prefixesTable = 'Prefixes';

    /**
     * @var int Search results limit
     */
    private $_searchLimit = 5;

    /**
     * @var string Account config file name
     */
    private $_accountConfig = 'account.';

    /**
     * @var string Sms config file name
     */
    private $_smsConfig = 'sms.';


    /**
     * Get user id, email and password hash that match the given email
     *
     * @param string $email
     * @return mixed
     * @throws Exception
     */
    public function getUser($email) {

        if (strlen($email) < 1) {
            throw new Exception("Invalid or empty email parameter");
        }

        // Build SQL
        $sql = "SELECT {$this->_tableName}.UserId, ";
        $sql .= "{$this->_tableName}.Email, ";
        $sql .= "{$this->_accountPasswordsTable}.Password ";
        $sql .= "FROM {$this->_tableName} ";
        $sql .= "LEFT JOIN {$this->_accountPasswordsTable} ";
        $sql .= "ON {$this->_tableName}.PasswordId = {$this->_accountPasswordsTable}.AccountPasswordId ";
        $sql .= "WHERE {$this->_tableName}.Email = ? ";
        $sql .= "LIMIT 0, 1;";

        $result = DB::select($sql, array($email));
        if ($result) {
            return $result[0];
        }
        return false;
    }

    /**
     * Insert new user in database
     *
     * @param string $email
     * @param string $password
     * @return array With user information
     */
    public function saveUser($email, $password) {
        $user = array(
            'Email' => $email,
            'Password' => Hash::make($password)
        );
        $userId = DB::table($this->_tableName)->insertGetId($user);

        return array(
            'UserId' => $userId,
            'Email' => $email
        );
    }


    /**
     * Check if given field exists in database
     *
     * @param string $fieldName
     * @param mixed $value
     * @return bool True if given value match the given field
     */
    public function check($fieldName, $value) {
        if (DB::table($this->_tableName)->where($fieldName, $value)->first()) {
            return true;
        }

        return false;
    }

    public function checkEmail($email, $userId) {
        $sql = "SELECT COUNT(Email) AS Records ";
        $sql .= "FROM {$this->_tableName} ";
        $sql .= "WHERE Email = ? ";
        $sql .= "AND UserId != ? ";
        $sql .= "LIMIT 0, 1";

        $result = DB::select($sql, array($email, $userId));
        if ($result[0]->Records > 0) {
            return true;
        }

        return false;
    }


    /**
     * Get user password that match the given email
     *
     * @param string $email
     * @return string User password hash
     */
    public function getUserPassword($email) {
        return DB::table($this->_tableName)->where('Email', $email)->pluck('Password');
    }


    /**
     * Get user settings values
     *
     * @param int $userId
     * @return mixed
     * @throws Exception When user id parameter is missing or is not numeric
     */
    public function getUserSettings($userId) {
        // Check for user id
        if (!$userId || !is_numeric($userId)) {
            throw new Exception("Missing or invalid user id parameter");
        }

        // Build SQL
        $sql = "SELECT {$this->_tableName}.Email, ";
        $sql .= "{$this->_accountPasswordsTable}.Timestamp, ";
        $sql .= "{$this->_accountPasswordsTable}.Updated, ";
        $sql .= "{$this->_phoneNumbersTable}.PhoneNumber ";
        $sql .= "FROM {$this->_tableName} ";
        $sql .= "LEFT JOIN {$this->_accountPasswordsTable} ";
        $sql .= "ON {$this->_tableName}.PasswordId = {$this->_accountPasswordsTable}.AccountPasswordId ";
        $sql .= "LEFT JOIN {$this->_phoneNumbersTable} ";
        $sql .= "ON {$this->_tableName}.PhoneNumberId = {$this->_phoneNumbersTable}.PhoneNumberId ";
        $sql .= "WHERE {$this->_tableName}.UserId = ? ";
        $sql .= "LIMIT 0, 1";

        $result = DB::select($sql, array($userId));
        return $result[0];
    }


    /**
     * Get phone number of the given user id
     *
     * @param int $userId
     * @return string User phone number
     * @throws Exception When user don't have a phone number set
     */
    public function getUserPhoneNumber($userId) {
        if (!$userId) {
            throw new Exception("Missing user id parameter");
        }

        // Build SQL
        $sql = "SELECT {$this->_phoneNumbersTable}.PhoneNumber, {$this->_prefixesTable}.Prefix ";
        $sql .= "FROM {$this->_phoneNumbersTable} ";
        $sql .= "LEFT JOIN {$this->_tableName} ";
        $sql .= "ON {$this->_phoneNumbersTable}.PhoneNumberId = {$this->_tableName}.PhoneNumberId ";
        $sql .= "LEFT JOIN {$this->_prefixesTable} ";
        $sql .= "ON {$this->_phoneNumbersTable}.PrefixId = {$this->_prefixesTable}.PrefixId ";
        $sql .= "WHERE {$this->_tableName}.UserId = ? ";
        $sql .= "LIMIT 0, 1";

        // Run SQL
        $result = DB::select($sql, array($userId));

        if (!$result) {
            // User don't have a phone number set
            throw new Exception("User don't have a phone number set");
        }

        $phoneNumber = $result[0];

        return $phoneNumber->Prefix . $phoneNumber->PhoneNumber;
    }


    /**
     * Get user tow factor auth status
     *
     * @param int $userId
     * @return bool Two factor auth status
     * @throws Exception If given user id is missing or invalid
     */
    public function getUserTwoFactorAuthStatus($userId) {
        if (!$userId || !is_numeric($userId)) {
            throw new Exception("Missing or invalid user id parameter from ".__METHOD__);
        }
        if ($this->count(array('UserId' => $userId, 'TwoFactorAuth' => 1))) {
            // Two factor auth is enabled
            return true;
        }

        // Two factor auth is disabled
        return false;
    }


    /**
     * Enable or disable two factor auth for the given user id
     *
     * @param int $userId
     * @param bool $status The status of the two factor auth
     * @throws Exception When User id parameter is missing or is not numeric
     */
    public function handleTwoFactorAuthStatus($userId, $status = false) {
        if (!$userId || !is_numeric($userId)) {
            throw new Exception("Missing or invalid user id parameter");
        }
        $twoFactorAuthStatus = 0;
        if ($status) {
            $twoFactorAuthStatus = 1;
        }
        $this->update(array('TwoFactorAuth' => $twoFactorAuthStatus), array('UserId' => $userId));
    }


    /**
     * Compare user level from the database with the given one
     *
     * @param int $userId
     * @param int $level
     * @return bool True if user level is >= than the given one else false
     * @throws Exception
     */
    public function compareUserLevel($userId, $level) {
        if (!$userId || !is_numeric($userId)) {
            throw new Exception("Missing or invalid user id parameter for " . __METHOD__);
        }
        if (!$level || !is_numeric($level)) {
            throw new Exception("Missing or invalid user level parameter for " . __METHOD__);
        }

        if ($this->getUserLevel($userId) < $level) {
            return false;
        }

        return true;
    }


    /**
     * Get level of the given user id
     *
     * @param int $userId
     * @return int User level
     * @throws Exception
     */
    public function getUserLevel($userId) {
        if (!is_numeric($userId)) {
            throw new Exception("Invalid user id parameter for " . __METHOD__);
        }
        $result = $this->getOne(array('Level'), array('UserId' => $userId));
        if (!$result) {
            throw new Exception("User id " . $userId . "does not have a level assigned");
        }

        return $result->Level;
    }


    /**
     * Insert new user in database
     *
     * @param array $userDetails
     * @throws Exception

     */
    public function createNewUser($userDetails) {

        $this->_checkRequiredFieldsForUserDetails($userDetails);
        DB::beginTransaction();

        // Insert user details
        $userId = $this->insertGetId(array(
            'Email' => $userDetails['email'],
            'Password' => Hash::make($userDetails['password'])
        ));
        if (!$userId) {
            throw new Exception("New user could not be inserted in database");
            DB::rollback();
        }

        // Insert phone number
        $prefixesModel = new PrefixesModel();
        $prefixData = $prefixesModel->getOne(array('PrefixId', 'CountryId'), array('Prefix' => $userDetails['phoneNumberPrefix']));
        if (!$prefixData->Prefix) {
            throw new Exception("Could not get prefix id");
            DB::rollback();
        }
        if (!$prefixData->CountryId) {
            throw new Exception("Could not get country id");
            DB::rollback();
        }
        $phoneNumbersModel = new PhoneNumbersModel();
        $phoneNumberId = $phoneNumbersModel->insertGetId(array(
            'PhoneNumber' => $userDetails['phoneNumber']
        ));
        if (!$phoneNumberId) {
            throw new Exception("Could not insert phone number in database");
            DB::rollback();
        }

        $this->_sendRegisterEmail();
        $this->_sendRegisterSmsCode();
    }


    /**
     * Validate the given email
     *
     * @param string $email
     * @return array
     */
    public function validateEmail($email) {
        // Check if email is empty
        if (empty($email)) {
            return array(
                'emailError' => true,
                'emptyEmail' => true
            );
        }

        // Check if email has a correct format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return array(
                'emailError' => true,
                'invalidEmail' => true
            );
        }

        // Check if email is already used
        if ($this->check('Email', $email)) {
            return array(
                'emailError' => true,
                'alreadyUsedEmail' => true
            );
        }
    }


    /**
     * Validate the given password
     *
     * @param string $password
     * @return array
     */
    public function validatePassword($password) {
        // Check if password is empty
        if (empty($password)) {
            return array(
                'passwordError' => true,
                'emptyPassword' => true
            );
        }

        // Check if password is too short
        if (strlen($password) < Config::get($this->_accountConfig.'minPasswordLength')) {
            return array(
                'passwordError' => true,
                'tooShortPassword' => true,
                'passwordLength' => Config::get($this->_accountConfig.'minPasswordLength')
            );
        }

        // Check if password contain alphabetic characters
        if (!preg_match('/[a-z]/i', $password)) {
            return array(
                'passwordError' => true,
                'tooSimplePassword' => true
            );
        }

        // Check if password contain numeric characters
        if (!preg_match('/[0-9]/', $password)) {
            return array(
                'passwordError' => true,
                'tooSimplePassword' => true
            );
        }
    }


    public function searchUserByEmail($email) {
        if (strlen($email) < 1) {
            throw new Exception("Email parameter is empty");
        }

        // Build sql
        $sql = "SELECT UserId, Email ";
        $sql .= "FROM {$this->_tableName} ";
        $sql .= "WHERE Email LIKE '%?%' ";
        $sql .= "LIMIT 0, {$this->_searchLimit}";

        // Execute query
        $result = DB::select($sql, array($email));
//        exit(print_r($result));
    }


    private function _sendRegisterEmail($userEmail) {
        //
    }

    private function _sendRegisterSmsCode($verificationCode, $phoneNumber) {
        $nexmoSms = new NexmoSms(Config::get($this->_smsConfig.'apiKey'), Config::get($this->_smsConfig.'apiSecret'));
        $nexmoSms->setSMSParams(array(
            'type' => 'text'
        ));
        $nexmoSms->sendSMS('from', 'to', 'content');
    }

    /**
     * Check if all user details are given when creating a new account
     *
     * @param array $details
     * @throws Exception If a required parameter is missing
     */
    private function _checkRequiredFieldsForUserDetails($details = array()) {
        $requiredUserDetails = array(
            'email',
            'password',
            'phoneNumber',
            'phoneNumberPrefix',
            'subscriptionPlan'
        );
        foreach ($requiredUserDetails as $requiredUserDetail) {
            if (!in_array($requiredUserDetail, $details)) {
                throw new Exception("User detail \"{$requiredUserDetail}\" is missing");
            }
        }
    }
}
