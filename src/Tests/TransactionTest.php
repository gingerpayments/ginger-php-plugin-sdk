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

    public function setUp(): void
    {
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


    public function test_method_get_id()
    {
        $real = Client::fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["id" => "1234567890"]
            )
        );
        $expected = "1234567890";
        self::assertSame(
            $real->getId()->get(),
            $expected
        );
    }

    public function test_method_is_capturable()
    {
        $real = Client::fromArray(
            Transaction::class,
            array_merge(
                self::MOCK_DATA_FOR_TRANSACTION,
                ["is_capturable" => true]
            )
        );
        $expected = true;
        self::assertSame(
            $real->isCapturable(),
            $expected
        );
    }

    public function test_update_transaction()
    {
        $transaction = OrderStub::getValidTransaction();
        self::assertSame(
            expected: array_replace(
                $transaction->toArray(),
                ['payment_method'=> 'non-existing']
            ),
            actual: $transaction->update(payment_method: 'non-existing')->toArray()
        );
    }
}
