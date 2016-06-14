<?php
namespace Firebase;

/**
 * Interface FirebaseInterface
 *
 * @package Firebase
 */
interface FirebaseInterface
{
    /**
     * @param $token
     * @return mixed
     */
    public function setToken($token);

    /**
     * @param $baseURI
     * @return mixed
     */
    public function setBaseURI($baseURI);

    /**
     * @param $seconds
     * @return mixed
     */
    public function setTimeOut($seconds);

    /**
     * @param $path
     * @param $data
     * @param $options
     * @return mixed
     */
    public function set($path, $data, $options = array());

    /**
     * @param $path
     * @param $data
     * @param $options
     * @return mixed
     */
    public function push($path, $data, $options = array());

    /**
     * @param $path
     * @param $data
     * @param $options
     * @return mixed
     */
    public function update($path, $data, $options = array());

    /**
     * @param $path
     * @param $options
     * @return mixed
     */
    public function get($path, $options = array());

    /**
     * @param $path
     * @param $options
     * @return mixed
     */
    public function delete($path, $options = array());
}
