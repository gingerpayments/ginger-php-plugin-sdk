<?php

namespace GingerPluginSdk\Tests;

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
use GingerPluginSdk\Exceptions\APIException;
use GingerPluginSdk\Properties\ClientOptions;
use GingerPluginSdk\Properties\Country;
use GingerPluginSdk\Properties\Currency;
use GingerPluginSdk\Properties\EmailAddress;
use GingerPluginSdk\Properties\Locale;
use PHPUnit\Framework\TestCase;

class CreateOrderTest extends TestCase
{
    private Order $order;
    private \GingerPluginSdk\Client $client;

    public function setUp(): void
    {
        $_SERVER["REMOTE_ADDR"] = "173.0.2.5";
        $_SERVER["HTTP_USER_AGENT"] = "PHPUnit Tests";

        $this->client = new \GingerPluginSdk\Client(
            new ClientOptions(
                endpoint: $_ENV["PUBLIC_API_URL"],
                useBundle: true,
                apiKey: getenv('GINGER_API_KEY'))
        );
        $this->order = new Order(
            currency: new Currency('EUR'),
            amount: 500,
            transactions: new Transactions(
                $this->getTransactions()
            ),
            customer: $this->getCustomer(),
            orderLines: $this->getOrderLines(),
            description: 'Test Product',
            extra: new Extra(
                ['sw_order_id' => "501"]
            ),
            client: $this->getClient()
        );
    }

    /**
     * @throws \Exception
     */
    public function test_sending()
    {
        $response = $this->client->sendOrder($this->order);
        self::assertSame($response["status"], 'new');
    }

    public function test_get_property()
    {
        self::assertSame(
            $this->order->getPropertyName(),
            ''
        );
    }

    public function getTransactions()
    {
        return new Transaction(
            paymentMethod: 'ideal',
            paymentMethodDetails: new PaymentMethodDetails(
                issuer_id: "15"
            )
        );
    }

    public function getCustomer()
    {
        return new Customer(
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
        );
    }

    public function getOrderLines()
    {
        return new OrderLines(
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
        );
    }

    public function getClient()
    {
        return  new \GingerPluginSdk\Entities\Client(
            userAgent: $_SERVER['HTTP_USER_AGENT'],
            platformName: 'docker',
            platformVersion: '1',
            pluginName: 'ginger-plugin-sdk',
            pluginVersion: '1.0.0'
        );
    }

    public function test_exception_validation_ideal()
    {
        self::expectException(APIException::class);
        $test_order = new Order(
            currency: new Currency('NUL'),
            amount: 500,
            transactions: new Transactions(
                $this->getTransactions()
            ),
            customer: $this->getCustomer(),
            orderLines: $this->getOrderLines(),
            description: 'Test Product',
            extra: new Extra(
                ['sw_order_id' => 501]
            ),
            client: $this->getClient()
        );
        $response = $this->client->sendOrder($test_order);
    }

    public function test_exception_validation_afterpay()
    {
        self::expectException(APIException::class);
        $test_order = new Order(
            currency: new Currency('NUL'),
            amount: 500,
            transactions: new Transactions(
                new Transaction(
                    paymentMethod: 'credit-card',
                    paymentMethodDetails: new PaymentMethodDetails(
                        issuer_id: "15",
                        verified_terms_of_service: true
                    )
                )
            ),
            customer: $this->getCustomer(),
            orderLines: $this->getOrderLines(),
            description: 'Test Product',
            extra: new Extra(
                ['sw_order_id' => 501]
            ),
            client: $this->getClient()
        );
        $response = $this->client->sendOrder($test_order);
    }
}