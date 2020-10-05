<?php

namespace Trunkrs\SDK;

class MockV2Responses {
    public static function getFakeAddressBody(Address $address = null) {
        $actualAddress = $address ?? Mocks::getFakeAddress();

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

    public static function getFakeParcelBody(Parcel $parcel = null) {
        $actualParcel = $parcel ?? Mocks::getFakeParcel();

        return [
            "reference" => $actualParcel->reference,
            "description" => $actualParcel->description,
            "contents" => array_map(function ($contentItem) {
                return self::getFakeContentItemBody($contentItem);
            }, $actualParcel->contents),
            "weight" => self::getFakeMeasurementBody($actualParcel->measurements->weight),
            "size" => [
                "width" => self::getFakeMeasurementBody($actualParcel->measurements->width),
                "height" => self::getFakeMeasurementBody($actualParcel->measurements->height),
                "depth" => self::getFakeMeasurementBody($actualParcel->measurements->depth),
            ],
        ];
    }

    public static function getFakeContentItemBody(ParcelContent $content) {
        $actualContent = $content ?? Mocks::getFakeParcelContent();

        return [
            "name" => $actualContent->name,
            "reference" => $actualContent->reference,
            "additionalRemarks" => $actualContent->remarks,
        ];
    }

    public static function getFakeMeasurementBody(Measurement $measurement) {
        $actualMeasurement = $measurement ?? Mocks::getFakeSizeMeasurement();

        return [
            "unit" => $actualMeasurement->unit,
            "value" => $actualMeasurement->value,
        ];
    }
}