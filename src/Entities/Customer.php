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
     * @param string|null $gender - Customer's gender
     * @param PhoneNumbers|null $phoneNumbers
     * @param \GingerPluginSdk\Properties\Birthdate|null $birthdate - Customer's birthdate (ISO 8601 / RFC 3339)
     * @param \GingerPluginSdk\Properties\Country|null $country
     * @param string|null $ipAddress
     * @param string|null $addressType
     * @param mixed ...$additionalProperties
     */
    public function __construct(
        private AdditionalAddresses $additionalAddresses,
        string                      $firstName,
        string                      $lastName,
        private EmailAddress        $emailAddress,
        ?string                     $gender = null,
        ?PhoneNumbers               $phoneNumbers = null,
        private ?Birthdate          $birthdate = null,
        ?Country                    $country = null,
        ?string                     $ipAddress = null,
        ?string                     $addressType = null,
        mixed                       ...$additionalProperties
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

        $this->country = $country ?? new Country(
                $this->additionalAddresses->get()->getCountry()
            );

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

        if ($gender) {
            $this->gender = $this->createEnumeratedField(
                propertyName: 'gender',
                value: $gender,
                enum: [
                    'male', 'female'
                ]
            );
        }
        if ($additionalProperties) $this->filterAdditionalProperties($additionalProperties);

        if ($phoneNumbers) $this->setPhoneNumbers($phoneNumbers);
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
        return $this->birthdate ? $this->birthdate->get() : false;
    }

    public function getGender(): string
    {
        return $this->gender ? $this->birthdate->get() : false;
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
     * @param PhoneNumbers $phoneNumbers
     * @return Customer
     */
    public function setPhoneNumbers(PhoneNumbers $phoneNumbers): Customer
    {
        $this->phoneNumbers = $phoneNumbers;
        return $this;
    }
}