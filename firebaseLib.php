<?php

/**
 * Firebase PHP Client Library
 *
 * @author Tamas Kalman <ktamas77@gmail.com>
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
class Firebase
{

    private $_baseURI;
    private $_timeout;

    /**
     * Constructor
     *
     * @param String $baseURI Base URI
     *
     * @return void
     */
    function __construct($baseURI = '')
    {
        if (!extension_loaded('curl')) {
            trigger_error('Extension CURL is not loaded.', E_USER_ERROR);
        }

        $this->setBaseURI($baseURI);
        $this->setTimeOut(10);
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
        $this->_baseURI = $baseURI;
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
     * 
     * @param String $path Path
     * @param Mixed  $data Data
     *
     * @return Array Response
     */
    public function set($path, $data)
    {
        $jsonData = json_encode($data);
    }

    /**
     * Reading data from Firebase
     *
     * @param String $path Path
     *
     * @return Array Response
     */
    public function get($path)
    {

    }

    /**
     * Pushing data to Firebase
     *
     * @param String $path Path
     * @param Mixed  $data Data
     *
     * @return Array Response
     */
    public function push($path, $data)
    {
        $jsonData = json_encode($data);
    }

    /**
     * Deletes data from Firebase
     *
     * @param type $path Path
     *
     * @return Array Response
     */
    public function delete($path)
    {
        
    }

    /**
     * Call Firebase with the constructed URI
     *
     * @param String $param Parameters to add for the call
     *
     * @return Array Response
     */
    private function _fireCall($param)
    {
        $url = $this->_baseURI . $param;
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->_timeout);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->_timeout);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $return = curl_exec($ch);
            curl_close($ch);
        } catch (Exception $e) {
            $return = null;
        }
    }

}