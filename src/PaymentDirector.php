<?php

namespace GingerPluginSdk;

use GingerPluginSdk\Builders\IdealBuilder;
use GingerPluginSdk\Collections\OrderLines;
use GingerPluginSdk\Entities\Customer;
use GingerPluginSdk\Entities\Extra;
use GingerPluginSdk\Entities\Order;
use GingerPluginSdk\Properties\RawCost;
use GingerPluginSdk\Entities\Client as ClientEntity;
use GingerPluginSdk\Client;

class PaymentDirector
{
    public function makeIdealPayment(
        Client          $client,
        IdealBuilder    $builder,
        string          $currency,
        RawCost         $amount,
        Customer        $customer,
        $issuerId                        = null,
        $verifiedTerms                   = null,
        OrderLines      $orderLines      = null,
        Extra           $extra           = null,
        ClientEntity    $clientEntity    = null,
        string          $webhookUrl      = null,
        string          $returnUrl       = null,
        string          $merchantOrderId = null,
        string          $description     = null,
        mixed           ...$additionalProperties

    ) : Order {
        $builder->setCurrency($currency);
        $builder->setAmount($amount);
        $builder->setPaymentMethodDetails($issuerId, $verifiedTerms);
        $builder->setTransactions($builder->paymentMethod::PAYMENT_NAME);
        $builder->setCustomer($customer);
        $builder->setOrderLines($orderLines);
        $builder->setExtra($extra);
        $builder->setClient($clientEntity);
        $builder->setWebhookUrl($webhookUrl);
        $builder->setReturnUrl($returnUrl);
        $builder->setMerchantOrderId($merchantOrderId);
        $builder->setDescription($description);
        $builder->setAdditionalProperties($additionalProperties);


        return $builder->createOrder($client);
    }
}