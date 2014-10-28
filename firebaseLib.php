<?php

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'firebaseInterface.php';

/**
 * Firebase PHP Client Library
 *
 * @author Tamas Kalman <ktamas77@gmail.com>
 * @url    https://github.com/ktamas77/firebase-php/
 * @link   https://www.firebase.com/docs/rest-api.html
 *
 */

/**
 * Firebase PHP Class
 *
 * @author Tamas Kalman <ktamas77@gmail.com>
 * @link   https://www.firebase.com/docs/rest-api.html
 *
 */
class Firebase implements FirebaseInterface
{
    private $_baseURI;
    private $_timeout;
    private $_token;
    private $_ch;

    /**
     * Constructor
     *
     * @param String $baseURI Base URI
     *
     * @return void
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
    }

    /**
     * Sets Token
     *
     * @param String $token Token
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
     * @param String $baseURI Base URI
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
     * @param String $path to data
     */
    private function _getJsonPath($path)
    {
        $url = $this->_baseURI;
        $path = ltrim($path, '/');
        $auth = ($this->_token == '') ? '' : '?auth=' . $this->_token;
        return $url . $path . '.json' . $auth;
    }

    /**
     * Sets REST call timeout in seconds
     *
     * @param Integer $seconds Seconds to timeout
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
     * @param String $path Path
     * @param Mixed  $data Data
     *
     * @return Array Response
     */
    public function set($path, $data)
    {
      return $this->_writeData($path, $data, 'PUT');
    }
    /**
     * Creating multiple curl process with shell_exec, you'll need shell_exec and must run PHP on linux 
     * 
     *
     * @param String $path Path
     * @param Mixed  $data Data
     *
     * @return Nothing be careful
     */
    public function set_fast($path,$data)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return $this->_writeData($path, $data, 'PUT');
        }
        $this->_execPut($path,$data);
    }
    /**
     * Writing multiple datas into multiple Firebase paths with a PUT request via socks
     * $paths[$key] must be pair to $datas[$key]
     *
     * @param Array $paths List of Paths
     * @param Array $data  List of Values
     *
     * @return Boolean
     */
    public function set_multi($paths,$datas)
    {
        if(count($paths) != count($datas))
            return 0;

        foreach ($paths as $key => $path) {
            $this->_socksPut($path,$datas[$key]);
        }
        return 1;
    }
    /**
     * Pushing data into Firebase with a POST request
     * HTTP 200: Ok
     *
     * @param String $path Path
     * @param Mixed  $data Data
     *
     * @return Array Response
     */
    public function push($path, $data)
    {
      return $this->_writeData($path, $data, 'POST');
    }

    /**
     * Updating data into Firebase with a PATH request
     * HTTP 200: Ok
     *
     * @param String $path Path
     * @param Mixed  $data Data
     *
     * @return Array Response
     */
    public function update($path, $data)
    {
      return $this->_writeData($path, $data, 'PATCH');
    }

    /**
     * Reading data from Firebase
     * HTTP 200: Ok
     *
     * @param String $path Path
     *
     * @return Array Response
     */
    public function get($path)
    {
        try {
            $ch = $this->_getCurlHandler($path, 'GET');
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
     * @param type $path Path
     *
     * @return Array Response
     */
    public function delete($path)
    {
        try {
            $ch = $this->_getCurlHandler($path, 'DELETE');
            $return = curl_exec($ch);
        } catch (Exception $e) {
            $return = null;
        }
        return $return;
    }

    /**
     * Returns with Initialized CURL Handler
     *
     * @param String $mode Mode
     *
     * @return CURL Curl Handler
     */
    private function _getCurlHandler($path, $mode)
    {
        $url = $this->_getJsonPath($path);
        if (!$this->_ch) {
            $this->_ch = curl_init();
        }
        curl_setopt($this->_ch, CURLOPT_URL, $url);
        curl_setopt($this->_ch, CURLOPT_TIMEOUT, $this->_timeout);
        curl_setopt($this->_ch, CURLOPT_CONNECTTIMEOUT, $this->_timeout);
        curl_setopt($this->_ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->_ch, CURLOPT_CUSTOMREQUEST, $mode);
       
        return $this->_ch;
    }

    private function _writeData($path, $data, $method = 'PUT')
    {
        $jsonData = json_encode($data);
        $header = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        );
        try {
            $ch = $this->_getCurlHandler($path, $method);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            $return = curl_exec($ch);
        } catch (Exception $e) {
            $return = null;
        }
        return $return;
    }
    private function _execPut($path,$data)
    {
        $path = $this->_getJsonPath($path);

        $formatted_data = addslashes(json_encode($data));
        shell_exec("curl -X PUT -d $formatted_data  \"$path\" > /dev/null 2>/dev/null &");
    }
    private function _socksPut($path,$data)
    {
        $jsonData = json_encode($data);
        $path = $this->_getJsonPath($path);
        $parts=parse_url($path);

        $sslUrl = "ssl://".$parts['host'];
        $fp = fsockopen($sslUrl,443,$errNo, $errStr, 30);

        $out = "PUT ".$parts['path']."?auth=".$this->_token." HTTP/1.1\r\n";
        $out.= "Host: ".$parts['host']."\r\n";
        $out.= "Content-Type: application/json\r\n";
        $out.= "Content-Length: ".strlen($jsonData)."\r\n";
        $out.= "Connection: Close\r\n\r\n";
        if (isset($jsonData)) $out.= $jsonData;

        fwrite($fp, $out);
        fclose($fp);
    }

}