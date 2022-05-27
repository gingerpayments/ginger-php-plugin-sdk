<?php

namespace GingerPluginSdk\Tests;

use GingerPluginSdk\Exceptions\OrderCreationFailedException;
use GingerPluginSdk\Exceptions\OutOfEnumException;
use GingerPluginSdk\Properties\Status;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    public function test_allowed_status()
    {
        $exp = 'new';
        $real = new Status(
            value: 'new'
        );
        self::assertSame(
            expected: $exp,
            actual: $real->get()
        );
    }

    public function test_non_allowed_status()
    {
        self::expectException(OutOfEnumException::class);
        new Status(
            value: 'fake'
        );
    }
}