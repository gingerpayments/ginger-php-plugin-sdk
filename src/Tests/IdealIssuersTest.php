<?php

namespace GingerPluginSdk\Tests;

use GingerPluginSdk\Collections\IdealIssuers;
use GingerPluginSdk\Entities\Issuer;
use PHPUnit\Framework\TestCase;

class IdealIssuersTest extends TestCase
{
    private IdealIssuers $issuers;

    public function setUp(): void
    {
        $this->issuers = new IdealIssuers(
            new Issuer(
                id: 'ak12',
                listType: 'admin',
                name: 'bill'
            ),
            new Issuer(
                id: 'fp11',
                listType: 'custom',
                name: 'deposit'
            ),

        );
    }

    public function test_to_array()
    {
        self::assertSame(
            expected: [
                [
                    "id" => "ak12",
                    "list_type" => "admin",
                    "name" => "bill"
                ],
                [
                    "id" => "fp11",
                    "list_type" => "custom",
                    "name" => "deposit"
                ]
            ],
            actual: $this->issuers->toArray()
        );
    }

    public function test_get_property_name()
    {
        self::assertSame(
            expected: 'issuers',
            actual: $this->issuers->getPropertyName()
        );
    }
}