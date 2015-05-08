<?php
namespace Firebase;

/**
 * Interface FirebaseInterface
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
     * @return mixed
     */
    public function set($path, $data);

    /**
     * @param $path
     * @param $data
     * @return mixed
     */
    public function push($path, $data);

    /**
     * @param $path
     * @param $data
     * @return mixed
     */
    public function update($path, $data);

    /**
     * @param $path
     * @return mixed
     */
    public function get($path);

    /**
     * @param $path
     * @return mixed
     */
    public function delete($path);
}
