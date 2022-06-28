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

class Order implements MultiFieldsEntityInterface
{
    use HelperTrait;
    use MultiFieldsEntityTrait;
    use FieldsValidatorTrait;
    use SingleFieldTrait;

    private BaseField $merchantOrderId;
    private BaseField $webhookUrl;
    private BaseField $returnUrl;
    private BaseField $description;
    private BaseField $created;
    private BaseField|null $id;
    private BaseField $lastTransactionAdded;
    private BaseField $merchantId;
    private BaseField $modified;
    private BaseField $projectId;
    private BaseField $completed;

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
        mixed                ...$additionalProperties
    )
    {
        if ($id) $this->id = $this->createSimpleField(
            propertyName: 'id',
            value: $id
        );

        if ($additionalProperties) $this->filterAdditionalProperties($additionalProperties);
    }

    public function getId(): BaseField|null
    {
        return $this->id;
    }

    public function getStatus(): Status|false
    {
        return $this->status ?? false;
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

    public function getReturnUrl(): BaseField
    {
        return $this->returnUrl;
    }

    public function getWebhookUrl(): BaseField
    {
        return $this->webhookUrl;
    }

    public function getMerchantOrderId(): BaseField
    {
        return $this->merchantOrderId;
    }

    public function getExtra(): Extra
    {
        return $this->extra;
    }

    public function getDescription(): BaseField
    {
        return $this->description;
    }

    public function getCurrentTransaction(): Transaction
    {
        return $this->transactions->get();
    }
}