<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Util\JsonDateTime;

class MockV1Responses {
    public static function getFakeAddressBody(Address $address = null) {
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

    public static function getFakeTimeWindowBody(TimeWindow $timeWindow = null) {
        $actualWindow = $timeWindow
            ? $timeWindow
            : Mocks::getFakeTimeWindow();

        return (object)[
            "from" => JsonDateTime::to($actualWindow->from),
            "to" => JsonDateTime::to($actualWindow->to),
        ];
    }

    public static function getFakeTimeSlotBody(TimeSlot $timeSlot = null) {
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
        Address $pickup = null,
        Address $delivery = null,
        TimeSlot $timeSlot = null
    ) {
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
            "sender" => self::getFakeAddressBody($pickup),
            "recipient" => self::getFakeAddressBody($delivery),
            "timeSlot" => self::getFakeTimeSlotBody($timeSlot),
        ];
    }

    public static function getFakeShipmentLogBody(
        ShipmentLog $log = null
    ) {
        $actualLog = $log
            ? $log
            : Mocks::getFakeShipmentLog();

        return (object)[
            "id" => $actualLog->id,
            "label" => $actualLog->label,
            "name" => $actualLog->name,
            "status" => $actualLog->description,
            "reasonCode" => $actualLog->reason,
        ];
    }

    public static function getFakePackageOwnerBody(
        PackageOwner $owner = null
    ) {
        $actualOwner = $owner
            ? $owner
            : Mocks::getFakePackageOwner();

        return (object)[
            "type" => $actualOwner->type,
            "name" => $actualOwner->name,
            "address" => $actualOwner->addressLine,
            "postCode" => $actualOwner->postal,
            "city" => $actualOwner->city,
            "country" => $actualOwner->country,
        ];
    }

    public static function getShipmentStateBody(
        ShipmentState $state = null
    ) {
        $actualState = $state
            ? $state
            : Mocks::getFakeShipmentState();

        return (object)[
            "shipmentId" => $actualState->shipmentId,
            "timestamp" => JsonDateTime::to($actualState->timestamp),
            "stateObj" => self::getFakeShipmentLogBody($actualState->state),
            "currentOwner" => self::getFakePackageOwnerBody($actualState->owner),
        ];
    }
}