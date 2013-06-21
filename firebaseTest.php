<?php

require_once 'firebaseLib.php';

// --- set up your own database here
$url = 'https://kidsplace.firebaseio.com/';
$token = 'MqL0c8tKCtheLSYcygYNtGhU8Z2hULOFs9OKPdEp';

$fb = new fireBase($url, $token);

$todos1 = array(
  'name' => 'Pick the milk',
  'priority' => 1
);

$todos2 = array(
  'name' => 'Pick the beer',
  'priority' => 1
);

$todoPath = '/sample/todo';

printf("Database: %s\n", $url);

printf("Sending data to %s\n", $todoPath);
$response = $fb->set($todoPath, $todos1);
printf("Result: %s\n", $response);
sleep(2);

printf("Reading data from %s\n", $todoPath);
$response = $fb->get($todoPath);
printf("Result: %s\n", $response);
sleep(2);

printf("Updating data in %s\n", $todoPath);
$response = $fb->update($todoPath, $todos2);
printf("Result: %s\n", $response);
sleep(2);

printf("Pushing data from %s\n", $todoPath);
$response = $fb->push($todoPath, $todos1);
$response = $fb->push($todoPath, $todos1);
$response = $fb->push($todoPath, $todos1);
$response = $fb->push($todoPath, $todos1);
$response = $fb->push($todoPath, $todos1);
printf("Result: %s\n", $response);
sleep(2);

printf("Deleting data  %s\n", $todoPath);
$response = $fb->delete($todoPath);
printf("Result: %s\n", $response);
sleep(2);

printf("Reading data from %s\n", $todoPath);
$response = $fb->get($todoPath);
printf("Result: %s\n", $response);
