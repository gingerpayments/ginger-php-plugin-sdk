<?php

declare(strict_types=1);

namespace GingerPluginSdk\Tests;

use GingerPluginSdk\Entities\Line;
use GingerPluginSdk\Exceptions\OutOfDiapasonException;
use GingerPluginSdk\Exceptions\OutOfEnumException;
use PHPUnit\Framework\TestCase;

class LineTest extends TestCase
{
    private Line $line;

    public function setUp(): void
    {
        $this->line = new Line(
            type: 'physical',
            merchantOrderLineId: '1',
            name: 'Bottle',
            quantity: 3,
            amount: 664,
            vatPercentage: 25
        );
    }

    public function test_valid_line()
    {
        $expected_array = [
            'type' => 'physical',
            'merchant_order_line_id' => '1',
            'name' => 'Bottle',
            'quantity' => 3,
            'amount' => 66400,
            'vat_percentage' => 2500
        ];
        self::assertSame($expected_array, $this->line->toArray());
    }

    public function test_invalid_count_arguments_line()
    {
        self::expectException(\ArgumentCountError::class);
        $test = new Line(
            name: 'Test'
        );
    }

    public function test_invalid_enum_for_type()
    {
        self::expectException(OutOfEnumException::class);
        $test = new Line(
            type: 'magical',
            merchantOrderLineId: '1',
            name: 'Bottle',
            quantity: 3,
            amount: 664,
            vatPercentage: 25
        );
    }

    public function test_set_discount_rate()
    {
        $expected = 1500;
        self::assertSame(
            $expected,
            $this->line->setDiscountRate(15)->getDiscountRate());
    }

    public function test_discount_rate_out_of_diapason()
    {
        self::expectException(OutOfDiapasonException::class);
        $this->line->setDiscountRate(101);
    }

    public function test_set_url()
    {
        $expected = 'programminghub.com/pc.png';
        self::assertSame(
            $expected,
            $this->line->setUrl('programminghub.com/pc.png')->getUrl()
        );
    }

    public function test_vat_percentage_out_of_diapason()
    {
        self::expectException(OutOfDiapasonException::class);
        $test = new Line(
            type: "discount",
            merchantOrderLineId: '5',
            name: 'Home',
            quantity: 1,
            amount: 5,
            vatPercentage: 110,
        );
    }

    public function test_quantity_out_of_diapason()
    {
        self::expectException(OutOfDiapasonException::class);
        $test = new Line(
            type: "discount",
            merchantOrderLineId: '5',
            name: 'Home',
            quantity: 0,
            amount: 5,
            vatPercentage: 20,
        );
    }

    public function test_currency_field_type()
    {
        self::expectException(\TypeError::class);
        $test = new Line(
            currency: 'EUR'
        );
    }

    public function test_get_property()
    {
        self::assertSame(
            $this->line->getPropertyName(),
            ""
        );
    }
}
