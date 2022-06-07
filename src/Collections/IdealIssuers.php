<?php

namespace GingerPluginSdk\Collections;

use GingerPluginSdk\Entities\Issuer;

class IdealIssuers extends AbstractCollection
{
    const ITEM_TYPE = Issuer::class;

    public function __construct(Issuer ...$items)
    {
        $this->propertyName = 'issuers';
        foreach ($items as $item) {
            $this->add($item);
        }
        parent::__construct($this->propertyName);
    }

    public function addIssuer(Issuer $item)
    {
        $this->add($item);
    }

    public function removeIssuer($index)
    {
        $this->remove($index);
    }
}