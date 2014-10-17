<?php

/**
 * Class TwoFactorAuthVerificationCodesModel
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class TwoFactorAuthVerificationCodesModel extends BaseModel {

    /**
     * @var string Table name
     */
    protected $_tableName = "TwoFactorAuthVerificationCodes";

    /**
     * @var array Table fields
     */
    protected $_tableFields = array(
        'TwoFactorAuthVerificationCodeId',
        'VerificationCode',
        'UserId',
        'Timestamp',
        'Verified'
    );

    /**
     * @var string Verification code
     */
    private $_verificationCode = "";

    /**
     * @var int Verification code length
     */
    private $_verificationCodeLength = 4;

    /**
     * @var string User email
     */
    private $_phoneNumber = "";

    /**
     * @var int User id
     */
    private $_userId = null;

    /**
     * @var string Sms config file name
     */
    private $_smsConfig = "sms.";

    /**
     * @var string Account config file name
     */
    private $_accountConfig = "account.";


    /**
     * Send verification code to the user
     *
     * @param string $email
     */
    public function sendTwoFactorAuthVerificationCode($email) {
        // Get user id
        $this->_getUserId($email);

        // Get user phone number
        $this->_getUserPhoneNumber();

        // Generate verification code and insert in database (also delete other verification codes of this user)
        $this->_generateAuthVerificationCode();

        // Send sms with verification code
        $this->_sendVerificationCode();
    }


    /**
     * Check if given verification exists for the given user id
     *
     * @param string $verificationCode
     * @param int $userId
     * @return bool
     */
    public function checkTwoFactorAuthVerificationCode($verificationCode, $userId) {

        // Build SQL
        $sql = "SELECT COUNT(*) AS Codes ";
        $sql .= "FROM {$this->_tableName} ";
        $sql .= "WHERE VerificationCode = ? ";
        $sql .= "AND UserId = ? ";
        $sql .= "LIMIT 0, 1";

        // Run SQL
        $query = DB::select($sql, array($verificationCode, $userId));
        if (!$query) {
            return false;
        }
        $result = $query[0];
        if ($result->Codes) {
            $this->_setVerificationCodeToVerified($userId);
            return true;
        }
    }


    /**
     * Check if verification code is required for given user id
     *
     * @param int $userId
     * @return bool
     */
    public function checkIfVerificationCodeIsRequired($userId) {
        // Build SQL
        $sql = "SELECT COUNT(VerificationCode) AS Rows ";
        $sql .= "FROM {$this->_tableName} ";
        $sql .= "WHERE UserId = ? ";
        $sql .= "AND Verified = ? ";
        $sql .= "LIMIT 0, 1";

        // Run SQL
        $query = DB::select($sql, array($userId, 0));
        if (!$query) {
            return false;
        }
        $result = $query[0];
        if ($result->Rows) {
            return true;
        }
        return false;
    }


    /**
     * Set verification code to verified for the given user id
     *
     * @param int $userId
     */
    private function _setVerificationCodeToVerified($userId) {
        $this->update(array('Verified' => '1'), array('UserId' => $userId));
    }


    /**
     * Get user id that match the given user email
     *
     * @param int $userEmail
     * @throws Exception
     */
    private function _getUserId($userEmail) {
        if (!$userEmail) {
            throw new Exception("Missing user email parameter");
        }
        $usersModel = new UsersModel();
        $result = $usersModel->getOne(array('UserId'), array('Email' => $userEmail));
        $this->_userId = $result->UserId;
    }


    /**
     * Get user phone number
     */
    private function _getUserPhoneNumber() {
        $usersModel = new UsersModel();
        $this->_phoneNumber = $usersModel->getUserPhoneNumber($this->_userId);
    }


    /**
     * Generate a new verification code, save in database and delete other verification codes for current user
     *
     * @throws Exception
     */
    private function _generateAuthVerificationCode() {

        // Generate verification code
        $this->_generateRandomString();

        // Start transaction
        DB::beginTransaction();

        // Delete old verification codes
        $this->deleteOldVerificationCodes($this->_userId);

        // Insert in database generated verification code
        $insertId = $this->insertGetId(array(
            'VerificationCode' => $this->_verificationCode,
            'UserId' => $this->_userId
        ));

        // Check if verification code was inserted
        if (!$insertId) {
            DB::rollback();
            throw new Exception("Verification code could not be inserted in database");
        }

        DB::commit();
    }


    private function _sendVerificationCode() {
        // Get config values
        $apiKey = Config::get($this->_smsConfig.'apiKey');
        $apiSecret = Config::get($this->_smsConfig.'apiSecret');
        $from = Config::get($this->_accountConfig.'smsVerificationCodeFrom');
        $content = "Your login code is {$this->_verificationCode}";

        // Send sms
//        $nexmoSms = new NexmoSms($apiKey, $apiSecret);
//        $nexmoSms->setSMSParams(array('type' => 'text'));
//        $nexmoSms->sendSMS($from, $this->_phoneNumber, $content);
    }


    /**
     * Delete verification codes that match the given user id
     *
     * @param int $userId
     * @throws Exception
     */
    public function deleteOldVerificationCodes($userId) {

        if (!$userId || !is_numeric($userId)) {
            throw new Exception("Missing or invalid user id parameter for ".__METHOD__);
        }

        $this->delete(array(
            'UserId' => $userId
        ));
    }


    /**
     * Generate random string used as verification code
     */
    private function _generateRandomString() {
        $this->_verificationCode = substr(str_shuffle('0123456789'), 0, $this->_verificationCodeLength);
    }
}