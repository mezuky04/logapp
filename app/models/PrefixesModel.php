<?php

/**
 * Class PrefixesModel
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class PrefixesModel extends BaseModel {

    /**
     * @var string Table name
     */
    protected $_tableName = 'Prefixes';

    /**
     * @var array Table fields
     */
    protected $_tableFields = array('PrefixId', 'Prefix', 'CountryId');


    /**
     * Get PrefixId of the given $prefix
     *
     * @param string $prefix
     * @return string
     * @throws InvalidPhoneNumberPrefixException
     */
    public function getPrefixIdByPrefix($prefix) {
        if (!ctype_digit($prefix)) {
            throw new InvalidPhoneNumberPrefixException("Given phone number prefix is invalid");
        }
        return $this->getOne(array('PrefixId'), array('Prefix' => $prefix));
    }


    /**
     * Check if the given $prefix exists in database
     *
     * @param string $prefix
     * @return bool True if prefix exists else false
     * @throws InvalidPhoneNumberPrefixException
     */
    public function checkIfPrefixExists($prefix) {
        if (!ctype_digit($prefix)) {
            throw new InvalidPhoneNumberPrefixException("Given phone number prefix is invalid");
        }
        if ($this->count(array('Prefix' => $prefix))) {
            return true;
        }
        return false;
    }
}