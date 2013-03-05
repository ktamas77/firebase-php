<?php

require_once 'firebaseLib.php';

// --- set up your own database here
$url = 'https://yourdatabasename.firebaseio.com/';

$fb = new fireBase($url);

$todos = array(
    'name' => 'Pick the milk',
    'priority' => 1
);

$todoPath = '/sample/todo';

printf("Sending data to %s\n", $url);
$response = $fb->set($todoPath, $todos);
printf("Result: %s\n", $response);

printf("Reading data from %s\n", $todoPath);
$response = $fb->get($todoPath);
printf("Result: %s\n", $response);