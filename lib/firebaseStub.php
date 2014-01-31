<?php
/**
 * Stubs the Firebase interface without issuing any cURL requests.
 **/

class FirebaseStub
    implements FirebaseInterface
{
    private $_response = null;

    function __construct($baseURI = '', $token = '')
    {
        if (!extension_loaded('curl')) {
            trigger_error('Extension CURL is not loaded.', E_USER_ERROR);
        }

        $this->setBaseURI($baseURI);
        $this->setTimeOut(10);
        $this->setToken($token);
    }

    public function setToken($token)
    {
        $this->_token = $token;
    }

    public function setBaseURI($baseURI)
    {
        $baseURI .= (substr($baseURI, -1) == '/' ? '' : '/');
        $this->_baseURI = $baseURI;
    }

    public function setTimeOut($seconds)
    {
        $this->_timeout = $seconds;
    }

    public function set($path, $data)
    {
      return $this->_getSetResponse($data);
    }

    public function push($path, $data)
    {
      return $this->set($path, $data);
    }

    public function update($path, $data)
    {
      return $this->set($path, $data);
    }

    public function get($path)
    {
      return $this->_getGetResponse();
    }

    public function delete($path)
    {
      return $this->_getDeleteResponse();
    }

    public function setResponse($expectedResponse)
    {
        $this->_response = $expectedResponse;
    }

    private function _isBaseURIValid() {
      $error = preg_match('/^https:\/\//', $this->_baseURI);
      return new Error(($error == 0 ? true : false), 'Firebase does not support non-ssl traffic. Please try your request again over https.');
    }

    private function _isDataValid($data) {

      if ($data == "" || $data == null) {
        return new Error(true, "Missing data; Perhaps you forgot to send the data.");
      }

      $error = json_decode($data);
      return new Error(($error ? false : true), "Invalid data; couldn't parse JSON object, array, or value. Perhaps you're using invalid characters in your key names.");
    }

    private function _getSetResponse($data)
    {
      $validBaseUriObject = $this->_isBaseURIValid();
      if ($validBaseUriObject->error) {
        return $validBaseUriObject->message;
      }

      $validDataObject = $this->_isDataValid($data);
      if ($validDataObject->error) {
        return $validDataObject->message;
      }

      return $this->_response;
    }

    private function _getGetResponse()
    {
      $validBaseUriObject = $this->_isBaseURIValid();
      if ($validBaseUriObject->error) {
        return $validBaseUriObject->message;
      }
      return $this->_response;
    }

    private function _getDeleteResponse() { return $this->_getGetResponse(); }
}

Class Error {
  function __construct($error, $message)
  {
    $this->error = $error;
    $this->message = $message;
  }
}
