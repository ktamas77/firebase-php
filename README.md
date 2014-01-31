firebase-php
============

[![Build Status](https://drone.io/github.com/ktamas77/firebase-php/status.png)](https://drone.io/github.com/ktamas77/firebase-php/latest)

[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/ktamas77/firebase-php/badges/quality-score.png?s=239ffca76628b5a86540b9def187e2f8a199cb10)](https://scrutinizer-ci.com/g/ktamas77/firebase-php/)

# Firebase PHP Client

Based on Firebase REST API: https://www.firebase.com/docs/rest-api.html

Firebase PHP Stub
=================
A Firebase PHP Stub has been created to allow for integration with phpunit without actually interacting with FirebaseIO.

To use the firebaseLib and firebaseStub in your application and testing, you must pass in a firebase object to your application.

For example, if your current code is:

```
public function setFirebaseValue($path, $value) {
  $firebase = new Firebase('https://radiant-fire-2427.firebaseio.com', 'czvEX8vMU8FZn4wYCvf466P3J6zH5ZlKQeuwxmEZ');
  $firebase->set($path, $value);
}
```

You will change it to be:

```
public function setFirebaseValue($path, $value, $firebase) {
  $firebase->set($path, $value);
}
```

Then your phpunit tests will look like:

```
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

Unit Tests
==========

All the unit tests are found in the "/tests" directory. Due to the usage of an interface, the tests must run in isolation.

The firebaseLib tests have inherent latency due to actual cURL calls to a live firebaseIO account. The firebaseLib tests can be executed by running the following command:

```
$ phpunit tests/firebaseTest.php
```

The FirebaseStub tests can be executed by running the following command:

```
$ phpunit tests/firebaseStubTest.php
```
