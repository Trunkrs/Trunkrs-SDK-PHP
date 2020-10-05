<?php

namespace Trunkrs\SDK;

class MockV2Responses {
    public static function getFakeAddressBody(Address $address = null) {
        $actualAddress = $address
            ? $address
            : Mocks::getFakeAddress();

        return (object)[
            "companyName" => $actualAddress->companyName,
            "name" => $actualAddress->contactName,
            "address" => $actualAddress->addressLine,
            "postalCode" => $actualAddress->postal,
            "city" => $actualAddress->city,
            "country" => $actualAddress->country,
            "phoneNumber" => $actualAddress->phone,
            "emailAddress" => $actualAddress->email,
            "additionalRemarks" => $actualAddress->remarks,
        ];
    }


}