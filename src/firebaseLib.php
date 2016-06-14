<?php
namespace Firebase;

require_once __DIR__ . '/firebaseInterface.php';

use \Exception;


/**
 * Firebase PHP Client Library
 *
 * @author Tamas Kalman <ktamas77@gmail.com>
 * @url    https://github.com/ktamas77/firebase-php/
 * @link   https://www.firebase.com/docs/rest-api.html
 */

/**
 * Firebase PHP Class
 *
 * @author Tamas Kalman <ktamas77@gmail.com>
 * @link   https://www.firebase.com/docs/rest-api.html
 */
class FirebaseLib implements FirebaseInterface
{
    private $_baseURI;
    private $_timeout;
    private $_token;
    private $_curlHandler;

    /**
     * Constructor
     *
     * @param string $baseURI
     * @param string $token
     */
    function __construct($baseURI = '', $token = '')
    {
        if ($baseURI == '') {
            trigger_error('You must provide a baseURI variable.', E_USER_ERROR);
        }

        if (!extension_loaded('curl')) {
            trigger_error('Extension CURL is not loaded.', E_USER_ERROR);
        }

        $this->setBaseURI($baseURI);
        $this->setTimeOut(10);
        $this->setToken($token);
        $this->initCurlHandler();
    }

    /**
     * Initializing the CURL handler
     *
     * @return void
     */
    public function initCurlHandler()
    {
        $this->_curlHandler = curl_init();
    }

    /**
     * Closing the CURL handler
     *
     * @return void
     */
    public function closeCurlHandler()
    {
        curl_close($this->_curlHandler);
    }

    /**
     * Sets Token
     *
     * @param string $token Token
     *
     * @return void
     */
    public function setToken($token)
    {
        $this->_token = $token;
    }

    /**
     * Sets Base URI, ex: http://yourcompany.firebase.com/youruser
     *
     * @param string $baseURI Base URI
     *
     * @return void
     */
    public function setBaseURI($baseURI)
    {
        $baseURI .= (substr($baseURI, -1) == '/' ? '' : '/');
        $this->_baseURI = $baseURI;
    }

    /**
     * Returns with the normalized JSON absolute path
     *
     * @param  string $path Path
     * @param  array $options Options
     * @return string
     */
    private function _getJsonPath($path, $options = array())
    {
        $url = $this->_baseURI;
        if ($this->_token !== '') {
            $options['auth'] = $this->_token;
        }
        $path = ltrim($path, '/');
        return $url . $path . '.json?' . http_build_query($options);
    }

    /**
     * Sets REST call timeout in seconds
     *
     * @param integer $seconds Seconds to timeout
     *
     * @return void
     */
    public function setTimeOut($seconds)
    {
        $this->_timeout = $seconds;
    }

    /**
     * Writing data into Firebase with a PUT request
     * HTTP 200: Ok
     *
     * @param string $path Path
     * @param mixed $data Data
     * @param array $options Options
     *
     * @return array Response
     */
    public function set($path, $data, $options = array())
    {
        return $this->_writeData($path, $data, 'PUT', $options);
    }

    /**
     * Pushing data into Firebase with a POST request
     * HTTP 200: Ok
     *
     * @param string $path Path
     * @param mixed $data Data
     * @param array $options Options
     *
     * @return array Response
     */
    public function push($path, $data, $options = array())
    {
        return $this->_writeData($path, $data, 'POST', $options);
    }

    /**
     * Updating data into Firebase with a PATH request
     * HTTP 200: Ok
     *
     * @param string $path Path
     * @param mixed $data Data
     * @param array $options Options
     *
     * @return array Response
     */
    public function update($path, $data, $options = array())
    {
        return $this->_writeData($path, $data, 'PATCH', $options);
    }

    /**
     * Reading data from Firebase
     * HTTP 200: Ok
     *
     * @param string $path Path
     * @param array $options Options
     *
     * @return array Response
     */
    public function get($path, $options = array())
    {
        try {
            $ch = $this->_getCurlHandler($path, 'GET', $options);
            $return = curl_exec($ch);
        } catch (Exception $e) {
            $return = null;
        }
        return $return;
    }

    /**
     * Deletes data from Firebase
     * HTTP 204: Ok
     *
     * @param string $path Path
     * @param array $options Options
     *
     * @return array Response
     */
    public function delete($path, $options = array())
    {
        try {
            $ch = $this->_getCurlHandler($path, 'DELETE', $options);
            $return = curl_exec($ch);
        } catch (Exception $e) {
            $return = null;
        }
        return $return;
    }

    /**
     * Returns with Initialized CURL Handler
     *
     * @param string $path Path
     * @param string $mode Mode
     * @param array $options Options
     *
     * @return resource Curl Handler
     */
    private function _getCurlHandler($path, $mode, $options = array())
    {
        $url = $this->_getJsonPath($path, $options);
        $ch = $this->_curlHandler;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->_timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->_timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $mode);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        return $ch;
    }

    private function _writeData($path, $data, $method = 'PUT', $options = array())
    {
        $jsonData = json_encode($data);
        $header = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        );
        try {
            $ch = $this->_getCurlHandler($path, $method, $options);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            $return = curl_exec($ch);
        } catch (Exception $e) {
            $return = null;
        }
        return $return;
    }

}
