<?php

namespace Firebase;

/**
 * Class FirebaseStub
 *
 * Stubs the Firebase interface without issuing any cURL requests.
 *
 * @package Firebase
 */
class FirebaseStub extends FirebaseLib implements FirebaseInterface
{
    protected $response;

    /**
     * @param string $path
     * @param mixed $data
     * @param array $options
     * @return mixed
     */
    public function set(string $path, $data, array $options = [])
    {
        return $this->getSetResponse();
    }

    /**
     * @param string $path
     * @param mixed $data
     * @param array $options
     * @return mixed
     */
    public function push(string $path, $data, array $options = [])
    {
        return $this->set($path, $data);
    }

    /**
     * @param string $path
     * @param mixed $data
     * @param array $options
     * @return mixed
     */
    public function update(string $path, $data, array $options = [])
    {
        return $this->set($path, $data);
    }

    /**
     * @param string $path
     * @param array $options
     * @return mixed
     */
    public function get(string $path, array $options = [])
    {
        return $this->getGetResponse();
    }

    /**
     * @param string $path
     * @param array $options
     * @return null
     */
    public function delete(string $path, array $options = [])
    {
        return $this->getDeleteResponse();
    }

    /**
     * @param $expectedResponse
     */
    public function setResponse($expectedResponse): void
    {
        $this->response = $expectedResponse;
    }

    /**
     * @return mixed
     */
    private function getSetResponse()
    {
        return $this->response;
    }

    /**
     * @return mixed
     */
    private function getGetResponse()
    {
        return $this->response;
    }

    /**
     * @return mixed
     */
    private function getDeleteResponse()
    {
        return $this->response;
    }
}
