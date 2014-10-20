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
    protected $_tableFields = array('CountryId', 'Name', 'Code');

    /**
     * @var string Prefixes table
     */
    private $_prefixesTable = 'Prefixes';


    public function get() {
        // Build sql
        $sql = "SELECT {$this->_tableName}.Name, {$this->_prefixesTable}.Prefix ";
        $sql .= "FROM {$this->_tableName} ";
        $sql .= "JOIN {$this->_prefixesTable} ";
        $sql .= "ON {$this->_tableName}.CountryId = {$this->_prefixesTable}.CountryId";

        return DB::select($sql);
    }

    public function getByPrefix($prefix) {
        // Build sql
        $sql = "SELECT {$this->_tableName}.Name ";
        $sql .= "FROM {$this->_tableName} ";
        $sql .= "JOIN {$this->_prefixesTable} ";
//        $sql .= "ON {$this->_prefixesTable}.Prefix = ? ";
        $sql .= "ON ({$this->_tableName}.CountryId = {$this->_prefixesTable}.CountryId ";
        $sql .= "AND {$this->_prefixesTable}.Prefix = ?) ";
        $sql .= "LIMIT 0,1";

        $result = DB::select($sql, array($prefix));
        return $result[0]->Name;
    }
}