<?php

namespace GingerPluginSdk\Tests;

use GingerPluginSdk\Client;
use GingerPluginSdk\Entities\PaymentMethodDetails;
use GingerPluginSdk\Entities\Transaction;
use GingerPluginSdk\Exceptions\OutOfDiapasonException;
use GingerPluginSdk\Exceptions\OutOfEnumException;
use GingerPluginSdk\Properties\ClientOptions;
use GingerPluginSdk\Properties\EmailAddress;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    private Transaction $transaction;
    private Client $client;

    public function setUp(): void
    {
        $this->client = new Client(
            new ClientOptions(
                endpoint: $_ENV["PUBLIC_API_URL"],
                useBundle: true,
                apiKey: getenv('GINGER_API_KEY')
            )
        );

        $this->transaction = new Transaction(
            paymentMethod: 'ideal',
            paymentMethodDetails: new PaymentMethodDetails(
                issuer_id: "15"
            )
        );
    }

    public function test_to_array()
    {
        $expected = [
            'payment_method' => 'ideal',
            'payment_method_details' => [
                'issuer_id' => '15'
            ]
        ];
        self::assertSame(
            $expected,
            $this->transaction->toArray()
        );
    }

    public function test_invalid_payment_method_type()
    {
        self::expectException(\TypeError::class);
        $test = new Transaction(
            paymentMethod: 'test', paymentMethodDetails: new EmailAddress('test@mail.nl')
        );
    }

    public function test_payment_method_out_of_enum()
    {
        self::expectException(OutOfEnumException::class);
        $test = new Transaction(
            paymentMethod: 'invalid_type',
            paymentMethodDetails: new PaymentMethodDetails(
                issuer_id: 'test'
            )
        );
    }

    public function test_get_property()
    {
        self::assertSame(
            $this->transaction->getPropertyName(),
            ""
        );
    }

    const MOCK_DATA_FOR_TRANSACTION = [
        "paymentMethod" => 'ideal',
        "paymentMethodDetails" => [
            "issuer_id" => 'UA_AIM'
        ]
    ];


    public function test_additional_property_id()
    {
        $real = $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["id" => "1234567890"]
            )
        );
        $expected = "1234567890";
        self::assertSame(
            $real->toArray()["id"],
            $expected
        );
    }

    public function test_additional_property_merchant_id()
    {
        $real = $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["merchant_id" => "1234567890"]
            )
        );
        $expected = "1234567890";
        self::assertSame(
            $real->toArray()["merchant_id"],
            $expected
        );
    }

    public function test_additional_property_created()
    {
        $real = $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["created" => "2022-05-05T16:32:20.148139+00:00"]
            )
        );
        $expected = "2022-05-05T16:32:20.148139+00:00";

        self::assertSame(
            $real->toArray()["created"],
            $expected
        );
    }

    public function test_additional_property_modified()
    {
        $real = $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["modified" => "2022-05-05T16:32:20.148139+00:00"]
            )
        );
        $expected = "2022-05-05T16:32:20.148139+00:00";
        self::assertSame(
            $real->toArray()["modified"],
            $expected
        );
    }

    public function test_additional_property_completed()
    {
        $real = $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["completed" => "2022-05-05T16:32:20.148139+00:00"]
            )
        );
        $expected = "2022-05-05T16:32:20.148139+00:00";
        self::assertSame(
            $real->toArray()["completed"],
            $expected
        );
    }

    public function test_additional_property_settled()
    {
        $real = $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["settled" => "2022-05-05T16:32:20.148139+00:00"]
            )
        );
        $expected = "2022-05-05T16:32:20.148139+00:00";
        self::assertSame(
            $real->toArray()["settled"],
            $expected
        );
    }

    public function test_additional_property_finalized()
    {
        $real = $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["finalized" => "2022-05-05T16:32:20.148139+00:00"]
            )
        );
        $expected = "2022-05-05T16:32:20.148139+00:00";
        self::assertSame(
            $real->toArray()["finalized"],
            $expected
        );
    }

    public function test_additional_property_expiration_period()
    {
        $real = $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["expiration_period" => "PT30M"]
            )
        );
        $expected = "PT30M";
        self::assertSame(
            $real->toArray()["expiration_period"],
            $expected
        );
    }

    public function test_additional_property_currency()
    {
        $real = $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["currency" => "EUR"]
            )
        );
        $expected = "EUR";
        self::assertSame(
            $real->toArray()["currency"],
            $expected
        );
    }

    public function test_additional_property_amount_valid()
    {
        $real = $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["amount" => 12.34]
            )
        );
        $expected = 1234;
        self::assertSame(
            $real->toArray()["amount"],
            $expected
        );
    }

    public function test_additional_property_amount_invalid()
    {
        self::expectException(OutOfDiapasonException::class);
        $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["amount" => 0.001]
            )
        );
    }

    public function test_additional_property_balance_valid()
    {
        $real = $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["balance" => "test"]
            )
        );
        $expected = "test";
        self::assertSame(
            $real->toArray()["balance"],
            $expected
        );
    }

    public function test_additional_property_balance_invalid()
    {
        self::expectException(OutOfEnumException::class);
        $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["balance" => "sss"]
            )
        );
    }

    public function test_additional_property_description()
    {
        $real = $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["description" => "test"]
            )
        );
        $expected = "test";
        self::assertSame(
            $real->toArray()["description"],
            $expected
        );
    }

    public function test_additional_property_product_type()
    {
        $real = $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["product_type" => "test"]
            )
        );
        $expected = "test";
        self::assertSame(
            $real->toArray()["product_type"],
            $expected
        );
    }

    public function test_additional_property_credit_debit_valid()
    {
        $real = $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["credit_debit" => "credit"]
            )
        );
        $expected = "credit";
        self::assertSame(
            $real->toArray()["credit_debit"],
            $expected
        );
    }

    public function test_additional_property_credit_debit_invalid()
    {
        self::expectException(OutOfEnumException::class);
        $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["credit_debit" => "n"]
            )
        );
    }

    public function test_additional_property_payment_method_brand()
    {
        $real = $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["payment_method_brand" => "ideal"]
            )
        );
        $expected = "ideal";
        self::assertSame(
            $real->toArray()["payment_method_brand"],
            $expected
        );
    }

    public function test_additional_property_payment_url()
    {
        $real = $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["payment_url" => "https://payment_page.ua"]
            )
        );
        $expected = "https://payment_page.ua";
        self::assertSame(
            $real->toArray()["payment_url"],
            $expected
        );
    }

    public function test_additional_property_status_valid()
    {
        $real = $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["status" => "completed"]
            )
        );
        $expected = "completed";
        self::assertSame(
            $real->toArray()["status"],
            $expected
        );
    }

    public function test_additional_property_reason()
    {
        $real = $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["reason" => "because"]
            )
        );
        $expected = "because";
        self::assertSame(
            $real->toArray()["reason"],
            $expected
        );
    }

    public function test_additional_property_is_capturable()
    {
        $real = $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["is_capturable" => true]
            )
        );
        $expected = true;
        self::assertSame(
            $real->toArray()["is_capturable"],
            $expected
        );
    }

    public function test_additional_property_order_id()
    {
        $real = $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["order_id" => "123"]
            )
        );
        $expected = "123";
        self::assertSame(
            $real->toArray()["order_id"],
            $expected
        );
    }

    public function test_additional_property_channel_valid()
    {
        $real = $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["channel" => "ecom"]
            )
        );
        $expected = "ecom";
        self::assertSame(
            $real->toArray()["channel"],
            $expected
        );
    }

    public function test_additional_property_channel_invalid()
    {
        self::expectException(OutOfEnumException::class);
        $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["channel" => "desp"]
            )
        );
    }

    public function test_additional_property_project_type()
    {
        $real = $this->client->fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["project_type" => "ginger"]
            )
        );
        $expected = "ginger";
        self::assertSame(
            $real->toArray()["project_type"],
            $expected
        );
    }
}
