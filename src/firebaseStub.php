<?php

namespace Firebase;

require_once __DIR__ . '/firebaseError.php';
require_once __DIR__ . '/firebaseInterface.php';

/**
 * Class FirebaseStub
 *
 * Stubs the Firebase interface without issuing any cURL requests.
 *
 * @package Firebase
 */
class FirebaseStub implements FirebaseInterface
{
    private $response;
    public $baseURI;
    public $token;

    private $sslConnection;
    public $timeout;

    /**
     * @param string $baseURI
     * @param string $token
     */
    public function __construct($baseURI = '', $token = '')
    {
        if (!extension_loaded('curl')) {
            trigger_error('Extension CURL is not loaded.', E_USER_ERROR);
        }

        $this->setBaseURI($baseURI);
        $this->setTimeOut(10);
        $this->setToken($token);
        $this->setSSLConnection(false);
    }

    /**
     * @param $token
     * @return null
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @param $baseURI
     * @return null
     */
    public function setBaseURI($baseURI)
    {
        $baseURI .= (substr($baseURI, -1) === '/' ? '' : '/');
        $this->baseURI = $baseURI;
    }

    /**
     * Enabling/Disabling SSL Connection
     *
     * @param bool $enableSSLConnection
     */
    public function setSSLConnection($enableSSLConnection)
    {
        $this->sslConnection = $enableSSLConnection;
    }

    /**
     * Returns status of SSL Connection
     *
     * @return boolean
     */
    public function getSSLConnection()
    {
        return $this->sslConnection;
    }

    /**
     * @param $seconds
     * @return null
     */
    public function setTimeOut($seconds)
    {
        $this->timeout = $seconds;
    }

    /**
     * @param $path
     * @param $data
     * @param $options
     * @return null
     */
    public function set($path, $data, array $options = [])
    {
        return $this->getSetResponse($data);
    }

    /**
     * @param $path
     * @param $data
     * @param $options
     * @return null
     */
    public function push($path, $data, array $options = [])
    {
        return $this->set($path, $data);
    }

    /**
     * @param $path
     * @param $data
     * @param $options
     * @return null
     */
    public function update($path, $data, array $options = [])
    {
        return $this->set($path, $data);
    }

    /**
     * @param $path
     * @param $options
     * @return null
     */
    public function get($path, array $options = [])
    {
        return $this->getGetResponse();
    }

    /**
     * @param $path
     * @param $options
     * @return null
     */
    public function delete($path, array $options = [])
    {
        return $this->getDeleteResponse();
    }

    /**
     * @param $expectedResponse
     */
    public function setResponse($expectedResponse)
    {
        $this->response = $expectedResponse;
    }

    /**
     * @uses $this->baseURI
     * @return Error
     */
    private function isBaseURIValid()
    {
        $error = preg_match('/^https:\/\//', $this->baseURI);
        $message = 'Firebase does not support non-ssl traffic. Please try your request again over https.';
        return new Error($error === 0, $message);
    }

    /**
     * @param $data
     * @return Error
     */
    private function isDataValid($data)
    {
        if ($data === '' || $data === null) {
            return new Error(true, 'Missing data; Perhaps you forgot to send the data.');
        }
        $error = json_decode($data);
        $message = "Invalid data; couldn't parse JSON object, array, or value. " .
            "Perhaps you're using invalid characters in your key names.";
        return new Error($error === null, $message);
    }

    /**
     * @param $data
     * @return null
     */
    private function getSetResponse($data)
    {
        $validBaseUriObject = $this->isBaseURIValid();
        if ($validBaseUriObject->error) {
            return $validBaseUriObject->message;
        }

        $validDataObject = $this->isDataValid($data);
        if ($validDataObject->error) {
            return $validDataObject->message;
        }

        return $this->response;
    }

    /**
     * @return null
     */
    private function getGetResponse()
    {
        $validBaseUriObject = $this->isBaseURIValid();
        if ($validBaseUriObject->error) {
            return $validBaseUriObject->message;
        }
        return $this->response;
    }

    /**
     * @return null
     */
    private function getDeleteResponse()
    {
        return $this->getGetResponse();
    }
}
