<?php
/**
 * Interface for PHP Firebase functions.
 **/

interface FirebaseInterface
{
  public function setToken($token);
  public function setBaseURI($baseURI);
  public function setTimeOut($seconds);
  public function set($path, $data);
  public function push($path, $data);
  public function update($path, $data);
  public function get($path);
  public function delete($path);
}
