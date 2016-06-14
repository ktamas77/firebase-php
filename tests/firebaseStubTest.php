<?php
namespace Firebase;

require_once __DIR__ . '/../src/firebaseStub.php';

class FirebaseStubTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FirebaseStub
     */
    protected $_firebaseStub;

    const DEFAULT_URL = 'https://firebaseStub.firebaseio.com/';
    const DEFAULT_TOKEN = 'MqL0c8tKCtheLSYcygBrIanhU8Z2hULOFs9OKPdEp';
    const DEFAULT_TIMEOUT = 10;
    const DEFAULT_PATH = 'example/path';
    const DEFAULT_DATA = '{"firstName": "Howdy", "lastName": "Doody"}';
    const DEFAULT_PUSH_DATA = '{"firstName": "1skdSDdksdlisS"}';

    const UPDATED_URI = 'https://myfirebaseStub.firebaseio.com/';
    const UPDATED_TOKEN = 'MqL0c8tEmBeRLSYcygBrIanhU8Z2hULOFs9OKPdEp';
    const UPDATED_TIMEOUT = 30;

    const INSECURE_URL = 'http://insecure.firebaseio.com';
    const INVALID_DATA = '"firstName" "Howdy", "lastName": "Doody" "": ';
    const MISSING_DATA = '';
    const NULL_DATA = null;

    public function setUp()
    {
        $this->_firebaseStub = new FirebaseStub(self::DEFAULT_URL, self::DEFAULT_TOKEN);
    }

    public function testBaseURIInitializationOnInstantiation()
    {
        $this->assertEquals(self::DEFAULT_TOKEN, $this->_firebaseStub->_token);
    }

    public function testSetBaseURI()
    {
        $actualResponse = $this->_firebaseStub->setBaseURI(self::UPDATED_URI);
        $this->assertEquals(null, $actualResponse);

        $this->assertEquals(self::UPDATED_URI, $this->_firebaseStub->_baseURI);
    }

    public function testTokenInitializationOnInstantiation()
    {
        $this->assertEquals(self::DEFAULT_TOKEN, $this->_firebaseStub->_token);
    }

    public function testSetToken()
    {
        $actualResponse = $this->_firebaseStub->setToken(self::UPDATED_TOKEN);
        $this->assertEquals(null, $actualResponse);

        $this->assertEquals(self::UPDATED_TOKEN, $this->_firebaseStub->_token);
    }

    public function testTimeoutInitializationOnInstantiation()
    {
        $this->assertEquals(self::DEFAULT_TIMEOUT, $this->_firebaseStub->_timeout);
    }

    public function testSetTimeout()
    {
        $actualResponse = $this->_firebaseStub->setTimeout(self::UPDATED_TIMEOUT);
        $this->assertEquals(null, $actualResponse);

        $this->assertEquals(self::UPDATED_TIMEOUT, $this->_firebaseStub->_timeout);
    }

    public function testSet()
    {
        $this->_firebaseStub->setResponse(self::DEFAULT_DATA);
        $actualResponse = $this->_firebaseStub->set(self::DEFAULT_PATH, self::DEFAULT_DATA);
        $this->assertEquals(self::DEFAULT_DATA, $actualResponse);
    }

    public function testPush()
    {
        $this->_firebaseStub->setResponse(self::DEFAULT_PUSH_DATA);
        $actualResponse = $this->_firebaseStub->push(self::DEFAULT_PATH, self::DEFAULT_DATA);
        $this->assertEquals(self::DEFAULT_PUSH_DATA, $actualResponse);
    }

    public function testUpdate()
    {
        $this->_firebaseStub->setResponse(self::DEFAULT_DATA);
        $actualResponse = $this->_firebaseStub->update(self::DEFAULT_PATH, self::DEFAULT_DATA);
        $this->assertEquals(self::DEFAULT_DATA, $actualResponse);
    }

    public function testDelete()
    {
        $actualResponse = $this->_firebaseStub->delete(self::DEFAULT_PATH);
        $this->assertEquals(null, $actualResponse);
    }

    public function testInvalidBaseUri()
    {
        $firebase = new FirebaseStub(self::INSECURE_URL);
        $response = $firebase->set(self::DEFAULT_PATH, self::DEFAULT_DATA);
        $this->assertEquals($this->_getErrorMessages('INSECURE_URL'), $response);
    }

    public function testMissingData()
    {
        $response = $this->_firebaseStub->set(self::DEFAULT_PATH, self::MISSING_DATA);
        $this->assertEquals($this->_getErrorMessages('NO_DATA'), $response);
    }

    public function testNullData()
    {
        $response = $this->_firebaseStub->set(self::DEFAULT_PATH, self::NULL_DATA);
        $this->assertEquals($this->_getErrorMessages('NO_DATA'), $response);
    }

    private function _getErrorMessages($errorCode)
    {
        $errorMessages = Array(
            'INSECURE_URL' => 'Firebase does not support non-ssl traffic. Please try your request again over https.',
            'INVALID_JSON' => 'Invalid data; couldn\'t parse JSON object, array, or value. Perhaps you\'re using invalid characters in your key names.',
            'NO_DATA' => 'Missing data; Perhaps you forgot to send the data.'
        );

        return $errorMessages[$errorCode];
    }
}
