<?php

namespace GingerPluginSdk\Tests;

use GingerPluginSdk\Client;
use GingerPluginSdk\Entities\Order;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    private \GingerPluginSdk\Entities\Order $order;

    public function setUp(): void
    {
        $_SERVER["REMOTE_ADDR"] = 'test';
        $_SERVER["HTTP_USER_AGENT"] = 'test';
        $this->order = OrderStub::getValidOrder();
    }

    public function test_get_client()
    {
        self::assertSame(
            expected: 'client',
            actual: $this->order->getClient()->getPropertyName()
        );
    }

    public function test_get_amount()
    {
        self::assertSame(
            expected: 'amount',
            actual: $this->order->getAmount()->getPropertyName()
        );
    }

    public function test_get_merchant_order_id()
    {
        $old_order = $this->order->toArray();
        $old_order['merchant_order_id'] = 5;
        $updated_order = Client::fromArray(
            Order::class,
            $old_order
        );
        self::assertSame(
            expected: 'merchantOrderId',
            actual: $updated_order->getMerchantOrderId()->getPropertyName()
        );
    }
}