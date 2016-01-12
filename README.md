# Object

[![Build Status](https://travis-ci.org/molovo/object.svg)](https://travis-ci.org/molovo/object) [![Coverage Status](https://coveralls.io/repos/molovo/object/badge.svg?branch=master&service=github)](https://coveralls.io/github/molovo/object?branch=master)

A lightweight object wrapper, ideal for storing nested config etc.

#### Install

```sh
composer require molovo/object
```

#### Use

```php
<?php

use Molovo\Object\Object;

// Pass the array of values directly to the constructor
$object = new Object([
  'some' => [
    'awesome' => [
      'nested' => 'values'
    ]
  ]
]);

// Getting values
echo $object->some->awesome->nested; // returns 'values'

// Setting values
$object->some->awesome = 'code';

// Getting nested values
echo $object->valueForPath('some.awesome'); // returns 'code'

// Setting nested values
$object->setValueForPath('some.awesome.new.values.are', 'awesome');

// Get a pointer to a value (even if it doesn't exist)
$pointer = &$object->getPointer('some');
$pointer = &$pointer->getPointer('value');
$pointer = 'rules';

// Return the values as an array
$object->toArray();
// returns:
// [
//   'some' => [
//     'awesome' => [
//       'new' => [
//         'values' => [
//           'are' => 'awesome'
//         ]
//       ]
//     ],
//     'value' => 'rules'
//   ]
// ]
```
