<?php

require_once 'firebaseLib.php';

$fb = new fireBase('http://yourcompany.firebase.com/youruser');

$todos = array(
    'name' => 'Pick the milk',
    'priority' => 1
);

$response = $fb->push('todos', $todos);