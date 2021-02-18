<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Util\JsonDateTime;

class MockV2Responses {
    public static function getFakeShipmentBody(
        Shipment $shipment = null
    ): \stdClass {
        $actualShipment = $shipment ?? Mocks::getFakeShipment();

        return (object) [
            'trunkrsNr' => $actualShipment->trunkrsNr,
            'label' => self::getFakeLabelUrlsBody($actualShipment->label),
            'sender' => self::getFakeAddressBody($actualShipment->sender),
            'recipient' => self::getFakeAddressBody($actualShipment->recipient),
            'parcels' => array_map(function (Parcel $parcel) {
                return self::getFakeParcelBody($parcel);
            }, $actualShipment->parcels),
            'timeSlot' => self::getFakeTimeSlotBody($actualShipment->timeSlot),
            'state' => self::getFakeShipmentStateBody($actualShipment->state),
            'featureCodes' => self::getFakeFeatureCodesBody($actualShipment->featureCodes),
            'service' => $actualShipment->service,
        ];
    }

    public static function getFakeShipmentFromDetailsBody(
        string $trunkrsNr,
        string $serviceLevel,
        LabelUrls $labelUrls,
        ShipmentState $state,
        TimeSlot $timeSlot,
        FeatureCodes $codes,
        ShipmentDetails $details
    ): \stdClass {
        return (object) [
            'trunkrsNr' => $trunkrsNr,
            'label' => self::getFakeLabelUrlsBody($labelUrls),
            'sender' => self::getFakeAddressBody($details->sender),
            'recipient' => self::getFakeAddressBody($details->recipient),
            'parcels' => array_map(function (Parcel $parcel) {
                return self::getFakeParcelBody($parcel);
            }, $details->parcels),
            'timeSlot' => self::getFakeTimeSlotBody($timeSlot),
            'state' => self::getFakeShipmentStateBody($state),
            'featureCodes' => self::getFakeFeatureCodesBody($codes),
            'service' => $serviceLevel,
        ];
    }

    public static function getFakeAddressBody(Address $address = null): \stdClass {
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

    public static function getFakeParcelBody(Parcel $parcel = null): \stdClass {
        $actualParcel = $parcel ?? Mocks::getFakeParcel();

        $parcelPart = [
            "reference" => $actualParcel->reference,
            "description" => $actualParcel->description,
            "contents" => array_map(function ($contentItem) {
                return self::getFakeContentItemBody($contentItem);
            }, $actualParcel->contents),
        ];
        $measurementPart = (array)self::getFakeParcelMeasurementsBody($actualParcel->measurements);

        return (object)array_merge($parcelPart, $measurementPart);
    }

    public static function getFakeContentItemBody(
        ParcelContent $content = null
    ): \stdClass {
        $actualContent = $content ?? Mocks::getFakeParcelContent();

        return (object)[
            "name" => $actualContent->name,
            "reference" => $actualContent->reference,
            "additionalRemarks" => $actualContent->remarks,
        ];
    }

    public static function getFakeMeasurementBody(
        Measurement $measurement = null
    ): \stdClass {
        $actualMeasurement = $measurement ?? Mocks::getFakeSizeMeasurement();

        return (object)[
            "unit" => $actualMeasurement->unit,
            "value" => $actualMeasurement->value,
        ];
    }

    public static function getFakeParcelMeasurementsBody(
        ParcelMeasurements $measurements = null
    ): \stdClass {
        $actualMeasurements = $measurements ?? Mocks::getFakeParcelMeasurements();

        return (object)[
            "weight" => self::getFakeMeasurementBody($actualMeasurements->weight),
            "size" => (object)[
                "width" => self::getFakeMeasurementBody($actualMeasurements->width),
                "height" => self::getFakeMeasurementBody($actualMeasurements->height),
                "depth" => self::getFakeMeasurementBody($actualMeasurements->depth),
            ],
        ];
    }

    public static function getFakeFeatureCodesBody(
        FeatureCodes $codes = null
    ): \stdClass {
        $actualCodes = $codes ?? Mocks::getFakeFeatureCodes();

        return (object)[
            "noNeighbourDelivery" => $actualCodes->noNeighbourDelivery,
            "noSignature" => $actualCodes->noSignature,
            "deliverInMailBox" => $actualCodes->deliverInMailBox,
            "maxDeliveryAttempts" => $actualCodes->maxDeliveryAttempts,
            "maxTimeOutsideFreezer" => $actualCodes->maxHoursOutsideFreezer,
        ];
    }

    public static function getFakeShipmentLogBody(
        ShipmentLog $log = null
    ): \stdClass {
        $actualLog = $log ?? Mocks::getFakeShipmentLog();

        return (object) [
            'code' => $actualLog->code,
            'reasonCode' => $actualLog->reason,
        ];
    }

    public static function getFakeLabelUrlsBody(
        LabelUrls $urls = null
    ): \stdClass {
        $actualUrls = $urls ?? Mocks::getFakeLabelUrls();

        return (object) [
            'pdf' => $actualUrls->pdfUrl,
            'zpl' => $actualUrls->zplUrl,
        ];
    }

    public static function getFakeShipmentStateBody(
        ShipmentState $state = null
    ): \stdClass {
        $actualState = $state ?? Mocks::getFakeShipmentState();

        return (object) [
            'code' => $actualState->state->code,
            'reasonCode' => $actualState->state->reason,
            'timestamp' => JsonDateTime::to($actualState->timestamp),
            'currentOwner' => self::getFakePackageOwnerBody($actualState->owner),
        ];
    }

    public static function getFakePackageOwnerBody(
        PackageOwner $owner = null
    ): \stdClass {
        $actualOwner = $owner ?? Mocks::getFakePackageOwner();

        return (object) [
            'type' => $actualOwner->type,
            'name' => $actualOwner->name,
            'address' => $actualOwner->addressLine,
            'postalCode' => $actualOwner->postal,
            'city' => $actualOwner->city,
            'country' => $actualOwner->country,
        ];
    }

    public static function getFakeTimeSlotBody(
        TimeSlot $timeSlot = null
    ): \stdClass {
        $actualTimeSlot = $timeSlot ?? Mocks::getFakeTimeSlot();

        return (object) [
            'id' => $actualTimeSlot->id,
            'merchant' => self::getFakeAddressBody($actualTimeSlot->sender),
            'cutOffTime' => JsonDateTime::to($actualTimeSlot->dataCutOff),
            'collectionWindow' => self::getFakeTimeWindowBody($actualTimeSlot->collectionWindow),
            'deliveryWindow' => self::getFakeTimeWindowBody($actualTimeSlot->deliveryWindow),
            'serviceArea' => self::getFakeServiceAreaBody($actualTimeSlot->serviceArea),
        ];
    }

    public static function getFakeTimeWindowBody(
        TimeWindow $window = null
    ): \stdClass {
        $actualWindow = $window ?? Mocks::getFakeTimeWindow();

        return (object) [
            'start' => JsonDateTime::to($actualWindow->from),
            'end' => JsonDateTime::to($actualWindow->to),
        ];
    }

    public static function getFakeServiceAreaBody(
        ServiceArea $area = null
    ): \stdClass {
        $actualArea = $area ?? Mocks::getFakeServiceArea();

        return (object) [
            'country' => $actualArea->country,
            'region' => $actualArea->region,
        ];
    }

    public static function getFakeWebhookBody(
        Webhook $hook = null
    ): \stdClass {
        $actualHook = $hook ?? Mocks::getFakeWebhook();

        return (object) [
            'id' => $actualHook->id,
            'url' => $actualHook->callbackUrl,
            'header' => (object) [
                'key' => $actualHook->sessionHeaderName,
                'token' => $actualHook->sessionToken,
            ],
            'event' => $actualHook->event,
        ];
    }
}