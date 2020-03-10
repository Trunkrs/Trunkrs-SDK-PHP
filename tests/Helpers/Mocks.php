<?php

namespace Trunkrs\SDK;

use Faker\Factory;
use Faker\Generator;
use Trunkrs\SDK\Util\JsonDateTime;

class Mocks {
    private static $factory;

    public static function getFakeDetails(): ShipmentDetails {
        $details = new ShipmentDetails();
        $details->reference = uniqid();

        return $details;
    }

    public static function getFakeAddress(): Address {
        $address = new Address();

        $address->companyName = self::getGenerator()->company;
        $address->contactName = self::getGenerator()->name;
        $address->addressLine = self::getGenerator()->address;
        $address->postal = self::getGenerator()->postcode;
        $address->city = self::getGenerator()->city;
        $address->phone = self::getGenerator()->phoneNumber;
        $address->email = self::getGenerator()->email;
        $address->remarks = self::getGenerator()->sentence;

        return $address;
    }

    public static function getFakeTimeWindow(): TimeWindow {
        $window = new TimeWindow();
        $window->from = self::getGenerator()->dateTimeThisMonth;
        $window->to = self::getGenerator()->dateTimeThisMonth;

        return $window;
    }

    public static function getFakeTimeSlot(): TimeSlot {
        $timeSlot = new TimeSlot();
        $timeSlot->id = self::getGenerator()->randomNumber();
        $timeSlot->senderId = self::getGenerator()->randomNumber();
        $timeSlot->dataCutOff = self::getGenerator()->dateTimeThisMonth;
        $timeSlot->collectionWindow = self::getFakeTimeWindow();
        $timeSlot->deliveryWindow = self::getFakeTimeWindow();

        return $timeSlot;
    }

    public static function getFakeAddressBody(Address $address = null) {
        $actualAddress = $address
            ? $address
            : self::getFakeAddress();

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
            : self::getFakeTimeWindow();

        return (object)[
            "from" => JsonDateTime::to($actualWindow->from),
            "to" => JsonDateTime::to($actualWindow->to),
        ];
    }

    public static function getFakeTimeSlotBody(TimeSlot $timeSlot = null) {
        $actualTimeSlot = $timeSlot
            ? $timeSlot
            : self::getFakeTimeSlot();

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
                ? self::getGenerator()->randomNumber()
                : $shipmentId,
            "trunkrsNr" => $trunkrsNr
                ? $trunkrsNr
                : self::getTrunkrsNr(),
            "label" => $labelUrl
                ? $labelUrl
                : self::getGenerator()->url,
            "sender" => self::getFakeAddressBody($pickup),
            "recipient" => self::getFakeAddressBody($delivery),
            "timeSlot" => self::getFakeTimeSlotBody($timeSlot),
        ];
    }

    public static function getTrunkrsNr(): string {
        return self::getGenerator()->numberBetween(400000000, 500000000);
    }

    public static function getGenerator(): Generator {
        if(!self::$factory) {
            self::$factory = Factory::create();
        }
        return self::$factory;
    }
}