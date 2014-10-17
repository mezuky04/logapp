<?php

/**
 * Class PasswordRecoversModel
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class PasswordRecoversModel {

    /**
     * @var string Table name
     */
    private $_tableName = 'PasswordRecovers';


    /**
     * Insert new recover info in database
     *
     * @param int $userId
     * @return string Recover link
     */
    public function insertNewRecover($userId) {

        // Generate unique recover code
        $recoverCode = $this->_generateVerificationCode();

        // Insert in table
        DB::table($this->_tableName)->insert(array(
            'UserId' => $userId,
            'RecoverCode' => $recoverCode,
            'Used' => 0
        ));

        // Generate and return recover link
        return URL::to('recover/'.$userId.'/'.$recoverCode);
    }


    /**
     * Check if given $userId/$recoverCode combination is valid
     *
     * @param int $userId
     * @param string $recoverCode
     * @return bool
     */
    public function checkVerificationCode($userId, $recoverCode) {

        $query = DB::table($this->_tableName);
        $query->where('UserId', $userId);
        $query->where('VerificationCode', $recoverCode);
        if ($query->count()) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    private function _generateVerificationCode() {
        $chars = 'abcdefghilklmnopqrstuvwxyz1234567890-_=+!@#$%^&*(){}[]:><?/ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return md5(str_shuffle($chars).uniqid());
    }
}