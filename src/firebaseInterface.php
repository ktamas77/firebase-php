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
     * @param string $token Token
     * @return mixed
     */
    public function setToken(string $token): void;

    /**
     * @param string $baseURI Base URI
     * @return mixed
     */
    public function setBaseURI(string $baseURI): void;

    /**
     * @param int $seconds Seconds
     * @return mixed
     */
    public function setTimeOut(int $seconds): void;

    /**
     * @param string $path
     * @param mixed $data
     * @param array $options
     * @return mixed
     */
    public function set(string $path, array $data, array $options = []);

    /**
     * @param string $path
     * @param mixed $data
     * @param array $options
     * @return mixed
     */
    public function push(string $path, $data, array $options = []);

    /**
     * @param string $path
     * @param mixed $data
     * @param array $options
     * @return mixed
     */
    public function update(string $path, $data, array $options = []);

    /**
     * @param string $path
     * @param array $options
     * @return mixed
     */
    public function delete(string $path, array $options = []);

    /**
     * @param string $path
     * @param array $options
     * @return mixed
     */
    public function get(string $path, array $options = []);
}
