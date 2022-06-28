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

    public function test_update_phone_numbers()
    {
        $phone_numbers = new PhoneNumbers();
        $phone_numbers->addPhoneNumber('095');
        $phone_numbers->addPhoneNumber('059');
        self::assertEqualsCanonicalizing(
            expected: array_replace(
                $phone_numbers->toArray(),
                [
                    0 => '12312'
                ]
            ),
            actual: $phone_numbers->updatePhoneNumber('12312', 1)->toArray()
        );
    }
}
