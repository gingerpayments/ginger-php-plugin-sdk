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
use JetBrains\PhpStorm\Pure;

final class Customer implements MultiFieldsEntityInterface
{
    use MultiFieldsEntityTrait;
    use FieldsValidatorTrait;
    use SingleFieldTrait;

    private string $propertyName = 'customer';
    /** ---Required--- */
    private BaseField $lastName;
    private BaseField $firstName;
    private BaseField $gender;
    /** -------------- */

    /** ---not-nullable--- */
    private string $address;
    private BaseField $addressType;
    private BaseField $country;
    private BaseField $postalCode;
    /** ------------------ */


    private PhoneNumbers|null $phoneNumbers = null;
    private BaseField|null $merchantCustomerId = null;
    private BaseField|null $locale = null;
    /** @var BaseField|null - Customer's IP address */
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
        ?string                     $ipAddress = null
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

        $this->setMerchantCustomerId($merchantCustomerId)
            ->setPhoneNumbers($phoneNumbers)
            ->setLocale($locale)
            ->setIpAddress($ipAddress);
    }

    #[Pure] public function getFirstName(): string
    {
        return $this->firstName->get();
    }

    #[Pure] public function getLastName(): string
    {
        return $this->lastName->get();
    }

    #[Pure] public function getEmailAddress(): string
    {
        return $this->email_address->get();
    }

    public function getAdditionalAddress(): array
    {
        return $this->additionalAddresses->toArray();
    }

    #[Pure] public function getBirthdate(): string
    {
        return $this->birthdate?->get();
    }

    #[Pure] public function getGender(): string
    {
        return $this->gender?->get();
    }

    #[Pure] public function getLocale(): ?string
    {
        return $this->locale?->get();
    }

    #[Pure] public function getIpAddress(): string
    {
        return $this->ipAddress->get();
    }

    #[Pure] public function getMerchantCustomerId(): ?string
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