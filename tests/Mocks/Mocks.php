<?php

namespace Trunkrs\SDK;

use Faker\Factory;
use Faker\Generator;
use Trunkrs\SDK\Enum\MeasurementUnit;
use Trunkrs\SDK\Enum\ShipmentOwnerType;
use Trunkrs\SDK\Enum\ShipmentService;
use Trunkrs\SDK\Enum\ShipmentStatus;
use Trunkrs\SDK\Enum\ShipmentStatusLabel;
use Trunkrs\SDK\Enum\WebhookEvent;

class Mocks {
    private static $factory;

    public static function getFakeShipmentLog(): ShipmentLog {
        $log = new ShipmentLog();
        $log->reason = self::getGenerator()->word;
        $log->code = self::getGenerator()->randomElement([
            ShipmentStatus::DATA_PROCESSED,
            ShipmentStatus::SHIPMENT_SORTED,
            ShipmentStatus::SHIPMENT_SORTED_AT_SUB_DEPOT,
            ShipmentStatus::SHIPMENT_ACCEPTED_BY_DRIVER,
            ShipmentStatus::SHIPMENT_DELIVERED,
            ShipmentStatus::SHIPMENT_DELIVERED_TO_NEIGHBOR,
            ShipmentStatus::SHIPMENT_NOT_DELIVERED,
            ShipmentStatus::EXCEPTION_SHIPMENT_DECLINED_BY_DRIVER,
            ShipmentStatus::EXCEPTION_SHIPMENT_CANCELLED_BY_SENDER,
            ShipmentStatus::EXCEPTION_SHIPMENT_CANCELLED_BY_TRUNKRS,
            ShipmentStatus::EXCEPTION_SHIPMENT_MISS_SORTED,
            ShipmentStatus::EXCEPTION_SHIPMENT_NOT_ARRIVED,
        ]);

        return $log;
    }

    public static function getFakePackageOwner(): PackageOwner {
        $owner = new PackageOwner();
        $owner->type = self::getGenerator()->randomElement([
           ShipmentOwnerType::DRIVER,
           ShipmentOwnerType::MERCHANT,
           ShipmentOwnerType::NEIGHBOUR,
           ShipmentOwnerType::RECIPIENT,
           ShipmentOwnerType::SUBCONTRACTOR,
           ShipmentOwnerType::TRUNKRS,
        ]);
        $owner->name = self::getGenerator()->name;
        $owner->addressLine = self::getGenerator()->address;
        $owner->postal = self::getGenerator()->postcode;
        $owner->city = self::getGenerator()->city;
        $owner->country = self::getGenerator()->countryCode;

        return $owner;
    }

    public static function getFakeShipmentState(
        int $shipmentId = -1
    ): ShipmentState {
        $state = new ShipmentState();
        $state->shipmentId = $shipmentId == -1
            ? Mocks::getGenerator()->randomNumber()
            : $shipmentId;
        $state->timestamp = self::getGenerator()->dateTimeThisMonth;

        $state->state = self::getFakeShipmentLog();
        $state->owner = self::getFakePackageOwner();

        return $state;
    }

    public static function getFakeShipment(
        $nrParcels = 1
    ): Shipment {
        $shipment = new Shipment();
        $shipment->id = self::getGenerator()->numberBetween(10000, 100000000);
        $shipment->trunkrsNr = Mocks::getTrunkrsNr();
        $shipment->sender = Mocks::getFakeAddress();
        $shipment->recipient = Mocks::getFakeAddress();

        $shipment->parcels = array_fill(0, $nrParcels, NULL);
        $shipment->parcels = array_map(function () {
            return self::getFakeParcel();
        }, $shipment->parcels);

        $shipment->featureCodes = Mocks::getFakeFeatureCodes();
        $shipment->service = self::getRandomServiceLevel();
        $shipment->timeSlot = self::getFakeTimeSlot();
        $shipment->state = self::getFakeShipmentState();
        $shipment->label = self::getFakeLabelUrls();

        return $shipment;
    }

    public static function getFakeParcel(int $nrContentItems = 1): Parcel {
        $parcel = new Parcel();
        $parcel->reference = self::getGenerator()->uuid;
        $parcel->description = self::getGenerator()->sentence;
        $parcel->measurements = self::getFakeParcelMeasurements();

        $parcel->contents = array_fill(0, $nrContentItems, NULL);
        $parcel->contents = array_map(function () {
            return self::getFakeParcelContent();
        }, $parcel->contents);

        return $parcel;
    }

    public static function getRandomServiceLevel(): string {
        return self::getGenerator()->randomElement([
            ShipmentService::SAME_DAY,
            ShipmentService::SAME_DAY_FOOD,
            ShipmentService::SAME_DAY_FROZEN_FOOD,
        ]);
    }

    public static function getFakeDetails(int $nrParcels = 1): ShipmentDetails {
        $details = new ShipmentDetails();
        $details->timeSlotId = self::getGenerator()->randomNumber();
        $details->service = self::getRandomServiceLevel();
        $details->recipient = self::getFakeAddress();
        $details->sender = self::getFakeAddress();

        $details->parcels = array_fill(0, $nrParcels, NULL);
        $details->parcels = array_map(function () {
            return self::getFakeParcel();
        }, $details->parcels);

        return $details;
    }

    public static function getFakeParcelContent(): ParcelContent {
        $content = new ParcelContent();
        $content->reference = self::getGenerator()->ean8;
        $content->name = self::getGenerator()->colorName;
        $content->remarks = self::getGenerator()->sentence;

        return $content;
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

    public static function getFakeFeatureCodes(): FeatureCodes {
        $codes = new FeatureCodes();
        $codes->deliverInMailBox = self::getGenerator()->boolean;
        $codes->maxDeliveryAttempts = self::getGenerator()->numberBetween(1, 3);
        $codes->maxHoursOutsideFreezer = self::getGenerator()->numberBetween(6, 48);
        $codes->noNeighbourDelivery = self::getGenerator()->boolean;
        $codes->noSignature = self::getGenerator()->boolean;

        return $codes;
    }

    public static function getFakeLabelUrls(): LabelUrls {
        $urls = new LabelUrls();
        $urls->pdfUrl = self::getGenerator()->url;
        $urls->zplUrl = self::getGenerator()->url;

        return $urls;
    }

    public static function getFakeTimeWindow(): TimeWindow {
        $window = new TimeWindow();
        $window->from = self::getGenerator()->dateTimeThisMonth;
        $window->to = self::getGenerator()->dateTimeThisMonth;

        return $window;
    }

    public static function getFakeParcelMeasurements(): ParcelMeasurements {
        $measurements = new ParcelMeasurements();
        $measurements->weight = self::getFakeWeightMeasurement();
        $measurements->width = self::getFakeSizeMeasurement();
        $measurements->height = self::getFakeSizeMeasurement();
        $measurements->depth = self::getFakeSizeMeasurement();

        return $measurements;
    }

    public static function getFakeWeightMeasurement(): Measurement {
        $measurement = new Measurement();
        $measurement->value = self::getGenerator()->numberBetween(1, 100);
        $measurement->unit = self::getGenerator()->randomElement([
            MeasurementUnit::GRAMS,
            MeasurementUnit::KILOGRAMS,
        ]);

        return $measurement;
    }

    public static function getFakeSizeMeasurement(): Measurement {
        $measurement = new Measurement();
        $measurement->value = self::getGenerator()->numberBetween(1, 100);
        $measurement->unit = self::getGenerator()->randomElement([
            MeasurementUnit::CENTIMETERS,
            MeasurementUnit::METERS,
        ]);

        return $measurement;
    }

    public static function getFakeTimeSlot(): TimeSlot {
        $timeSlot = new TimeSlot();
        $timeSlot->id = self::getGenerator()->randomNumber();
        $timeSlot->senderId = self::getGenerator()->randomNumber();
        $timeSlot->sender = self::getFakeAddress();
        $timeSlot->dataCutOff = self::getGenerator()->dateTimeThisMonth;
        $timeSlot->collectionWindow = self::getFakeTimeWindow();
        $timeSlot->deliveryWindow = self::getFakeTimeWindow();
        $timeSlot->serviceArea = self::getFakeServiceArea();

        return $timeSlot;
    }

    public static function getFakeServiceArea(): ServiceArea {
        $area = new ServiceArea();
        $area->country = self::getGenerator()->countryCode;

        $area->region = array_fill(0, self::getGenerator()->numberBetween(3, 25), NULL);
        $area->region = array_map(function () {
            return self::getGenerator()->numberBetween(100, 999);
        }, $area->region);

        return $area;
    }

    public static function getFakeWebhook(
        string $event = null
    ): Webhook {
        $webhook = new Webhook();
        $webhook->id = self::getGenerator()->randomNumber();
        $webhook->callbackUrl = self::getGenerator()->url;
        $webhook->sessionToken = uniqid();
        $webhook->sessionHeaderName = uniqid();
        $webhook->event = $event ?? self::getGenerator()->randomElement([
            WebhookEvent::ON_CREATION,
            WebhookEvent::ON_CANCELLATION,
            WebhookEvent::ON_STATE_UPDATE,
        ]);
        $webhook->createdAt = self::getGenerator()->dateTimeThisMonth;

        return $webhook;
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