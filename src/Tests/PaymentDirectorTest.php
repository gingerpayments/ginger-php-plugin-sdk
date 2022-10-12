<?php

namespace GingerPluginSdk\Tests;

use GingerPluginSdk\Client;
use GingerPluginSdk\Entities\Order;
use GingerPluginSdk\Exceptions\APIException;
use GingerPluginSdk\PaymentDirector;
use GingerPluginSdk\Builders\IdealBuilder;
use GingerPluginSdk\Properties\Amount;
use GingerPluginSdk\Properties\Currency;
use GingerPluginSdk\Properties\RawCost;
use PHPUnit\Framework\TestCase;

class PaymentDirectorTest extends TestCase
{
    /**
     * @var IdealBuilder
     */
    public $builder;

    /**
     * @var PaymentDirector
     */
    public $paymentDirector;

    public function setUp(): void
    {
        $this->builder = new IdealBuilder();
        $this->paymentDirector = new PaymentDirector();
        $_SERVER["REMOTE_ADDR"] = "173.0.2.5";
        $_SERVER["HTTP_USER_AGENT"] = "PHPUnit Tests";
    }

    public function test_make_ideal_payment()
    {
        $client = new Client(
            OrderStub::getMockedClientOptions()
        );

        $order = $this->paymentDirector->makeIdealPayment(
            client: $client,
            builder: $this->builder,
            currency: 'EUR',
            amount: new RawCost(500),
            customer: OrderStub::getValidCustomer(),
            issuerId: 'test_issuer',
            extra: OrderStub::getValidExtra(),
            orderLines: OrderStub::getValidOrderLines(),
            clientEntity: OrderStub::getValidClient(),
            description: 'Description',
            webhookUrl: 'http://test.com/web',
            returnUrl: 'http://test.com/return',
            merchantOrderId: 42

        );

        self::assertSame('new', $order->getStatus()->get(), 'Order created wrong same!');

    }
}