<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Enum\WebhookEvent;
use Trunkrs\SDK\Util\JsonDateTime;

class MockV1Responses {
    public static function getFakeAddressBody(
        Address $address = null
    ): \stdClass {
        $actualAddress = $address
            ? $address
            : Mocks::getFakeAddress();

        return (object)[
            "name" => $actualAddress->companyName,
            "address" => $actualAddress->addressLine,
            "postCode" => $actualAddress->postal,
            "city" => $actualAddress->city,
            "country" => $actualAddress->country,
            "phoneNumber" => $actualAddress->phone,
            "email" => $actualAddress->email,
            "remarks" => $actualAddress->remarks,
        ];
    }

    public static function getFakeTimeWindowBody(
        TimeWindow $timeWindow = null
    ): \stdClass {
        $actualWindow = $timeWindow
            ? $timeWindow
            : Mocks::getFakeTimeWindow();

        return (object)[
            "from" => JsonDateTime::to($actualWindow->from),
            "to" => JsonDateTime::to($actualWindow->to),
        ];
    }

    public static function getFakeTimeSlotBody(
        TimeSlot $timeSlot = null
    ): \stdClass {
        $actualTimeSlot = $timeSlot
            ? $timeSlot
            : Mocks::getFakeTimeSlot();

        return (object)[
            "id" => $actualTimeSlot->id,
            "senderId" => $actualTimeSlot->senderId,
            "dataWindow" => JsonDateTime::to($actualTimeSlot->dataCutOff),
            "deliveryWindow" => self::getFakeTimeWindowBody($actualTimeSlot->deliveryWindow),
            "collectionWindow" => self::getFakeTimeWindowBody($actualTimeSlot->collectionWindow),
        ];
    }

    public static function getFakeShipmentBody(
        int $shipmentId = -1,
        string $trunkrsNr = null,
        string $labelUrl = null,
        ShipmentDetails $details = null,
        TimeSlot $timeSlot = null
    ): \stdClass {
        $actualDetails = $details ?? Mocks::getFakeDetails();

        return (object)[
            "shipmentId" => $shipmentId == -1
                ? Mocks::getGenerator()->randomNumber()
                : $shipmentId,
            "trunkrsNr" => $trunkrsNr
                ? $trunkrsNr
                : Mocks::getTrunkrsNr(),
            "label" => $labelUrl
                ? $labelUrl
                : Mocks::getGenerator()->url,
            "sender" =>  self::getFakeAddressBody($actualDetails->sender),
            "recipient" => self::getFakeAddressBody($actualDetails->recipient),
            "timeSlot" => self::getFakeTimeSlotBody($timeSlot),
        ];
    }

    public static function getFakeShipmentLogBody(
        ShipmentLog $log = null
    ): \stdClass {
        $actualLog = $log ?? Mocks::getFakeShipmentLog();

        return (object)[
            "label" => $actualLog->code,
            "reasonCode" => $actualLog->reason,
        ];
    }

    public static function getFakeParcelMeasurementsBody(
        ParcelMeasurements $measurements = null
    ): \stdClass {
        $actualMeasurements = $measurements ?? Mocks::getFakeParcelMeasurements();

        return (object) [
            'width' => self::getFakeMeasurementBody($actualMeasurements->width),
            'height' => self::getFakeMeasurementBody($actualMeasurements->height),
            'volume' => self::getFakeMeasurementBody($actualMeasurements->depth),
            'weight' => self::getFakeMeasurementBody($actualMeasurements->weight),
        ];
    }

    public static function getFakeMeasurementBody(
        Measurement $measurement = null
    ): string {
        $actualMeasurement = $measurement ?? Mocks::getFakeSizeMeasurement();

        return sprintf('%d %s', $actualMeasurement->value, $actualMeasurement->unit);
    }

    public static function getFakePackageOwnerBody(
        PackageOwner $owner = null
    ): \stdClass {
        $actualOwner = $owner ?? Mocks::getFakePackageOwner();

        return (object)[
            "type" => $actualOwner->type,
            "name" => $actualOwner->name,
            "address" => $actualOwner->addressLine,
            "postCode" => $actualOwner->postal,
            "city" => $actualOwner->city,
            "country" => $actualOwner->country,
        ];
    }

    public static function getFakeWebhookBody(
        Webhook $webhook = null
    ): \stdClass {
        $actualWebhook = $webhook ?? Mocks::getFakeWebhook();

        return (object)[
            'id' => $actualWebhook->id,
            'url' => $actualWebhook->callbackUrl,
            'key' => $actualWebhook->sessionHeaderName,
            'token' => $actualWebhook->sessionToken,
            'uponShipmentCreation' => $actualWebhook->event == WebhookEvent::ON_CREATION,
            'uponLabelReady' => false,
            'uponStatusUpdate' => $actualWebhook->event == WebhookEvent::ON_STATE_UPDATE,
            'uponShipmentCancellation' => $actualWebhook->event == WebhookEvent::ON_CANCELLATION,
            'createdAt' => JsonDateTime::to($actualWebhook->createdAt),
        ];
    }

    public static function getShipmentStateBody(
        ShipmentState $state = null
    ): \stdClass {
        $actualState = $state ?? Mocks::getFakeShipmentState();

        return (object)[
            "shipmentId" => $actualState->shipmentId,
            "timestamp" => JsonDateTime::to($actualState->timestamp),
            "stateObj" => self::getFakeShipmentLogBody($actualState->state),
            "currentOwner" => self::getFakePackageOwnerBody($actualState->owner),
        ];
    }
}