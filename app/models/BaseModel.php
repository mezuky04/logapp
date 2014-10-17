<?php

/**
 * Class BaseModel
 *
 * Implements basic database operations like select, update, delete
 */
abstract class BaseModel {

    /**
     * @var string Table name
     */
    protected $_tableName = '';

    /**
     * @var array Table fields
     */
    protected $_tableFields = array();

    /**
     * @var null Database instance
     */
    private $_query = null;


    /**
     * Check if all properties are set
     */
    public function __construct() {
        if (!$this->_tableName) {
            throw new Exception("Undefined table name");
        }
        if (!count($this->_tableFields)) {
            throw new Exception("Undefined table fields");
        }
    }


    /**
     * Get one record from database that match the given filter
     *
     * @param array $fields
     * @param array $filter key => value
     */
    public function getOne($fields = array(), $filter = array()) {

        $query = DB::table($this->_tableName);

        // If no params are parsed, return the first record from db with all columns
        if (!count($fields) && !count($filter)) {
            return $query->first();
        }

        // Build the select
        foreach ($fields as $field) {
            $query->addSelect($field);
        }

        // If no filter is parsed return first record with specified columns
        if (!count($filter) || !is_array($filter)) {
            return $query->first();
        }

        // Build where
        foreach ($filter as $key => $value) {
            $query->where($key, $value);
        }

        return $query->first();
    }



    /**
     * Get all records that match the given $filter
     *
     * @param array $fields To use in SELECT clause
     * @param array $filter key => value pairs to use in WHERE clause
     * @return mixed
     */
    public function getAll($fields = array(), $filter = array()) {

        $this->_query = DB::table($this->_tableName);

        // Clean fields
        $this->_cleanFields($fields);

        // Add select fields
        $this->_buildSelect($fields);

        // Build where
        $this->_buildWhere($filter);

        return $this->_query->get();
    }


    public function count($filter = array()) {

        $this->_query = DB::table($this->_tableName);

        // Build where condition if a filter was given
        if (count($filter)) {
            $this->_buildWhere($filter);
        }

        return $this->_query->count();
    }


    /**
     * Insert one or more records
     *
     * @param array $record key => value pairs
     */
    public function insert($record = array()) {

        $this->_query = DB::table($this->_tableName);

        if (!count($record)) {
            return;
        }
        $this->_query->insert($record);
    }


    /**
     * Insert one record and get their id
     *
     * @param array $record key => value pairs
     * @return int Insert id
     */
    public function insertGetId($record = array()) {

        $this->_query = DB::table($this->_tableName);

        if (!count($record)) {
            return;
        }
        return $this->_query->insertGetId($record);
    }


    /**
     * Update records that match the given filter
     *
     * @param array $record key => value pairs
     * @param array $filter key => value pairs to be used in WHERE clause
     */
    public function update($record = array(), $filter = array()) {

        $this->_query = DB::table($this->_tableName);

        // Build where
        $this->_buildWhere($filter);

        $this->_query->update($record);
    }


    /**
     * Delete records that match the given filter
     *
     * @param array $filter key => value pairs to use in WHERE clause
     */
    public function delete($filter = array()) {

        $this->_query = DB::table($this->_tableName);

        // Build where
        $this->_buildWhere($filter);

        $this->_query->delete();
    }


    /**
     * Truncate a table
     */
    public function truncate() {

        $this->_query = DB::table($this->_tableName);

        $this->_query->truncate();
    }


    /**
     * Build select clause
     *
     * @param array $fields
     */
    private function _buildSelect($fields = array()) {
        if (!count($fields)) {
            return;
        }
        foreach ($fields as $field) {
            $this->_query->addSelect($field);
        }
    }


    /**
     * Build where clause
     *
     * @param array $filter key => value pairs
     */
    private function _buildWhere($filter = array()) {
        if (!count($filter)) {
            return;
        }
        foreach ($filter as $key => $value) {
            $this->_query->where($key, $value);
        }
    }


    /**
     * Remove unknown fields
     *
     * @param array $fields
     */
    private function _cleanFields(&$fields) {
        foreach ($fields as $key => $field) {
            if (!in_array($field, $this->_tableFields)) {
                unset($fields[$key]);
            }
        }
    }
}