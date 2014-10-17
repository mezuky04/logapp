<?php

/**
 * Class CountriesModel
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class CountriesModel extends BaseModel {

    /**
     * @var string Table name
     */
    protected $_tableName = 'Countries';

    /**
     * @var array Table fields
     */
    protected $_tableFields = array('CountryId', 'Name');
}