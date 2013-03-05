<?php

require_once 'firebaseLib.php';

// --- set up your own database here
$url = 'https://yourdatabase.firebaseio.com/';

$fb = new fireBase($url);

$todos = array(
    'name' => 'Pick the milk',
    'priority' => 1
);

$todoPath = '/sample/todo';

printf("Database: %s\n", $url);

printf("Sending data to %s\n", $todoPath);
$response = $fb->set($todoPath, $todos);
printf("Result: %s\n", $response);

printf("Reading data from %s\n", $todoPath);
$response = $fb->get($todoPath);
printf("Result: %s\n", $response);

printf("Deleting data  %s\n", $todoPath);
$response = $fb->delete($todoPath);
printf("Result: %s\n", $response);

printf("Reading data from %s\n", $todoPath);
$response = $fb->get($todoPath);
printf("Result: %s\n", $response);
