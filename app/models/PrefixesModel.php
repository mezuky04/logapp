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
     * @var string Account config file name
     */
    private $_accountConfig = 'account.';

    /**
     * @var string Countries table name
     */
    private $_countriesTable = 'Countries';


    public function getDefaultPrefixAndCountry() {
        // Build sql
        $sql = "SELECT {$this->_tableName}.Prefix, {$this->_countriesTable}.Name ";
        $sql .= "FROM {$this->_tableName} ";
        $sql .= "JOIN {$this->_countriesTable} ";
        $sql .= "ON {$this->_tableName}.CountryId = {$this->_countriesTable}.CountryId ";
        $sql .= "WHERE {$this->_countriesTable}.Name = ? ";
        $sql .= "LIMIT 0,1";

        $result = DB::select($sql, array(Config::get($this->_accountConfig.'registerDefaultCountry')));
        return $result[0];
//        $formattedResult = (object) array(
//            'DefaultPrefix' => $result[0]->Prefix,
//            'DefaultPrefixCountry' => $result[0]->Name
//        );
//
//        return $formattedResult;
    }

    /**
     * Get PrefixId of the given $prefix
     *
     * @param string $prefix
     * @return string
     * @throws Exception
     */
    public function getPrefixIdByPrefix($prefix) {
        if (!ctype_digit($prefix)) {
            throw new Exception("Given phone number prefix is invalid");
        }
        return $this->getOne(array('PrefixId'), array('Prefix' => $prefix));
    }


    /**
     * Check if the given $prefix exists in database
     *
     * @param string $prefix
     * @return bool True if prefix exists else false
     * @throws Exception
     */
    public function checkIfPrefixExists($prefix) {
        if (!ctype_digit($prefix)) {
            throw new Exception("Given phone number prefix is invalid");
        }
        if ($this->count(array('Prefix' => $prefix))) {
            return true;
        }
        return false;
    }


    /**
     * Validate the given phone number prefix
     *
     * @param string $prefix
     * @return array
     */
    public function validatePhoneNumberPrefix($prefix) {
        // Check if prefix contain only digit chars, is not too big and exists in database
        if (!ctype_digit($prefix) || strlen($prefix) > Config::get($this->_accountConfig.'maxPhoneNumberPrefixLength') || !$this->checkIfPrefixExists($prefix)) {
            return array(
                'phoneNumberPrefixError' => true,
                'invalidPhoneNumberPrefix' => true
            );
        }
    }
}