<?php

namespace GingerPluginSdk\Tests;

use GingerPluginSdk\Collections\AdditionalAddresses;
use GingerPluginSdk\Entities\Address;
use GingerPluginSdk\Properties\Country;
use GingerPluginSdk\Properties\Locale;
use PHPUnit\Framework\TestCase;

class AdditionalAddressesTest extends TestCase
{
    private AdditionalAddresses $additionalAddresses;

    public function setUp(): void
    {
        $this->additionalAddresses = new AdditionalAddresses(
            new Address(
                addressType: 'billing',
                postalCode: '1234567',
                street: 'Donauweg',
                city: 'Amsterdam',
                country: new Country(
                    'NL'
                ),
                housenumber: "10"
            )
        );
    }

    public function test_invalid_type_address()
    {
        self::expectException(\TypeError::class);
        $test = new AdditionalAddresses(
            new Locale(
                'NL_be'
            )
        );
    }

    public function test_to_array()
    {
        $expected_array = [
            [
                "address_type" => "billing",
                "postal_code" => "1234567",
                "country" => "NL",
                "city" => "Amsterdam",
                "street" => "Donauweg",
                "address" => "Donauweg 10 1234567 Amsterdam",
                "housenumber" => "10"

            ]
        ];
        $real = $this->additionalAddresses->toArray();
        sort($expected_array);
            sort($real);
        self::assertSame(
            expected: $expected_array,
            actual:$real
        );
    }

    public function test_get_property()
    {
        self::assertSame(
            $this->additionalAddresses->getPropertyName(),
            'additional_addresses'
        );
    }
}
