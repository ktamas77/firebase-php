<?php

namespace Firebase;

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
    protected $baseURI;
    protected $timeout;
    protected $token;
    protected $curlHandler;
    protected $sslConnection;

    /**
     * Constructor
     *
     * @param string $baseURI
     * @param string $token
     */
    public function __construct(string $baseURI = '', string $token = '')
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
    public function initCurlHandler(): void
    {
        $this->curlHandler = curl_init();
    }

    /**
     * Closing the CURL handler
     *
     * @return void
     */
    public function closeCurlHandler(): void
    {
        curl_close($this->curlHandler);
    }

    /**
     * Enabling/Disabling SSL Connection
     *
     * @param bool $enableSSLConnection
     */
    public function setSSLConnection(bool $enableSSLConnection): void
    {
        $this->sslConnection = $enableSSLConnection;
    }

    /**
     * Returns status of SSL Connection
     *
     * @return boolean
     */
    public function getSSLConnection(): bool
    {
        return $this->sslConnection;
    }

    /**
     * Sets Token
     *
     * @param string $token Token
     * @return void
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * Get Token
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Sets Base URI, ex: http://yourcompany.firebase.com/youruser
     *
     * @param string $baseURI Base URI
     * @return void
     */
    public function setBaseURI(string $baseURI): void
    {
        $baseURI .= (substr($baseURI, -1) === '/' ? '' : '/');
        $this->baseURI = $baseURI;
    }

    /**
     * Gets Base URI
     *
     * @return string
     */
    public function getBaseURI(): string
    {
        return $this->baseURI;
    }

    /**
     * Returns with the normalized JSON absolute path
     *
     * @param string $path Path
     * @param array $options Options
     * @return string
     */
    private function getJsonPath(string $path, $options = []): string
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
     * @param int $seconds Seconds to timeout
     * @return void
     */
    public function setTimeOut(int $seconds): void
    {
        $this->timeout = $seconds;
    }

    /**
     * Gets timeout in seconds
     *
     * @return int
     */
    public function getTimeOut(): int
    {
        return $this->timeout;
    }

    /**
     * Writing data into Firebase with a PUT request
     * HTTP 200: Ok
     *
     * @param string $path Path
     * @param mixed $data Data
     * @param array $options Options
     * @return mixed
     */
    public function set(string $path, $data, array $options = [])
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
     * @return mixed
     */
    public function push(string $path, $data, array $options = [])
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
     * @return mixed
     */
    public function update(string $path, $data, array $options = [])
    {
        return $this->writeData($path, $data, 'PATCH', $options);
    }

    /**
     * Reading data from Firebase
     * HTTP 200: Ok
     *
     * @param string $path Path
     * @param array $options Options
     * @return mixed
     */
    public function get(string $path, array $options = [])
    {
        $ch = $this->getCurlHandler($path, 'GET', $options);
        return curl_exec($ch);
    }

    /**
     * Deletes data from Firebase
     * HTTP 204: Ok
     *
     * @param string $path Path
     * @param array $options Options
     * @return mixed
     */
    public function delete(string $path, array $options = [])
    {
        $ch = $this->getCurlHandler($path, 'DELETE', $options);
        return curl_exec($ch);
    }

    /**
     * Returns with Initialized CURL Handler
     *
     * @param string $path Path
     * @param string $mode Mode
     * @param array $options Options
     * @return mixed
     */
    private function getCurlHandler(string $path, string $mode, array $options = [])
    {
        $url = $this->getJsonPath($path, $options);
        $ch = $this->curlHandler;
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $mode);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->getSSLConnection());
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_URL, $url);
        return $ch;
    }

    /**
     * Writes Data to Firebase API
     *
     * @param string $path
     * @param $data
     * @param string $method
     * @param array $options
     * @return mixed
     */
    private function writeData(string $path, $data, string $method = 'PUT', array $options = [])
    {
        $jsonData = json_encode($data);
        $header = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ];
        $ch = $this->getCurlHandler($path, $method, $options);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        return curl_exec($ch);
    }
}
