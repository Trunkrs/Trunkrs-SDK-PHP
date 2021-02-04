<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Util\SerializableInterface;

class Address implements SerializableInterface {
    private static function applyV1(Address $address, $json) {
        $address->contactName = $json->name;
        $address->addressLine = $json->address;
        $address->postal = $json->postCode;
        $address->city = $json->city;
        $address->country = $json->country;
        $address->phone = $json->phoneNumber;
        $address->email = $json->email;
        $address->remarks = $json->remarks;
    }

    private static function applyV2(Address $address, $json) {
        $address->companyName = $json->companyName;
        $address->contactName = $json->name;
        $address->addressLine = $json->address;
        $address->postal = $json->postalCode;
        $address->city = $json->city;
        $address->country = $json->country;
        $address->phone = $json->phoneNumber;
        $address->email = $json->emailAddress;
        $address->remarks = $json->additionalRemarks;
    }

    private static function toV1Request(string $prefix, Address $address): array {
        return [
            $prefix . 'Name' => $address->companyName,
            $prefix . 'Contact' => $address->contactName,
            $prefix . 'Address' => $address->addressLine,
            $prefix . 'City' => $address->city,
            $prefix . 'PostCode' => $address->postal,
            $prefix . 'Country' => $address->country,
            $prefix . 'Email' => $address->email,
            $prefix . 'Tell' => $address->phone,
            $prefix . 'Remarks' => $address->remarks,
        ];
    }

    private static function toV2Request(Address $address): array {
        return [
            'companyName' => $address->companyName,
            'name' => $address->contactName,
            'emailAddress' => $address->email,
            'phoneNumber' => $address->phone,
            'address' => $address->addressLine,
            'postalCode' => $address->postal,
            'city' => $address->city,
            'country' => $address->country,
            'additionalRemarks' => $address->remarks,
        ];
    }

    /**
     * @var string $companyName The company name or general name of the address.
     */
    public $companyName;

    /**
     * @var string $contactName The name of the location contact.
     */
    public $contactName;

    /**
     * @var string $addressLine Address line for the address.
     */
    public $addressLine;

    /**
     * @var string $city The city of the address.
     */
    public $city;

    /**
     * @var string $postal The postal code of the address.
     */
    public $postal;

    /**
     * @var string $country The country of the address.
     */
    public $country = 'NL';
    /**
     * @var string $email The contact email address for the address.
     */
    public $email;
    /**
     * @var string $phone The contact phone number for the address.
     */
    public $phone;
    /**
     * @var string Any remarks for the pickup or delivery on this address.
     */
    public $remarks = '';

    /**
     * Address constructor.
     * @param array|null $json An optional associative array for parsing the timeslot.
     */
    public function __construct($json = null)
    {
        if ($json) {
            switch (Settings::$apiVersion) {
                case 1:
                    self::applyV1($this, $json);
                    break;
                case 2:
                    self::applyV2($this, $json);
                    break;
            }
        }
    }

    /**
     * @param string $prefix Prefix needed for v1 properties.
     * @return array
     * @internal
     */
    function serialize(string $prefix = null): array {
        switch (Settings::$apiVersion) {
            case 1:
                return self::toV1Request($prefix, $this);
            case 2:
                return self::toV2Request($this);
        }
    }
}