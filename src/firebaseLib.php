<?php
namespace Firebase;

require_once __DIR__ . '/firebaseInterface.php';

use Exception;

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
    private $baseURI;
    private $timeout;
    private $token;
    private $curlHandler;
    private $sslConnection;

    /**
     * Constructor
     *
     * @param string $baseURI
     * @param string $token
     */
    public function __construct($baseURI = '', $token = '')
    {
        if ($baseURI === '') {
            trigger_error('You must provide a baseURI variable.', E_USER_ERROR);
        }

        if (!extension_loaded('curl')) {
            trigger_error('Extension CURL is not loaded.', E_USER_ERROR);
        }

        $this->setBaseURI($baseURI);
        $this->setTimeOut(10);
        $this->setToken($token);
        $this->initCurlHandler();
        $this->setSSLConnection(true);
    }

    /**
     * Initializing the CURL handler
     *
     * @return void
     */
    public function initCurlHandler()
    {
        $this->curlHandler = curl_init();
    }

    /**
     * Closing the CURL handler
     *
     * @return void
     */
    public function closeCurlHandler()
    {
        curl_close($this->curlHandler);
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
     * Sets Token
     *
     * @param string $token Token
     *
     * @return void
     */
    public function setToken($token)
    {
        $this->token = $token;
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
        $baseURI .= (substr($baseURI, -1) === '/' ? '' : '/');
        $this->baseURI = $baseURI;
    }

    /**
     * Returns with the normalized JSON absolute path
     *
     * @param  string $path Path
     * @param  array $options Options
     * @return string
     */
    private function getJsonPath($path, $options = array())
    {
        $url = $this->baseURI;
        if ($this->token !== '') {
            $options['auth'] = $this->token;
        }
        $path = ltrim($path, '/');
        $query = http_build_query($options);
        return "$url$path.json?$query";
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
        $this->timeout = $seconds;
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
    public function set($path, $data, array $options = [])
    {
        return $this->writeData($path, $data, 'PUT', $options);
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
    public function push($path, $data, array $options = [])
    {
        return $this->writeData($path, $data, 'POST', $options);
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
    public function update($path, $data, array $options = [])
    {
        return $this->writeData($path, $data, 'PATCH', $options);
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
    public function get($path, array $options = [])
    {
        try {
            $ch = $this->getCurlHandler($path, 'GET', $options);
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
    public function delete($path, array $options = [])
    {
        try {
            $ch = $this->getCurlHandler($path, 'DELETE', $options);
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
    private function getCurlHandler($path, $mode, $options = array())
    {
        $url = $this->getJsonPath($path, $options);
        $ch = $this->curlHandler;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->getSSLConnection());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $mode);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        return $ch;
    }

    private function writeData($path, $data, $method = 'PUT', $options = array())
    {
        $jsonData = json_encode($data);
        $header = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        );
        try {
            $ch = $this->getCurlHandler($path, $method, $options);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            $return = curl_exec($ch);
        } catch (Exception $e) {
            $return = null;
        }
        return $return;
    }
}
