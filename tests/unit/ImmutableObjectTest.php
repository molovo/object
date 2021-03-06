<?php

namespace Molovo\Object\Tests;

use Molovo\Object\ImmutableObject;

class ImmutableObjectTest extends \Codeception\TestCase\Test
{
    /**
     * Test object is created correctly, and values can be fetched.
     *
     * @covers \Molovo\Object\Traits\ConstructsObjects::__construct
     * @covers \Molovo\Object\Traits\RetrievesValues::__get
     */
    public function testGettingValues()
    {
        $array = [
            'test' => 'test',
            'more' => 'more',
        ];

        $object = new ImmutableObject($array);

        verify($object->test)->equals('test');
        verify($object->more)->equals('more');
    }

    /**
     * Test object is created correctly, and values which are
     * non-associative arrays are not modified.
     *
     * @covers \Molovo\Object\Traits\ConstructsObjects::__construct
     * @covers \Molovo\Object\Traits\RetrievesValues::__get
     */
    public function testGettingValuesWithNumericArray()
    {
        $array = [
            'test_array'   => [1, 2, 3],
            'another_test' => ['one', 'two', 'three'],
        ];

        $object = new ImmutableObject($array);

        verify($object->test_array)->equals([1, 2, 3]);
        verify($object->another_test)->equals(['one', 'two', 'three']);
    }

    /**
     * Test object is created correctly, and nonexistent values return null.
     *
     * @covers \Molovo\Object\Traits\ConstructsObjects::__construct
     * @covers \Molovo\Object\Traits\RetrievesValues::__get
     */
    public function testGettingNonexistentValues()
    {
        $array = [
            'test' => 'test',
            'more' => 'more',
        ];

        $object = new ImmutableObject($array);

        verify($object->doesnotexist)->null();
    }

    /**
     * Test object is created correctly, and values cannot be overridden.
     *
     * @covers \Molovo\Object\Traits\ConstructsObjects::__construct
     * @covers \Molovo\Object\ImmutableObject::__set
     *
     * @uses \Molovo\Object\Traits\RetrievesValues::__get
     *
     * @expectedException \Molovo\Object\Exception\ImmutabilityViolationException
     */
    public function testSettingValues()
    {
        $array = [
            'test' => 'test',
            'more' => 'more',
        ];

        $object = new ImmutableObject($array);

        $object->test = 'changed';
        verify($object->test)->equals('changed');
    }

    /**
     * Test object is created correctly, and nonexistent values cannot be set.
     *
     * @covers \Molovo\Object\Traits\ConstructsObjects::__construct
     * @covers \Molovo\Object\ImmutableObject::__set
     *
     * @uses \Molovo\Object\Traits\RetrievesValues::__get
     *
     * @expectedException \Molovo\Object\Exception\ImmutabilityViolationException
     */
    public function testSettingNonexistentValues()
    {
        $array = [
            'test' => 'test',
            'more' => 'more',
        ];

        $object = new ImmutableObject($array);

        $object->doesnotexist = 'changed';
        verify($object->doesnotexist)->equals('changed');
    }

    /**
     * Verify that the toArray function returns the values correctly.
     *
     * @covers \Molovo\Object\Traits\RetrievesValues::toArray
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

        $object = new ImmutableObject($array);

        verify($object->toArray())->equals($array);
    }

    /**
     * Test object is created correctly with nested objects.
     *
     * @covers \Molovo\Object\Traits\ConstructsObjects::__construct
     * @covers \Molovo\Object\Traits\RetrievesValues::__get
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

        $object = new ImmutableObject($array);

        verify($object->test)->equals('test');
        verify($object->testing)->isInstanceOf(ImmutableObject::class);
        verify($object->testing->nested)->isInstanceOf(ImmutableObject::class);
        verify($object->testing->nested->data)->true();
    }

    /**
     * Test object is created correctly, and nested values cannot be overridden.
     *
     * @covers \Molovo\Object\Traits\ConstructsObjects::__construct
     * @covers \Molovo\Object\ImmutableObject::__set
     *
     * @uses \Molovo\Object\Traits\RetrievesValues::__get
     *
     * @expectedException \Molovo\Object\Exception\ImmutabilityViolationException
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

        $object = new ImmutableObject($array);

        $object->testing->nested->data = 'changed';
        verify($object->testing->nested->data)->equals('changed');
    }

    /**
     * Test can iterate over object values.
     *
     * @covers \Molovo\Object\Traits\IteratesValues::getIterator
     */
    public function testObjectIteration()
    {
        $array = [
            'test' => 'test',
            'more' => 'more',
        ];

        $object = new ImmutableObject($array);

        foreach ($object as $key => $value) {
            verify(isset($array[$key]))->true();
            verify($value)->equals($array[$key]);
        }
    }

    /**
     * Test can retrieve pointer to a value.
     *
     * @covers \Molovo\Object\Traits\RetrievesValues::getPointer
     */
    public function testGetPointer()
    {
        $array = [
            'test' => 'test',
        ];

        $object = new ImmutableObject($array);

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
     * @covers \Molovo\Object\Traits\RetrievesValues::valueForPath
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

        $object = new ImmutableObject($array);

        verify($object->valueForPath('testing.nested.data'))->true();
    }

    /**
     * Tests that nested paths can be accessed directly.
     *
     * @covers \Molovo\Object\Traits\RetrievesValues::valueForPath
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

        $object = new ImmutableObject($array);

        // Test that the last item is not found
        verify($object->valueForPath('testing.nested.doesnotexist'))->null();

        // Test that the first item is not found
        verify($object->valueForPath('nonexistent.nested.path'))->null();

        // Test that items in the middle are not found
        verify($object->valueForPath('testing.nonexistent.paths'))->null();
    }

    /**
     * Tests that nested paths cannot be set directly.
     *
     * @covers \Molovo\Object\ImmutableObject::setValueForPath
     *
     * @uses \Molovo\Object\Traits\RetrievesValues::valueForPath
     *
     * @expectedException \Molovo\Object\Exception\ImmutabilityViolationException
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

        $object = new ImmutableObject($array);

        $object->setValueForPath('testing.nested.data', 'changed');
        verify($object->valueForPath('testing.nested.data'))->equals('changed');
    }

    /**
     * Tests that non-existent nested paths cannot be set directly.
     *
     * @covers \Molovo\Object\ImmutableObject::setValueForPath
     *
     * @uses \Molovo\Object\Traits\RetrievesValues::valueForPath
     *
     * @expectedException \Molovo\Object\Exception\ImmutabilityViolationException
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

        $object = new ImmutableObject($array);

        $object->setValueForPath('testing.nested.doesnotexist', 'changed');
        verify($object->valueForPath('testing.nested.doesnotexist'))->equals('changed');

        $object->setValueForPath('nonexistent.nested.path', 'changed');
        verify($object->nonexistent)->isInstanceOf(ImmutableObject::class);
        verify($object->nonexistent->nested)->isInstanceOf(ImmutableObject::class);
        verify($object->nonexistent->nested->path)->equals('changed');
        verify($object->valueForPath('nonexistent.nested.path'))->equals('changed');
    }
}
