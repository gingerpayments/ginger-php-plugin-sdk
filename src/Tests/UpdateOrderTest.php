<?php

namespace GingerPluginSdk\Tests;

use GingerPluginSdk\Client;
use GingerPluginSdk\Entities\Line;
use GingerPluginSdk\Properties\Amount;
use GingerPluginSdk\Properties\ClientOptions;
use GingerPluginSdk\Properties\Currency;
use GingerPluginSdk\Properties\Percentage;
use GingerPluginSdk\Properties\RawCost;
use GingerPluginSdk\Properties\VatPercentage;
use PHPUnit\Framework\TestCase;

class UpdateOrderTest extends TestCase
{
    private Client $client;

    public function setUp(): void
    {
        $this->client = new Client(
            options: OrderStub::getMockedClientOptions()
        );
        $_SERVER["REMOTE_ADDR"] = "173.0.2.5";
        $_SERVER["HTTP_USER_AGENT"] = "PHPUnit Tests";
    }

    public function test_valid_update_order_call_description()
    {
        $order = $this->client->sendOrder(
            order: OrderStub::getValidOrder()
        );
    }

    public function test_update_line()
    {
        $order = OrderStub::getValidOrder();
        $expected = array_merge(
            $order->toArray(),
            [
                'order_lines' => [
                    [
                        'type' => 'physical',
                        'merchant_order_line_id' => '5',
                        'name' => 'Felix',
                        'quantity' => 1,
                        'amount' => 100,
                        'vat_percentage' => 5000,
                        'currency' => 'EUR'
                    ]
                ]
            ]);
        $order->getOrderLines()->get()->update(name: 'Felix');
        self::assertEqualsCanonicalizing(
            expected: $expected,
            actual: $order->toArray()
        );
    }

    public function test_update_order_line()
    {
        $order = OrderStub::getValidOrder();
        $expected = array_merge(
            $order->toArray(),
            [
                'order_lines' => [
                    [
                        'type' => 'physical',
                        'merchant_order_line_id' => '5',
                        'name' => 'Felix',
                        'quantity' => 1,
                        'amount' => 100,
                        'vat_percentage' => 5000,
                        'currency' => 'EUR'
                    ]
                ]
            ]);
        $order->getOrderLines()->updateLine(new Line(
                type: 'physical',
                merchantOrderLineId: "5",
                name: 'Felix',
                quantity: 1,
                amount: new Amount(new RawCost(1.00)),
                vatPercentage: new VatPercentage(new Percentage(50)),
                currency: new Currency(
                    'EUR'
                )
            )
        );
        self::assertEqualsCanonicalizing(
            expected: $expected,
            actual: $order->toArray()
        );
    }
}