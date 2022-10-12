<?php

namespace GingerPluginSdk\Builders;

use GingerPluginSdk\Entities\Order;
use GingerPluginSdk\Entities\PaymentMethodDetails;
use GingerPluginSdk\Properties\Currency;
use GingerPluginSdk\Properties\Amount;
use GingerPluginSdk\Properties\RawCost;
use GingerPluginSdk\Entities\Transaction;
use GingerPluginSdk\Collections\Transactions;
use GingerPluginSdk\Entities\Customer;
use GingerPluginSdk\Collections\OrderLines;
use GingerPluginSdk\Entities\Extra;
use GingerPluginSdk\Entities\Client as ClientEntity;
use GingerPluginSdk\Client;

class AbstractPaymentBuilder
{
    /**
     * @var Currency
     */
    public $currency;

    /**
     * @var Amount
     */
    public $amount;

    /**
     * @var Transactions
     */
    public $transactions;

    /**
     * @var Customer
     */
    public $customer;

    /**
     * @var PaymentMethodDetails
     */
    public $paymentMethodDetails;

    /**
     * @var OrderLines
     */
    public $orderLines;

    /**
     * @var Extra
     */
    public $extra;

    /**
     * @var ClientEntity
     */
    public $client;

    /**
     * @var string
     */
    public $webhookUrl;

    /**
     * @var string
     */
    public $returnUrl;

    /**
     * @var string
     */
    public $merchantOrderId;

    /**
     * @var string
     */
    public $description;

    /**
     * @var mixed
     */
    public $additionalProperties;

    public function setCurrency(string $currencyCode)
    {
        $this->currency = new Currency($currencyCode);
    }

    public function setAmount(RawCost $amount)
    {
        $this->amount = new Amount($amount);
    }

    public function setPaymentMethodDetails($issuerId, $verifiedTerms)
    {
        $this->paymentMethodDetails = new PaymentMethodDetails(
            array_filter([
                    'issuer_id' => $issuerId,
                    'verified_terms_of_service' => $verifiedTerms,
                ]
            )
        );
    }

    public function setTransactions(string $paymentMethod)
    {
        $this->transactions = new Transactions(
            new Transaction(
                paymentMethod: $paymentMethod,
                paymentMethodDetails: $this->paymentMethodDetails
            )
        );
    }

    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function setOrderLines(OrderLines | null $orderLines)
    {
        $this->orderLines = $orderLines;
    }

    public function setExtra(Extra | null $extra)
    {
        $this->extra = $extra;
    }

    public function setClient(ClientEntity | null $client)
    {
        $this->client = $client;
    }

    public function setWebhookUrl(string | null $webhookUrl)
    {
        $this->webhookUrl = $webhookUrl;
    }

    public function setReturnUrl(string | null $returnUrl)
    {
        $this->returnUrl = $returnUrl;
    }

    public function setMerchantOrderId($merchantOrderId)
    {
        $this->merchantOrderId = $merchantOrderId;
    }

    public function setDescription(string | null $description)
    {
        $this->description = $description;
    }

    public function setAdditionalProperties(mixed ...$additionalProperties)
    {
        $this->additionalProperties = $additionalProperties;
    }

    public function createOrder(Client | null $client) : Order
    {
        return $client->sendOrder(new Order(
                currency: $this->currency,
                amount: $this->amount,
                transactions: $this->transactions,
                customer: $this->customer,
                orderLines: $this->orderLines,
                extra: $this->extra,
                client: $this->client,
                webhook_url: $this->webhookUrl,
                return_url: $this->returnUrl,
                merchantOrderId: $this->merchantOrderId,
                description: $this->description,
                additionalProperties: $this->additionalProperties
            )
        );
    }
}