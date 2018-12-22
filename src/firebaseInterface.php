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
    public function set($path, $data, array $options = []);

    /**
     * @param $path
     * @param $data
     * @param $options
     * @return mixed
     */
    public function push($path, $data, array $options = []);

    /**
     * @param $path
     * @param $data
     * @param $options
     * @return mixed
     */
    public function update($path, $data, array $options = []);

    /**
     * @param $path
     * @param $options
     * @return mixed
     */
    public function get($path, array $options = []);

    /**
     * @param $path
     * @param $options
     * @return mixed
     */
    public function delete($path, array $options = []);
}
