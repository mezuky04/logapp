<?php

/**
 * Class PhoneNumbersModel
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class PhoneNumbersModel extends BaseModel {

    /**
     * @var string
     */
    protected $_tableName = 'PhoneNumbers';

    /**
     * @var array
     */
    protected $_tableFields = array('PhoneNumberId', 'PhoneNumber', 'PrefixId', 'CountryId');

    /**
     * @var string Account config file name
     */
    private $_accountConfig = 'account.';


    /**
     * Validate the given phone number
     *
     * @param string $phoneNumber
     * @return array
     */
    public function validatePhoneNumber($phoneNumber) {
        // Check if phone number is empty
        if (empty($phoneNumber)) {
            return array(
                'phoneNumberError' => true,
                'emptyPhoneNumber' => true
            );
        }

        // Check if phone number contains other chars than digits
        if (!ctype_digit($phoneNumber)) {
            return array(
                'phoneNumberError' => true,
                'invalidPhoneNumber' => true
            );
        }

        // Check if phone number is too short
        if (strlen($phoneNumber) < Config::get($this->_accountConfig.'minPhoneNumberLength')) {
            return array(
                'phoneNumberError' => true,
                'tooShortPhoneNumber' => true,
                'minPhoneNumberLength' => Config::get($this->_accountConfig.'minPhoneNumberLength')
            );
        }

        // Check if phone number is too long
        if (strlen($phoneNumber) > Config::get($this->_accountConfig.'maxPhoneNumberLength')) {
            return array(
                'phoneNumberError' => true,
                'tooLongPhoneNumber' => true,
                'maxPhoneNumberLength' => Config::get($this->_accountConfig.'maxPhoneNumberLength')
            );
        }
    }
}