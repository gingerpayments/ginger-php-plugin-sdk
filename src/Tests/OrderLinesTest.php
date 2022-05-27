<?php

declare(strict_types=1);

namespace GingerPluginSdk\Tests;

use GingerPluginSdk\Collections\OrderLines;
use GingerPluginSdk\Entities\Line;
use GingerPluginSdk\Properties\Locale;
use PHPUnit\Framework\TestCase;

class OrderLinesTest extends TestCase
{
    private OrderLines $order_lines;

    public function setUp(): void
    {
        $this->order_lines = new OrderLines(
            new Line(
                type: 'physical',
                merchantOrderLineId: '0',
                name: 'Milk',
                quantity: 1,
                amount: 50.5,
                vatPercentage: 25
            ),
            new Line(
                type: 'shipping_fee',
                merchantOrderLineId: '0',
                name: 'Fly',
                quantity: 1,
                amount: 50.5,
                vatPercentage: 25
            ),

        );
    }

    public function test_invalid_type_line()
    {
        self::expectException(\TypeError::class);
        $test = new OrderLines(
            new Locale(
                'NL_be'
            )
        );
    }

    public function test_to_array()
    {
        $expected_array = [
            [
                'type' => 'physical',
                'name' => 'Milk',
                'quantity' => 1,
                'amount' => 5050,
                'vat_percentage' => 2500,
                'merchant_order_line_id' => "0"
            ],
            [
                'type' => 'shipping_fee',
                'name' => 'Fly',
                'quantity' => 1,
                'amount' => 5050,
                'vat_percentage' => 2500,
                'merchant_order_line_id' => "0"
            ]
        ];
        $real = $this->order_lines->toArray();
        self::assertEqualsCanonicalizing(
            expected: $expected_array,
            actual: $real
        );
    }

    public function test_get_property()
    {
        self::assertSame(
            $this->order_lines->getPropertyName(),
            'order_lines'
        );
    }
}
