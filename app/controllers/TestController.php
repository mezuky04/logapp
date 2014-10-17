<?php

class TestController extends BaseController {

    private $_testModel = null;

    public function __construct() {
        $this->_testModel = new TestModel();
    }

    public function getOne() {
        // test getOne function
        $result = $this->_testModel->getOne(array('Message'), array('TestId' => 3));
        //echo $result->TestId.'<br>'.$result->Message;
        //echo $result->Message;
        //if (!$result) {echo 'nothing found';}
    }

    public function getAll() {
        // test getAll function
        $results = $this->_testModel->getAll(array(), array('TestId' => 2));
        foreach ($results as $result) {
            echo $result->Message.'<br>';
        }
    }

    public function insert() {
        // test insert function
        $this->_testModel->insert(array('Message' => 'insert() works!'));
    }

    public function insertGetId() {
        // test insertGetId function
        echo $this->_testModel->insertGetId(array('Message' => 'insertGetId() works!'));
    }

    public function update() {
        // test update function
        $this->_testModel->update(array('Message' => 'update() also works!'), array('TestId' => 1));
    }

    public function delete() {
        // test delete function
        $this->_testModel->delete(array('TestId' => 2));
    }

    public function truncate() {
        // test truncate function
        $this->_testModel->truncate();
    }
}