<?php

declare(strict_types=1);

namespace GingerPluginSdk\Entities;

use GingerPluginSdk\Helpers\MultiFieldsEntityTrait;
use GingerPluginSdk\Helpers\SingleFieldTrait;
use GingerPluginSdk\Helpers\SyncUpSchemasTrait;
use GingerPluginSdk\Interfaces\MultiFieldsEntityInterface;
use GingerPluginSdk\Bases\BaseField;
use GingerPluginSdk\Properties\Country;
use JetBrains\PhpStorm\Pure;

final class Address implements MultiFieldsEntityInterface
{
    use SingleFieldTrait;
    use MultiFieldsEntityTrait;
    use SyncUpSchemasTrait;

    private BaseField $addressType;
    private BaseField $postalCode;
    private BaseField $country;
    private BaseField $city;
    private BaseField $street;
    private BaseField $address;
    private BaseField|null $housenumber = null;

    /**
     * @param string $addressType
     * @param string $postalCode
     * @param string $street
     * @param string $city
     * @param Country $country - ISO 3166-1 alpha-2 country code
     * @param string|null $propertyName
     * @param string|null $housenumber
     */
    public function __construct(
        string  $addressType,
        string  $postalCode,
        Country $country,
        ?string $street = null,
        ?string $city = null,
        ?string $propertyName = null,
        ?string $address = null,
        ?string $housenumber = null
    )
    {
        $this->addressType = $this->createEnumeratedField(
            propertyName: 'address_type',
            value: $addressType,
            // enum: $this->getJsonSchemaFromAPI('order')
            enum: [
                'customer',
                'billing',
                'delivery'
            ]
        );
        $this->postalCode = $this->createSimpleField(
            propertyName: 'postal_code',
            value: $postalCode
        );
        $this->street = $this->createSimpleField(
            propertyName: 'street',
            value: $street
        );
        $this->city = $this->createSimpleField(
            propertyName: "city",
            value: $city
        );
        $this->country = $country;

        if ($housenumber) $this->setHousenumber($housenumber);

        if ($address) {
            $this->address = $this->createSimpleField(
                propertyName: 'address',
                value: $address
            );
        } else {
            $this->setAddressLine();
        };

        if ($propertyName) $this->propertyName = $propertyName;
    }

    public function setPropertyName($name): Address
    {
        $this->propertyName = $name;
        return $this;
    }

    #[Pure] public function getAddressType(): string
    {
        return $this->addressType->get();
    }

    #[Pure] public function getPostalCode(): string
    {
        return $this->postalCode->get();
    }

    #[Pure] public function getCountry(): string
    {
        return $this->country->get();
    }

    #[Pure] public function getCity(): ?string
    {
        return $this->city->get();
    }

    #[Pure] public function getStreet(): ?string
    {
        return $this->street->get();
    }

    #[Pure] public function getHousenumber(): ?string
    {
        return $this->housenumber?->get();
    }

    /**
     * @param string|null $value
     * @return $this
     */
    public function setHousenumber(?string $value): Address
    {
        $this->housenumber = $this->createSimpleField(
            propertyName: 'housenumber',
            value: $value
        );
        $this->setAddressLine();
        return $this;
    }

    public function setAddressLine()
    {
        $this->address = $this->createSimpleField(
            propertyName: 'address',
            value: $this->generateAddress()
        );
    }

    #[Pure] public function getAddressLine(): string
    {
        return $this->address->get();
    }

    public function generateAddress(): string
    {
        return implode(' ', array_filter([
            $this->getStreet(),
            $this->getHousenumber(),
            $this->getPostalCode(),
            $this->getCity(),
        ]));
    }
}