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
}