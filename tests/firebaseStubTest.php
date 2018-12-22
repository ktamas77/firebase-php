<?php
namespace Firebase;

require_once __DIR__ . '/../src/firebaseStub.php';

class FirebaseStubTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FirebaseStub
     */
    protected $firebaseStub;

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
        $this->firebaseStub = new FirebaseStub(self::DEFAULT_URL, self::DEFAULT_TOKEN);
    }

    public function testBaseURIInitializationOnInstantiation()
    {
        $this->assertEquals(self::DEFAULT_TOKEN, $this->firebaseStub->token);
    }

    public function testSetBaseURI()
    {
        $actualResponse = $this->firebaseStub->setBaseURI(self::UPDATED_URI);
        $this->assertEquals(null, $actualResponse);

        $this->assertEquals(self::UPDATED_URI, $this->firebaseStub->baseURI);
    }

    public function testTokenInitializationOnInstantiation()
    {
        $this->assertEquals(self::DEFAULT_TOKEN, $this->firebaseStub->token);
    }

    public function testSetToken()
    {
        $actualResponse = $this->firebaseStub->setToken(self::UPDATED_TOKEN);
        $this->assertEquals(null, $actualResponse);

        $this->assertEquals(self::UPDATED_TOKEN, $this->firebaseStub->token);
    }

    public function testTimeoutInitializationOnInstantiation()
    {
        $this->assertEquals(self::DEFAULT_TIMEOUT, $this->firebaseStub->timeout);
    }

    public function testSetTimeout()
    {
        $actualResponse = $this->firebaseStub->setTimeout(self::UPDATED_TIMEOUT);
        $this->assertEquals(null, $actualResponse);

        $this->assertEquals(self::UPDATED_TIMEOUT, $this->firebaseStub->timeout);
    }

    public function testSet()
    {
        $this->firebaseStub->setResponse(self::DEFAULT_DATA);
        $actualResponse = $this->firebaseStub->set(self::DEFAULT_PATH, self::DEFAULT_DATA);
        $this->assertEquals(self::DEFAULT_DATA, $actualResponse);
    }

    public function testPush()
    {
        $this->firebaseStub->setResponse(self::DEFAULT_PUSH_DATA);
        $actualResponse = $this->firebaseStub->push(self::DEFAULT_PATH, self::DEFAULT_DATA);
        $this->assertEquals(self::DEFAULT_PUSH_DATA, $actualResponse);
    }

    public function testUpdate()
    {
        $this->firebaseStub->setResponse(self::DEFAULT_DATA);
        $actualResponse = $this->firebaseStub->update(self::DEFAULT_PATH, self::DEFAULT_DATA);
        $this->assertEquals(self::DEFAULT_DATA, $actualResponse);
    }

    public function testDelete()
    {
        $actualResponse = $this->firebaseStub->delete(self::DEFAULT_PATH);
        $this->assertEquals(null, $actualResponse);
    }

    public function testInvalidBaseUri()
    {
        $firebase = new FirebaseStub(self::INSECURE_URL);
        $response = $firebase->set(self::DEFAULT_PATH, self::DEFAULT_DATA);
        $this->assertEquals($this->getErrorMessages('INSECURE_URL'), $response);
    }

    public function testMissingData()
    {
        $response = $this->firebaseStub->set(self::DEFAULT_PATH, self::MISSING_DATA);
        $this->assertEquals($this->getErrorMessages('NO_DATA'), $response);
    }

    public function testNullData()
    {
        $response = $this->firebaseStub->set(self::DEFAULT_PATH, self::NULL_DATA);
        $this->assertEquals($this->getErrorMessages('NO_DATA'), $response);
    }

    private function getErrorMessages($errorCode)
    {
        $errorMessages = array(
            'INSECURE_URL' => 'Firebase does not support non-ssl traffic. Please try your request again over https.',
            'INVALID_JSON' => "Invalid data; couldn't parse JSON object, array, or value. " .
                "Perhaps you're using invalid characters in your key names.",
            'NO_DATA' => 'Missing data; Perhaps you forgot to send the data.'
        );
        return $errorMessages[$errorCode];
    }
}
