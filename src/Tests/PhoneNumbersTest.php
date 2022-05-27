<?php

declare(strict_types=1);

namespace GingerPluginSdk\Tests;

use GingerPluginSdk\Collections\PhoneNumbers;
use PHPUnit\Framework\TestCase;

class PhoneNumbersTest extends TestCase
{
    private PhoneNumbers $phone_number;

    public function setUp(): void
    {
        $this->phone_number = new PhoneNumbers(
            '810-230-14'
        );
    }

    public function test_to_array()
    {
        $expected = [
            '810-230-14'
        ];
        self::assertSame(
            $expected,
            $this->phone_number->toArray()
        );
    }

    public function test_invalid_type()
    {
        self::expectException(\TypeError::class);
        $test = new PhoneNumbers(
            510293123
        );
    }

    public function test_get_property()
    {
        self::assertSame(
            $this->phone_number->getPropertyName(),
            'phone_numbers'
        );
    }
}
