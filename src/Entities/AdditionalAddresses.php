<?php

namespace GingerPluginSdk\Entities;

use GingerPluginSdk\Collections\AbstractCollection;

class AdditionalAddresses extends AbstractCollection
{
    public function __construct(Address ...$addresses)
    {
        $this->propertyName = 'additional_addresses';
        foreach ($addresses as $address) {
            $this->add($address);
        }
        parent::__construct(Address::class, $this->propertyName);
    }

    public function addAddress(Address $address): AdditionalAddresses
    {
        $this->add($address);
        return $this;
    }

    public function removeAddress($index): AdditionalAddresses
    {
        $this->remove($index);
        return $this;
    }
}