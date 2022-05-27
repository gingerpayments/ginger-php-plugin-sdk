<?php

namespace GingerPluginSdk\Tests;

use GingerPluginSdk\Collections\Transactions;
use GingerPluginSdk\Entities\PaymentMethodDetails;
use GingerPluginSdk\Entities\Transaction;
use GingerPluginSdk\Properties\Locale;
use PHPUnit\Framework\TestCase;

class TransactionsTest extends TestCase
{
    private Transactions $transactions;

    public function setUp(): void
    {
        $this->transactions = new Transactions(
            new Transaction(
                paymentMethod: 'ideal',
                paymentMethodDetails: new PaymentMethodDetails(
                    issuer_id: "15"
                )
            )
        );
    }

    public function test_to_array()
    {
        $expected = [
            [
                'payment_method' => 'ideal',
                'payment_method_details' => [
                    'issuer_id' => "15"
                ]
            ]
        ];
        self::assertSame(
            $expected,
            $this->transactions->toArray()
        );
    }

    public function test_invalid_constructor_item_type()
    {
        self::expectException(\TypeError::class);
        $test = new Transactions(
            new Locale("NL_nl")
        );
    }

    public function test_get_property()
    {
        self::assertSame(
            $this->transactions->getPropertyName(),
            'transactions'
        );
    }
}
