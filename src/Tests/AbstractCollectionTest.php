<?php

namespace GingerPluginSdk\Tests;

use GingerPluginSdk\Collections\AbstractCollection;
use PHPUnit\Framework\TestCase;

class AbstractCollectionTest extends TestCase
{
    public function test_init()
    {
        $abstract_collection = new AbstractCollection(class_string: '', propertyName: 'sore');
        self::assertSame(
            expected: 0,
            actual: $abstract_collection->count()
        );
    }

    public function test_add_item_get_value()
    {
        $abstract_collection = new AbstractCollection(class_string: '', propertyName: 'sore');
        $abstract_collection->add('depression');
        self::assertSame(
            expected: 'depression',
            actual: $abstract_collection->get()
        );
    }

    public function test_add_item_get_pointer()
    {
        $abstract_collection = new AbstractCollection(class_string: '', propertyName: 'sore');
        $abstract_collection->add('depression');
        self::assertSame(
            expected: 1,
            actual: $abstract_collection->getCurrentPointer()
        );
    }

    public function test_remove_item_get_count()
    {
        $abstract_collection = new AbstractCollection(class_string: '', propertyName: 'sore');
        $abstract_collection->add('depression');
        $abstract_collection->add('obsession');
        $abstract_collection->add('all_will_be_fine');
        $abstract_collection->remove($abstract_collection->getCurrentPointer() - 1);
        self::assertSame(
            expected: 2,
            actual: $abstract_collection->count()
        );
    }

    public function test_remove_item_get_using_pointer()
    {
        $abstract_collection = new AbstractCollection(class_string: '', propertyName: 'sore');
        $abstract_collection->add('depression');
        $abstract_collection->add('obsession');
        $abstract_collection->add('all_will_be_fine');
        $expected = $abstract_collection->get(2);
        $abstract_collection->remove(1);
        self::assertSame(
            expected: $expected,
            actual: $abstract_collection->first()
        );
    }

}