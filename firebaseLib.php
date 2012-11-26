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
class Firebase {

    private $_baseURI;

    /**
     * Constructor
     *
     * @param String $baseURI Base URI
     *
     * @return void
     */
    function __construct($baseURI = '')
    {
        $this->setBaseURI($baseURI);
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
     * Writing data into Firebase with a PUT request
     * 
     * @param String $path Path
     * @param Mixed  $data Data
     *
     * @return Array $response
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
     * @return Array $response
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
     * @return Array $response
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
     * @return Array $response
     */
    public function delete($path)
    {
        
    }

}