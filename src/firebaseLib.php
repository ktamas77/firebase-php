<?php
namespace Firebase;

use \Exception;


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
class FirebaseLib implements FirebaseInterface
{
    private $_baseURI;
    private $_timeout;
    private $_token;
    private $_verifyPeer;

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

        $this->setBaseURI($baseURI);
        $this->setTimeOut(10);
        $this->setVerifyPeer(TRUE);
        $this->setToken($token);
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
     * @param string $path to data
     * @return string
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
     * @param integer $seconds Seconds to timeout
     *
     * @return void
     */
    public function setTimeOut($seconds)
    {
        $this->_timeout = $seconds;
    }

    /**
     * Sets SSL Verify Peer flag
     *
     * @param boolean $verify True to verify peer
     *
     * @return void
     */
    public function setVerifyPeer($verify)
    {
        $this->_verifyPeer = $verify;
    }

    /**
     * Writing data into Firebase with a PUT request
     * HTTP 200: Ok
     *
     * @param string $path Path
     * @param mixed  $data Data
     *
     * @return array Response
     */
    public function set($path, $data)
    {
      return $this->_webRequest($path, $data, 'PUT');
    }

    /**
     * Pushing data into Firebase with a POST request
     * HTTP 200: Ok
     *
     * @param string $path Path
     * @param mixed  $data Data
     *
     * @return array Response
     */
    public function push($path, $data)
    {
      return $this->_webRequest($path, $data, 'POST');
    }

    /**
     * Updating data into Firebase with a PATH request
     * HTTP 200: Ok
     *
     * @param string $path Path
     * @param mixed  $data Data
     *
     * @return array Response
     */
    public function update($path, $data)
    {
      return $this->_webRequest($path, $data, 'PATCH');
    }

    /**
     * Reading data from Firebase
     * HTTP 200: Ok
     *
     * @param string $path Path
     *
     * @return array Response
     */
    public function get($path)
    {
       return $this->_webRequest($path);
    }

    /**
     * Deletes data from Firebase
     * HTTP 204: Ok
     *
     * @param string $path Path
     *
     * @return array Response
     */
    public function delete($path)
    {
       return $this->_webRequest($path, false, 'DELETE');
    }

    /**
     * Returns with Initialized CURL Handler
     *
     * @param string $mode Mode
     *
     * @return resource Curl Handler
     */
    private function _getCurlHandler($path, $mode)
    {
        $url = $this->_getJsonPath($path);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->_timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->_timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->_verifyPeer);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $mode);
        return $ch;
    }

    /**
     * Generates and executes a web request, with a preference
     * for curl and falling back to file_get_contents when needed
     *
     * @param  string $path the target path
     * @param  array $data any data to send JSON encoded
     * @param  string $mode the request mode eg get, delete
     * @return array       the request response
     */
     private function _webRequest($path, $data = false, $mode = 'GET')
     {
     	$url = $this->_getJsonPath($path);
     	if (!extension_loaded('curl'))
     	{
     		$header = array();
     		if ($data && in_array($mode, array(
     			'POST',
     			'PUT',
     			'PATCH'
     		)))
     		{
     			$jsonData = json_encode($data);
     			$header[] = 'Content-type: application/json';
     			$header[] = 'Content-Length: ' . mb_strlen($jsonData);
     			$header[] = 'Connection: close';
     		} //$data && in_array( $mode, array( 'POST', 'PUT' ) )
     		$opts = array(
     			'http' => array(
     				'method' => $mode
     			)
     		);
     		if (count($header) > 0) $opts['http']['header'] = implode("\r\n", $header);
     		if (isset($jsonData)) $opts['http']['content'] = $jsonData;
     		$opts['http']['timeout'] = $this->_timeout;
     		$opts['ssl'] = array(
     			'verify_peer' => $this->_verifyPeer
     		);
     		$context = stream_context_create($opts);
     		try
     		{
          $return = file_get_contents($url, false, $context);
     		}

     		catch(Exception $e)
     		{
     			$return = null;
     		}
     	}
     	else
     	{
     		if ($data)
     		{
     			$return = $this->_writeData($path, $data, $mode);
     		}
     		else
     		{
     			try
     			{
     				$ch = $this->_getCurlHandler($path, $mode);
     				$return = curl_exec($ch);
     				curl_close($ch);
     			}

     			catch(Exception $e)
     			{
     				$return = null;
     			}
     		}
     	}

     	return $return;
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
            curl_close($ch);
        } catch (Exception $e) {
            $return = null;
        }
        return $return;
    }

}
