<?php

namespace Molovo\Object\Tests;

use Molovo\Object\Object;

class ObjectTest extends \Codeception\TestCase\Test
{
    /**
     * Test object is created correctly, and values can be fetched.
     *
     * @covers Molovo\Object\Object::__construct
     * @covers Molovo\Object\Object::__get
     */
    public function testGettingValues()
    {
        $array = [
            'test' => 'test',
            'more' => 'more',
        ];

        $object = new Object($array);

        verify($object->test)->equals('test');
        verify($object->more)->equals('more');
    }

    /**
     * Test object is created correctly, and nonexistent values return null.
     *
     * @covers Molovo\Object\Object::__construct
     * @covers Molovo\Object\Object::__get
     */
    public function testGettingNonexistentValues()
    {
        $array = [
            'test' => 'test',
            'more' => 'more',
        ];

        $object = new Object($array);

        verify($object->doesnotexist)->null();
    }

    /**
     * Test object is created correctly, and values can be set.
     *
     * @covers Molovo\Object\Object::__construct
     * @covers Molovo\Object\Object::__set
     *
     * @uses Molovo\Object\Object::__get
     */
    public function testSettingValues()
    {
        $array = [
            'test' => 'test',
            'more' => 'more',
        ];

        $object = new Object($array);

        $object->test = 'changed';
        verify($object->test)->equals('changed');

        $object->more = 'also_changed';
        verify($object->more)->equals('also_changed');
    }

    /**
     * Test object is created correctly, and nonexistent values can be set.
     *
     * @covers Molovo\Object\Object::__construct
     * @covers Molovo\Object\Object::__set
     *
     * @uses Molovo\Object\Object::__get
     */
    public function testSettingNonexistentValues()
    {
        $array = [
            'test' => 'test',
            'more' => 'more',
        ];

        $object = new Object($array);

        $object->doesnotexist = 'changed';
        verify($object->doesnotexist)->equals('changed');
    }

    /**
     * Verify that the toArray function returns the values correctly.
     *
     * @covers Molovo\Object\Object::toArray
     */
    public function testToArray()
    {
        $array = [
            'test'    => 'test',
            'testing' => [
                'nested' => [
                    'data' => true,
                ],
            ],
        ];

        $object = new Object($array);

        verify($object->toArray())->equals($array);
    }

    /**
     * Test object is created correctly with nested objects.
     *
     * @covers Molovo\Object\Object::__construct
     * @covers Molovo\Object\Object::__get
     */
    public function testNestedObjects()
    {
        $array = [
            'test'    => 'test',
            'testing' => [
                'nested' => [
                    'data' => true,
                ],
            ],
        ];

        $object = new Object($array);

        verify($object->test)->equals('test');
        verify($object->testing)->isInstanceOf(Object::class);
        verify($object->testing->nested)->isInstanceOf(Object::class);
        verify($object->testing->nested->data)->true();
    }

    /**
     * Test object is created correctly, and values can be set.
     *
     * @covers Molovo\Object\Object::__construct
     * @covers Molovo\Object\Object::__set
     *
     * @uses Molovo\Object\Object::__get
     */
    public function testSettingWithinNestedObjects()
    {
        $array = [
            'testing' => [
                'nested' => [
                    'data' => true,
                ],
            ],
        ];

        $object = new Object($array);

        $object->testing->nested->data = 'changed';
        verify($object->testing->nested->data)->equals('changed');
    }

    /**
     * Test can iterate over object values.
     *
     * @covers Molovo\Object\Object::getIterator
     */
    public function testObjectIteration()
    {
        $array = [
            'test' => 'test',
            'more' => 'more',
        ];

        $object = new Object($array);

        foreach ($object as $key => $value) {
            verify(isset($array[$key]))->true();
            verify($value)->equals($array[$key]);
        }
    }

    public function testGetPointer()
    {
        $array = [
            'test' => 'test',
        ];

        $object = new Object($array);

        // Verify that the correct value is returned
        verify($object->getPointer('test'))->equals('test');

        // Verify that nonexistent values return null
        verify(($pointer = &$object->getPointer('nonexistent')))->null();

        // Verify that changes are reflected on the object
        $pointer = 'test';
        verify($object->nonexistent)->equals('test');
    }

    /**
     * Tests that nested paths can be accessed directly.
     *
     * @covers Molovo\Object\Object::valueForPath
     */
    public function testValueForPath()
    {
        $array = [
            'testing' => [
                'nested' => [
                    'data' => true,
                ],
            ],
        ];

        $object = new Object($array);

        verify($object->valueForPath('testing.nested.data'))->true();
    }

    /**
     * Tests that nested paths can be accessed directly.
     *
     * @covers Molovo\Object\Object::valueForPath
     */
    public function testValueForNonexistentPath()
    {
        $array = [
            'testing' => [
                'nested' => [
                    'data' => true,
                ],
            ],
        ];

        $object = new Object($array);

        // Test that the last item is not found
        verify($object->valueForPath('testing.nested.doesnotexist'))->null();

        // Test that the first item is not found
        verify($object->valueForPath('nonexistent.nested.path'))->null();

        // Test that items in the middle are not found
        verify($object->valueForPath('testing.nonexistent.paths'))->null();
    }

    /**
     * Tests that nested paths can be set directly.
     *
     * @covers Molovo\Object\Object::setValueForPath
     *
     * @uses Molovo\Object\Object::valueForPath
     */
    public function testSetValueForPath()
    {
        $array = [
            'testing' => [
                'nested' => [
                    'data' => true,
                ],
            ],
        ];

        $object = new Object($array);

        $object->setValueForPath('testing.nested.data', 'changed');
        verify($object->valueForPath('testing.nested.data'))->equals('changed');
    }

    /**
     * Tests that nested paths can be set directly.
     *
     * @covers Molovo\Object\Object::setValueForPath
     *
     * @uses Molovo\Object\Object::valueForPath
     */
    public function testSetValueForNonexistentPath()
    {
        $array = [
            'testing' => [
                'nested' => [
                    'data' => true,
                ],
            ],
        ];

        $object = new Object($array);

        $object->setValueForPath('testing.nested.doesnotexist', 'changed');
        verify($object->valueForPath('testing.nested.doesnotexist'))->equals('changed');

        $object->setValueForPath('nonexistent.nested.path', 'changed');
        verify($object->nonexistent)->isInstanceOf(Object::class);
        verify($object->nonexistent->nested)->isInstanceOf(Object::class);
        verify($object->nonexistent->nested->path)->equals('changed');
        verify($object->valueForPath('nonexistent.nested.path'))->equals('changed');
    }
}
