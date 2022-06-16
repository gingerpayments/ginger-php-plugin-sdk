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
    private BaseField $id;
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

    public function getId(): string|null
    {
        return isset($this->id) ? $this->id->get() : null;
    }

    public function getStatus(): string|null
    {
        return $this->status?->get();
    }

    public function getClient(): array
    {
        return $this->client?->toArray();
    }

    public function getCustomer(): array
    {
        return $this->customer->toArray();
    }

    public function getAmount(): int
    {
        return $this->amount->get();
    }

    public function getOrderLines(): ?OrderLines
    {
        return $this->orderLines;
    }

    public function getReturnUrl(): string
    {
        return $this->returnUrl->get();
    }

    public function getWebhookUrl(): string
    {
        return $this->webhookUrl->get();
    }

    public function getMerchantOrderId(): string
    {
        return $this->merchantOrderId->get();
    }

    public function getExtra(): array
    {
        return $this->extra?->toArray();
    }

    public function getDescription(): string
    {
        return $this->description->get();
    }

    public function getCurrentTransaction(): Transaction
    {
        return $this->transactions->get();
    }
}