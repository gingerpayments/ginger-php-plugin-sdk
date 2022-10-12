<?php

namespace GingerPluginSdk\Builders;

use GingerPluginSdk\Builders\AbstractPaymentBuilder;
use GingerPluginSdk\Bases\Ideal;

class IdealBuilder extends AbstractPaymentBuilder
{
    /**
     * @var Ideal
     */
    public $paymentMethod;

    public function __construct()
    {
        $this->paymentMethod = new Ideal();
    }
}