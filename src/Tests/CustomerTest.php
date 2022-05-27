<?php

declare(strict_types=1);

namespace GingerPluginSdk\Tests;

use Cassandra\Date;
use GingerPluginSdk\Collections\AdditionalAddresses;
use GingerPluginSdk\Collections\PhoneNumbers;
use GingerPluginSdk\Entities\Address;
use GingerPluginSdk\Entities\Customer;
use GingerPluginSdk\Exceptions\OutOfEnumException;
use GingerPluginSdk\Exceptions\OutOfPatternException;
use GingerPluginSdk\Properties\Country;
use GingerPluginSdk\Properties\Birthdate;
use GingerPluginSdk\Properties\EmailAddress;
use GingerPluginSdk\Properties\Locale;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    private Customer $customer;

    public function createMockAddress(): Address
    {
        return new Address(
            addressType: 'customer',
            postalCode: '1642AJ',
            street: 'Weednesday',
            city: 'Amsterdam',
            country: new Country(
                'NL'
            ),
            housenumber: '10'
        );
    }

    public function createMockAdditionalAddress(Address $address)
    {
        return new AdditionalAddresses(
            $address
        );
    }

    public function createMockPhoneNumber()
    {
        return new PhoneNumbers(
            '1999201'
        );
    }

    public function getMockEmail()
    {
        return new EmailAddress('test@mail.test');
    }

    public function getMockBirthdate(): Birthdate
    {
        return new Birthdate('2021-02-08');
    }

    public function getMockLocale(): Locale
    {
        return new Locale(
            "NL_en"
        );
    }

    public function setUp(): void
    {
        $this->customer = new Customer(
            additionalAddresses: new AdditionalAddresses(
                new Address(
                    addressType: 'customer',
                    postalCode: '1642AJ',
                    street: 'Weednesday',
                    city: 'Amsterdam',
                    country: new Country(
                        'NL'
                    ),
                    housenumber: '10'
                )
            ),
            firstName: 'Ley',
            lastName: 'Paris',
            emailAddress: new EmailAddress(
                value: 'order@weed.you'
            ),
            gender: 'male',
            phoneNumbers: new PhoneNumbers(
                '123456',
                '8-800-555-35-35'
            ),
            merchantCustomerId: '666',
            birthdate: new Birthdate('2021-07-01'),
            locale: new Locale(
                value: 'NL_en'
            )
        );
    }

    public function test_invalid_type_locale()
    {
        self::expectException(\TypeError::class);
        $test = new Customer(
            additionalAddresses: $this->createMockAdditionalAddress($this->createMockAddress()),
            firstName: 'Test',
            lastName: 'Test',
            emailAddress: $this->getMockEmail(),
            gender: 'animal',
            phoneNumbers: $this->createMockPhoneNumber(),
            merchantCustomerId: '0',
            birthdate: $this->getMockBirthdate(),
            locale: '123'
        );
    }

    public function test_invalid_type_additionall_address()
    {
        self::expectException(\TypeError::class);
        $test = new Customer(
            additionalAddresses: '123',
            firstName: 'Test',
            lastName: 'Test',
            emailAddress: $this->getMockEmail(),
            gender: 'animal',
            phoneNumbers: $this->createMockPhoneNumber(),
            merchantCustomerId: '0',
            birthdate: $this->getMockBirthdate(),
            locale: $this->getMockLocale()
        );
    }

    public function test_invalid_type_email_address()
    {
        self::expectException(\TypeError::class);
        $test = new Customer(
            additionalAddresses: $this->createMockAdditionalAddress($this->createMockAddress()),
            firstName: 'Test',
            lastName: 'Test',
            emailAddress: 'email',
            gender: 'animal',
            phoneNumbers: $this->createMockPhoneNumber(),
            merchantCustomerId: '0',
            birthdate: $this->getMockBirthdate(),
            locale: $this->getMockLocale()
        );
    }

    public function test_invalid_type_phone_numbers()
    {
        self::expectException(\TypeError::class);
        $test = new Customer(
            additionalAddresses: $this->createMockAdditionalAddress($this->createMockAddress()),
            firstName: 'Test',
            lastName: 'Test',
            emailAddress: $this->getMockEmail(),
            gender: 'animal',
            phoneNumbers: 'phones',
            merchantCustomerId: '0',
            birthdate: $this->getMockBirthdate(),
            locale: $this->getMockLocale()
        );
    }

    public function test_invalid_type_birthdate()
    {
        self::expectException(\TypeError::class);
        $test = new Customer(
            additionalAddresses: $this->createMockAdditionalAddress($this->createMockAddress()),
            firstName: 'Test',
            lastName: 'Test',
            emailAddress: $this->getMockEmail(),
            gender: 'animal',
            phoneNumbers: $this->createMockPhoneNumber(),
            merchantCustomerId: '0',
            birthdate: 5,
            locale: $this->getMockLocale()
        );
    }

    public function test_invalid_enum_gender()
    {
        self::expectException(OutOfEnumException::class);
        $test = new Customer(
            additionalAddresses: $this->createMockAdditionalAddress($this->createMockAddress()),
            firstName: 'Test',
            lastName: 'Test',
            emailAddress: $this->getMockEmail(),
            gender: 'animal',
            phoneNumbers: $this->createMockPhoneNumber(),
            merchantCustomerId: '0',
            birthdate: $this->getMockBirthdate(),
            locale: $this->getMockLocale()
        );
    }

    public function test_to_array()
    {
        $expected_customer = [
            'additional_addresses' => [
                [
                    'address_type' => 'customer',
                    'postal_code' => '1642AJ',
                    'country' => 'NL',
                    'city' => 'Amsterdam',
                    'street' => 'Weednesday',
                    'address' => 'Weednesday 10 1642AJ Amsterdam',
                    'housenumber' => '10'
                ]
            ],
            'email' => 'order@weed.you',
            'birthdate' => '2021-07-01',
            'merchant_customer_id' => '666',
            'country' => 'NL',
            'locale' => 'NL_en',
            'ip_address' => '173.0.2.5',
            'phoneNumbers' => [
                '123456',
                '8-800-555-35-35'
            ],
            'gender' => 'male',
            'first_name' => 'Ley',
            'last_name' => 'Paris'
        ];
        $actual = $this->customer->toArray();
        sort($expected_customer);
        sort($actual);
        self::assertSame(
            expected: $expected_customer,
            actual: $actual
        );
    }

    public function test_get_property()
    {
        self::assertSame(
            $this->customer->getPropertyName(),
            'customer'
        );
    }
}
