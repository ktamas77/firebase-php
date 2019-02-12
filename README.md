# Firebase PHP Client

[![Current version](https://img.shields.io/packagist/v/ktamas77/firebase-php.svg)](https://packagist.org/packages/ktamas77/firebase-php)
[![Supported PHP version](https://img.shields.io/packagist/php-v/ktamas77/firebase-php.svg)]()
[![Total Downloads](https://img.shields.io/packagist/dt/kreait/firebase-php.svg)](https://packagist.org/packages/ktamas77/firebase-php/stats)

[![Build Status](https://cloud.drone.io/api/badges/ktamas77/firebase-php/status.svg)](https://cloud.drone.io/ktamas77/firebase-php)
[![Build Status](https://semaphoreci.com/api/v1/ktamas77/firebase-php/branches/master/badge.svg)](https://semaphoreci.com/ktamas77/firebase-php)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/ktamas77/firebase-php/badges/quality-score.png?s=239ffca76628b5a86540b9def187e2f8a199cb10)](https://scrutinizer-ci.com/g/ktamas77/firebase-php/)

Based on the [Firebase REST API](https://www.firebase.com/docs/rest-api.html).

Available on [Packagist](https://packagist.org/packages/ktamas77/firebase-php).

### Adding Firebase PHP to your project using Composer

```bash
cd <your_project>
composer require ktamas77/firebase-php
```

More info about Composer at [getcomposer.org](http://getcomposer.org).

### Example
```php
const DEFAULT_URL = 'https://kidsplace.firebaseio.com/';
const DEFAULT_TOKEN = 'MqL0c8tKCtheLSYcygYNtGhU8Z2hULOFs9OKPdEp'; // You will find this in the firebase console. Settings -> Project Settings -> Service accounts -> Database secrets
const DEFAULT_PATH = '/firebase/example';

$firebase = new \Firebase\FirebaseLib(DEFAULT_URL, DEFAULT_TOKEN);

// --- storing an array ---
$test = [
    'foo' => 'bar',
    'i_love' => 'lamp',
    'id' => 42
];
$dateTime = new DateTime();
$firebase->set(DEFAULT_PATH . '/' . $dateTime->format('c'), $test);

// --- storing a string ---
$firebase->set(DEFAULT_PATH . '/name/contact001', 'John Doe');

// --- reading the stored string ---
$name = $firebase->get(DEFAULT_PATH . '/name/contact001');
```

### Supported Commands
```php
// -- Firebase API commands

$firebase->set($path, $value);   // stores data in Firebase
$value = $firebase->get($path);  // reads a value from Firebase
$firebase->delete($path);        // deletes value from Firebase
$firebase->update($path, $data); // updates data in Firebase
$firebase->push($path, $data);   // push data to Firebase

// -- Query Parameters can be optionally used on all operations, example:

$value = $firebase->get($path, array('shallow' => 'true'));

// -- Query Parameter values with quotes, example (https://firebase.google.com/docs/database/rest/retrieve-data#filtering-by-a-specified-child-key):

$value = $firebase->get($path, array('orderBy' => '"height"'));

// -- Firebase PHP Library commands

$firebase->setToken($token);     // set up Firebase token
$firebase->setBaseURI($uri);     // set up Firebase base URI (root node)
$firebase->setTimeOut($seconds); // set up maximum timeout / request
```

Please refer to the [Firebase REST API documentation](https://www.firebase.com/docs/rest/api/) for further details.

### Firebase PHP Stub
A Firebase PHP Stub has been created to allow for integration with phpunit without actually interacting with FirebaseIO.

To use the firebaseLib and firebaseStub in your application and testing, you must pass in a firebase object to your application.

For example, if your current code is:

```php
public function setFirebaseValue($path, $value) { 
  $url = 'https://radiant-fire-2427.firebaseio.com';
  $token = 'czvEX8vMU8FZn4wYCvf466P3J6zH5ZlKQeuwxmEZ';
  $firebase = new Firebase($url, $token);
  $firebase->set($path, $value);
}
```

You will change it to be:

```php
public function setFirebaseValue($path, $value, $firebase) {
  $firebase->set($path, $value);
}
```

Then your phpunit tests will look like:

```php
<?php
  require_once '<path>/lib/firebaseInterface.php';
  require_once '<path>/lib/firebaseStub.php';

  class MyClass extends PHPUnit_Framework_TestCase
  {
    public function testSetFirebaseValue() {
      $myClass = new MyClass();
      $firebaseStub = new FirebaseStub($uri, $token);
      $myClass->setFirebaseValue($path, $value, $firebaseStub);
    }
  }
?>
```

### Composer upgrade

Coding standards check / fixing & tests are integrated with composer.
To start, upgrade the required packages:

```bash
$ composer update
```

### Unit Tests
All the unit tests are found in the "/tests" directory. Due to the usage of an interface, the tests must run in isolation.

The firebaseLib tests have inherent latency due to actual cURL calls to a live firebaseIO account. The firebaseLib tests can be executed by running the following command:

```bash
$ composer test
```

### Coding Standards Validation
The codebase is in compliance with PSR-2.

To test coding standards:
```bash
$ composer style
```

To automatically fix standards (whenever it's possible):
```bash
$ composer style-fix
```


#### The MIT License (MIT)
```
Copyright (c) 2012-2018 Tamas Kalman

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
```
