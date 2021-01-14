<?php

namespace Firebase;

use PHPUnit\Framework\TestCase;

class FirebaseStubTest extends TestCase
{
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

    public function setUp(): void
    {
        $this->firebaseStub = new FirebaseStub(self::DEFAULT_URL, self::DEFAULT_TOKEN);
    }

    public function testBaseURIInitializationOnInstantiation(): void
    {
        $this->assertEquals(self::DEFAULT_TOKEN, $this->firebaseStub->getToken());
    }

    public function testSetBaseURI(): void
    {
        $this->firebaseStub->setBaseURI(self::UPDATED_URI);
        $this->assertEquals(self::UPDATED_URI, $this->firebaseStub->getBaseURI());
    }

    public function testGetSSLConnection(): void
    {
        $sslConnection = $this->firebaseStub->getSSLConnection();
        $this->assertEquals(true, $sslConnection);
    }

    public function testTokenInitializationOnInstantiation(): void
    {
        $this->assertEquals(self::DEFAULT_TOKEN, $this->firebaseStub->getToken());
    }

    public function testSetToken(): void
    {
        $this->firebaseStub->setToken(self::UPDATED_TOKEN);
        $this->assertEquals(self::UPDATED_TOKEN, $this->firebaseStub->getToken());
    }

    public function testTimeoutInitializationOnInstantiation(): void
    {
        $this->assertEquals(self::DEFAULT_TIMEOUT, $this->firebaseStub->getTimeOut());
    }

    public function testSetTimeout(): void
    {
        $this->firebaseStub->setTimeout(self::UPDATED_TIMEOUT);
        $this->assertEquals(self::UPDATED_TIMEOUT, $this->firebaseStub->getTimeOut());
    }

    public function testSet(): void
    {
        $this->firebaseStub->setResponse(self::DEFAULT_DATA);
        $actualResponse = $this->firebaseStub->set(self::DEFAULT_PATH, self::DEFAULT_DATA);
        $this->assertEquals(self::DEFAULT_DATA, $actualResponse);
    }

    public function testPush(): void
    {
        $this->firebaseStub->setResponse(self::DEFAULT_PUSH_DATA);
        $actualResponse = $this->firebaseStub->push(self::DEFAULT_PATH, self::DEFAULT_DATA);
        $this->assertEquals(self::DEFAULT_PUSH_DATA, $actualResponse);
    }

    public function testUpdate(): void
    {
        $this->firebaseStub->setResponse(self::DEFAULT_DATA);
        $actualResponse = $this->firebaseStub->update(self::DEFAULT_PATH, self::DEFAULT_DATA);
        $this->assertEquals(self::DEFAULT_DATA, $actualResponse);
    }

    public function testDelete(): void
    {
        $actualResponse = $this->firebaseStub->delete(self::DEFAULT_PATH);
        $this->assertEquals(null, $actualResponse);
    }
}
