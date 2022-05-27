<?php

namespace GingerPluginSdk\Tests;

use GingerPluginSdk\Client;
use GingerPluginSdk\Collections\AdditionalAddresses;
use GingerPluginSdk\Collections\OrderLines;
use GingerPluginSdk\Collections\PhoneNumbers;
use GingerPluginSdk\Collections\Transactions;
use GingerPluginSdk\Entities\Address;
use GingerPluginSdk\Entities\Customer;
use GingerPluginSdk\Entities\Extra;
use GingerPluginSdk\Entities\Line;
use GingerPluginSdk\Entities\Order;
use GingerPluginSdk\Entities\PaymentMethodDetails;
use GingerPluginSdk\Entities\Transaction;
use GingerPluginSdk\Properties\ClientOptions;
use GingerPluginSdk\Properties\Country;
use GingerPluginSdk\Properties\Birthdate;
use GingerPluginSdk\Properties\Currency;
use GingerPluginSdk\Properties\EmailAddress;
use GingerPluginSdk\Properties\Locale;
use PHPUnit\Framework\TestCase;

class FromArrayTest extends TestCase
{
    private Client $client;

    public function setUp(): void
    {
        $this->client = new \GingerPluginSdk\Client(
            new ClientOptions(
                endpoint: $_ENV["PUBLIC_API_URL"],
                useBundle: true,
                apiKey: getenv('GINGER_API_KEY'))
        );
    }

    public function test_payment_methods_details_from_array()
    {
        $real = $this->client->fromArray(
            PaymentMethodDetails::class,
            [
                "issuer_id" => "15"
            ]
        );
        $expected = new PaymentMethodDetails(
            issuer_id: "15"
        );
        self::assertSame(
            $real->toArray(),
            $expected->toArray()
        );
    }

    public function test_transactions_from_array()
    {
        $real = $this->client->fromArray(
            Transactions::class,
            [
                [
                    'payment_method' => 'ideal',
                    'payment_method_details' => [
                        'issuer_id' => '15'
                    ]
                ]
            ]
        );
        $expected = new Transactions(
            new Transaction(
                paymentMethod: 'ideal',
                paymentMethodDetails: new PaymentMethodDetails(
                    issuer_id: "15"
                )
            )
        );
        self::assertSame(
            $real->toArray(),
            $expected->toArray()
        );
    }

    public function test_order_from_array()
    {
        $_SERVER["REMOTE_ADDR"] = "173.0.2.5";
        $_SERVER["HTTP_USER_AGENT"] = "PHPUnit Tests";
        $expected_order = new Order(
            currency: new Currency('EUR'),
            amount: 500,
            transactions: new Transactions(
                new Transaction(
                    paymentMethod: 'ideal',
                    paymentMethodDetails: new PaymentMethodDetails(
                        issuer_id: "15"
                    )
                )
            ),
            customer: new Customer(
                additionalAddresses: new AdditionalAddresses(
                    new Address(
                        addressType: 'customer',
                        postalCode: '12345',
                        street: 'Soborna',
                        city: 'Poltava',
                        country: new Country(
                            'UA'
                        )
                    ),
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
                ),
                firstName: 'Alexander',
                lastName: 'Tiutiunnyk',
                emailAddress: new EmailAddress(
                    'tutunikssa@gmail.com'
                ),
                gender: 'male',
                phoneNumbers: new PhoneNumbers(
                    '0951018201'
                ),
                merchantCustomerId: '15',
                birthdate: new \GingerPluginSdk\Properties\Birthdate('1999-09-01'),
                locale: new Locale(
                    'Ua_ua'

                )
            ),
            orderLines: new OrderLines(
                new Line(
                    type: 'physical',
                    merchantOrderLineId: "5",
                    name: 'Milk',
                    quantity: 1,
                    amount: 1.00,
                    vatPercentage: 50,
                    currency: new Currency(
                        'EUR'
                    )
                )
            ),
            description: 'Test Product',
            extra: new Extra(
                ['sw_order_id' => "501"]
            ),
            client: new \GingerPluginSdk\Entities\Client(
                userAgent: $_SERVER['HTTP_USER_AGENT'],
                platformName: 'docker',
                platformVersion: '1',
                pluginName: 'ginger-plugin-sdk',
                pluginVersion: '1.0.0'
            )
        );
        $real_order = $this->client->fromArray(
            Order::class,
            [
                "amount" => 50000,
                "description" => "Test Product",
                "extra" => [
                    "sw_order_id" => "501"
                ],
                "client" =>
                    [
                        "user_agent" => "PHPUnit Tests",
                        "platform_name" => "docker",
                        "platform_version" => "1",
                        "plugin_name" => "ginger-plugin-sdk",
                        "plugin_version" => "1.0.0"
                    ],
                "currency" => "EUR",
                "transactions" => [
                    [
                        "payment_method" => "ideal",
                        "payment_method_details" =>
                            [
                                "issuer_id" => "15"
                            ]

                    ]
                ],
                "customer" => [
                    "last_name" => "Tiutiunnyk",
                    "first_name" => "Alexander",
                    "gender" => "male",
                    "country" => "NL",
                    "phone_numbers" => [
                        "0951018201"
                    ],
                    "merchant_customer_id" => "15",
                    "locale" => "Ua_ua",
                    "ip_address" => "173.0.2.5",
                    "additional_addresses" => [
                        [
                            "address_type" => "customer",
                            "postal_code" => "12345",
                            "country" => "UA",
                            "city" => "Poltava",
                            "street" => "Soborna",
                            "address" => "Soborna 12345 Poltava"
                        ],
                        [
                            "address_type" => "billing",
                            "postal_code" => "1234567",
                            "country" => "NL",
                            "city" => "Amsterdam",
                            "street" => "Donauweg",
                            "address" => "Donauweg 10 1234567 Amsterdam",
                            "housenumber" => "10"
                        ]

                    ],

                    "email_address" => "tutunikssa@gmail.com",
                    "birthdate" => "1999-09-01",
                ],

                "order_lines" => [
                    [
                        "type" => "physical",
                        "merchant_order_line_id" => "5",
                        "name" => "Milk",
                        "quantity" => 1,
                        "amount" => 100,
                        "vat_percentage" => 5000,
                        "currency" => "EUR"
                    ]

                ]
            ]
        );
        self::assertSame($expected_order->toArray(), $real_order->toArray());
    }

    public function test_extra_from_array()
    {
        $real = $this->client->fromArray(
            Extra::class,
            [
                "sw_order_id" => '501'
            ]);
        $expected = new Extra([
            "sw_order_id" => "501"
        ]);
        self::assertSame(
            $expected->toArray(),
            $real->toArray()
        );
    }

    public function test_customer_from_array()
    {
        $_SERVER['REMOTE_ADDR'] = '173.0.2.5';
        $expected_customer = new Customer(
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
        $real_customer = $this->client->fromArray(
            Customer::class,
            [
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
                'email_address' => 'order@weed.you',
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
            ]
        );
        self::assertSame($expected_customer->toArray(), $real_customer->toArray());
    }

    public function test_customer_from_api_array()
    {
        $order_array = $this->client->getApiClient()->getOrder($_ENV["ORDER_ID_FOR_TESTS"]);
        $expected = $order_array["customer"];
        $real = $this->client->fromArray(
            Customer::class,
            $expected
        )->toArray();
        self::assertEqualsCanonicalizing(
            expected: $expected,
            actual: $real
        );
    }
}