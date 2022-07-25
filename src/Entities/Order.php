<?php

namespace GingerPluginSdk\Entities;

use GingerPluginSdk\Bases\BaseField;
use GingerPluginSdk\Collections\Flags;
use GingerPluginSdk\Collections\OrderLines;
use GingerPluginSdk\Collections\Transactions;
use GingerPluginSdk\Helpers\FieldsValidatorTrait;
use GingerPluginSdk\Helpers\HelperTrait;
use GingerPluginSdk\Helpers\MultiFieldsEntityTrait;
use GingerPluginSdk\Helpers\SingleFieldTrait;
use GingerPluginSdk\Interfaces\MultiFieldsEntityInterface;
use GingerPluginSdk\Properties\Amount;
use GingerPluginSdk\Properties\Currency;
use GingerPluginSdk\Properties\Status;

final class Order implements MultiFieldsEntityInterface
{
    use HelperTrait;
    use MultiFieldsEntityTrait;
    use FieldsValidatorTrait;
    use SingleFieldTrait;

    private BaseField|null $id = null;
    private BaseField|null $merchantOrderId = null;

    public function __construct(
        private Currency     $currency,
        private Amount       $amount,
        private Transactions $transactions,
        private Customer     $customer,
        private ?OrderLines  $orderLines = null,
        private ?Extra       $extra = null,
        private ?Client      $client = null,
        private ?Flags       $flags = null,
        ?string              $id = null,
        private ?Status      $status = null,
        ?string              $merchantOrderId = null,
        mixed                ...$additionalProperties
    )
    {
        if ($id) $this->id = $this->createSimpleField(
            propertyName: 'id',
            value: $id
        );

        if ($merchantOrderId) $this->merchantOrderId = $this->createSimpleField(
            propertyName: 'merchantOrderId',
            value: $merchantOrderId
        );

        if ($additionalProperties) $this->filterAdditionalProperties($additionalProperties);
    }

    public function getId(): BaseField|null
    {
        return $this->id;
    }

    public function getStatus(): Status|null
    {
        return $this->status;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }

    public function getOrderLines(): ?OrderLines
    {
        return $this->orderLines;
    }

    public function getMerchantOrderId(): ?BaseField
    {
        return $this->merchantOrderId;
    }

    public function getExtra(): Extra
    {
        return $this->extra;
    }

    public function getCurrentTransaction(): Transaction
    {
        return $this->transactions->get();
    }
}