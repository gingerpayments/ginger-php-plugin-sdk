<?php

namespace GingerPluginSdk\Entities;

use Cassandra\Date;
use GingerPluginSdk\Bases\BaseField;
use GingerPluginSdk\Collections\AdditionalAddresses;
use GingerPluginSdk\Collections\PhoneNumbers;
use GingerPluginSdk\Helpers\FieldsValidatorTrait;
use GingerPluginSdk\Helpers\MultiFieldsEntityTrait;
use GingerPluginSdk\Helpers\SingleFieldTrait;
use GingerPluginSdk\Interfaces\MultiFieldsEntityInterface;
use GingerPluginSdk\Properties\Country;
use GingerPluginSdk\Properties\Birthdate;
use GingerPluginSdk\Properties\EmailAddress;
use GingerPluginSdk\Properties\Locale;


final class Customer implements MultiFieldsEntityInterface
{
    use MultiFieldsEntityTrait;
    use FieldsValidatorTrait;
    use SingleFieldTrait;

    private string $propertyName = 'customer';

    private BaseField $lastName;
    private BaseField $firstName;
    private BaseField $gender;
    private BaseField $address;
    private BaseField $addressType;
    private BaseField $country;
    private BaseField $postalCode;
    private BaseField $houseNumber;
    private PhoneNumbers|null $phoneNumbers = null;
    private BaseField|null $merchantCustomerId = null;
    private BaseField|null $locale = null;
    private BaseField|null $ipAddress = null;

    /**
     * @param \GingerPluginSdk\Collections\AdditionalAddresses $additionalAddresses
     * @param string $firstName
     * @param string $lastName
     * @param EmailAddress $emailAddress
     * @param string $gender - Customer's gender
     * @param PhoneNumbers|null $phoneNumbers
     * @param string|null $merchantCustomerId - Merchant's internal customer identifier
     * @param \GingerPluginSdk\Properties\Birthdate|null $birthdate - Customer's birthdate (ISO 8601 / RFC 3339)
     * @param Locale|null $locale - POSIX locale or RFC 5646 language tag; only language and region are supported
     * @param \GingerPluginSdk\Properties\Country|null $country
     * @param string|null $ipAddress
     * @param string|null $address
     * @param string|null $addressType
     * @param string|null $postalCode
     * @param string|null $housenumber
     */
    public function __construct(
        private AdditionalAddresses $additionalAddresses,
        string                      $firstName,
        string                      $lastName,
        private EmailAddress        $emailAddress,
        string                      $gender,
        ?PhoneNumbers               $phoneNumbers = null,
        ?string                     $merchantCustomerId = null,
        private ?Birthdate          $birthdate = null,
        ?Locale                     $locale = null,
        ?Country                    $country = null,
        ?string                     $ipAddress = null,
        ?string                     $address = null,
        ?string                     $addressType = null,
        ?string                     $postalCode = null,
        ?string                     $housenumber = null
    )
    {
        $this->firstName = $this->createSimpleField(
            propertyName: 'first_name',
            value: $firstName
        );
        $this->lastName = $this->createSimpleField(
            propertyName: 'last_name',
            value: $lastName
        );
        $this->gender = $this->createEnumeratedField(
            propertyName: 'gender',
            value: $gender,
            enum: [
                'male', 'female'
            ]
        );
        $this->country = $country ?? new Country(
                $this->additionalAddresses->get()->getCountry()
            );

        if ($address) {
            $this->address = $this->createSimpleField(
                propertyName: 'address',
                value: $address
            );
        }

        if ($addressType) {
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
        }

        if ($postalCode) {
            $this->address = $this->createSimpleField(
                propertyName: 'postal_code',
                value: $postalCode
            );
        }

        if ($housenumber) {
            $this->address = $this->createSimpleField(
                propertyName: 'housenumber',
                value: $housenumber
            );
        }

        if ($phoneNumbers) $this->setPhoneNumbers($phoneNumbers);
        if ($merchantCustomerId) $this->setMerchantCustomerId($merchantCustomerId);
        if ($locale) $this->setLocale($locale);
        $this->setIpAddress($ipAddress);
    }

    public function getFirstName(): string
    {
        return $this->firstName->get();
    }

    public function getLastName(): string
    {
        return $this->lastName->get();
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress->get();
    }

    public function getAdditionalAddress(): array
    {
        return $this->additionalAddresses->toArray();
    }

    public function getBirthdate(): string
    {
        return $this->birthdate?->get();
    }

    public function getGender(): string
    {
        return $this->gender?->get();
    }

    public function getLocale(): ?string
    {
        return $this->locale?->get();
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress->get();
    }

    public function getMerchantCustomerId(): ?string
    {
        return $this->merchantCustomerId->get();
    }

    public function getPhoneNumbers(): array
    {
        return $this->phoneNumbers->toArray();
    }

    /**
     * @param Birthdate|null $date
     * @return $this
     */
    public function setBirthdate(?Birthdate $date): Customer
    {
        $this->birthdate = $date ?: null;
        return $this;
    }

    /**
     * @param \GingerPluginSdk\Properties\Locale|null $locale
     * @return $this
     */
    public function setLocale(?Locale $locale): Customer
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return $this
     */
    public function setIpAddress($ipAddress = null): Customer
    {
        $this->ipAddress = $this->createSimpleField(
            propertyName: "ip_address",
            value: $ipAddress ?? $_SERVER['REMOTE_ADDR']
        );
        return $this;
    }

    /**
     * @param string|null $id
     * @return $this
     */
    public function setMerchantCustomerId(?string $id): Customer
    {
        $this->merchantCustomerId = $this->createSimpleField(
            propertyName: 'merchant_customer_id',
            value: $id
        );
        return $this;
    }

    /**
     * @param PhoneNumbers $phoneNumbers
     * @return Customer
     */
    public function setPhoneNumbers(PhoneNumbers $phoneNumbers): Customer
    {
        $this->phoneNumbers = $phoneNumbers;
        return $this;
    }
}