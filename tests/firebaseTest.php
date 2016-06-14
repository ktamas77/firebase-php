<?php

namespace Firebase;

require_once __DIR__ . "/../src/firebaseLib.php";

use Exception;

class FirebaseTest extends \PHPUnit_Framework_TestCase
{
    protected $_firebase;
    protected $_todoMilk = array(
        'name' => 'Pick the milk',
        'priority' => 1
    );

    protected $_todoBeer = array(
        'name' => 'Pick the beer',
        'priority' => 2
    );

    protected $_todoLEGO = array(
        'name' => 'Pick the LEGO',
        'priority' => 3
    );

    // --- set up your own database here
    const DEFAULT_URL = 'https://kidsplace.firebaseio.com/';
    const DEFAULT_TOKEN = 'MqL0c8tKCtheLSYcygYNtGhU8Z2hULOFs9OKPdEp';
    const DEFAULT_TODO_PATH = '/sample/todo';
    const DELETE_PATH = '/sample';
    const DEFAULT_SET_RESPONSE = '{"name":"Pick the milk","priority":1}';
    const DEFAULT_UPDATE_RESPONSE = '{"name":"Pick the beer","priority":2}';
    const DEFAULT_PUSH_RESPONSE = '{"name":"Pick the LEGO","priority":3}';
    const DEFAULT_DELETE_RESPONSE = 'null';
    const DEFAULT_URI_ERROR = 'You must provide a baseURI variable.';

    public function setUp()
    {
        $this->_firebase = new FirebaseLib(self::DEFAULT_URL, self::DEFAULT_TOKEN);
    }

    public function testNoBaseURI()
    {
        $errorMessage = null;
        try {
            new FirebaseLib();
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
        }

        $this->assertEquals(self::DEFAULT_URI_ERROR, $errorMessage);
    }

    public function testSet()
    {
        $response = $this->_firebase->set(self::DEFAULT_TODO_PATH, $this->_todoMilk);
        $this->assertEquals(self::DEFAULT_SET_RESPONSE, $response);
    }

    public function testGetAfterSet()
    {
        $response = $this->_firebase->get(self::DEFAULT_TODO_PATH);
        $this->assertEquals(self::DEFAULT_SET_RESPONSE, $response);
    }

    public function testUpdate()
    {
        $response = $this->_firebase->update(self::DEFAULT_TODO_PATH, $this->_todoBeer);
        $this->assertEquals(self::DEFAULT_UPDATE_RESPONSE, $response);
    }

    public function testGetAfterUpdate()
    {
        $response = $this->_firebase->get(self::DEFAULT_TODO_PATH);
        $this->assertEquals(self::DEFAULT_UPDATE_RESPONSE, $response);
    }

    public function testPush()
    {
        $response = $this->_firebase->push(self::DEFAULT_TODO_PATH, $this->_todoLEGO);
        $this->assertRegExp('/{"name"\s?:\s?".*?}/', $response);
        return $this->_parsePushResponse($response);
    }

    /**
     * @depends testPush
     */
    public function testGetAfterPush($responseName)
    {
        $response = $this->_firebase->get(self::DEFAULT_TODO_PATH . '/' . $responseName);
        $this->assertEquals(self::DEFAULT_PUSH_RESPONSE, $response);
    }

    public function testDelete()
    {
        $response = $this->_firebase->delete(self::DELETE_PATH);
        $this->assertEquals(self::DEFAULT_DELETE_RESPONSE, $response);
    }

    public function testGetAfterDELETE()
    {
        $response = $this->_firebase->get(self::DEFAULT_TODO_PATH);
        $this->assertEquals(self::DEFAULT_DELETE_RESPONSE, $response);
    }

    /**
     * @param $response
     * @return mixed
     */
    private function _parsePushResponse($response)
    {
        $responseObj = json_decode($response);
        return $responseObj->name;
    }
}
