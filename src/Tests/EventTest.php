<?php

namespace GingerPluginSdk\Tests;

use GingerPluginSdk\Entities\Event;
use GingerPluginSdk\Exceptions\OutOfEnumException;
use GingerPluginSdk\Exceptions\OutOfPatternException;

class EventTest extends \PHPUnit\Framework\TestCase
{
    public function test_valid_event()
    {
        $event = new Event(
            occurred: '2022-05-17T11:58:33.813534+00:00',
            event: 'new',
            source: 'google',
            noticed: '2022-05-17T11:58:33.813534+00:00',
            id: '123'
        );
        self::assertSame(
            expected: $event::class,
            actual: Event::class
        );;
    }

    public function test_event_invalid()
    {
        self::expectException(OutOfEnumException::class);
        new Event(
            occurred: '2022-05-17T11:58:33.813534+00:00',
            event: '1',
            source: '123',
            noticed: '2022-05-17T11:58:33.813534+00:00',
            id: '123'
        );
    }

    public function test_occurred_invalid()
    {
        self::expectException(OutOfPatternException::class);
        new Event(
            occurred: '2022-13534+00:00',
            event: 'new',
            source: '123',
            noticed: '2022-05-17T11:58:33.813534+00:00',
            id: '123'
        );
    }

    public function test_noticed_invalid()
    {
        self::expectException(OutOfPatternException::class);
        new Event(
            occurred: '2022-05-17T11:58:33.813534+00:00',
            event: 'new',
            source: '123',
            noticed: '2022-.813534+00:00',
            id: '123'
        );
    }

    public function test_to_array()
    {
        $expected = [
            "event" => 'new',
            'occurred' => '2022-05-17T11:58:33.813534+00:00',
            'noticed' => '2022-05-17T11:58:33.813534+00:00',
            'id' => '123',
            'source' => '123'
        ];
        $real = new Event(
            occurred: '2022-05-17T11:58:33.813534+00:00',
            event: 'new',
            source: '123',
            noticed: '2022-05-17T11:58:33.813534+00:00',
            id: '123'
        );
        self::assertEqualsCanonicalizing(
            expected: $expected,
            actual: $real->toArray()
        );
    }

    public function test_get_property_name()
    {
        self::assertSame(
            expected: '',
            actual: (new Event('2022-05-17T11:58:33.813534+00:00', 'new', '123'))->getPropertyName()
        );
    }
}