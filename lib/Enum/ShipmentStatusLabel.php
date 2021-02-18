<?php

namespace Trunkrs\SDK\Enum;

class ShipmentStatusLabel {
    /**
     * Converts the status label to the V2 compliant shipment status codes.
     * @param string $label The shipment status label.
     * @return string The shipment status.
     * @internal
     */
    public static function toShipmentStatus(string $label): string {
        switch ($label) {
            case ShipmentStatusLabel::DATA_RECEIVED:
                return ShipmentStatus::DATA_RECEIVED;
            case ShipmentStatusLabel::SORTED_HUB:
                return ShipmentStatus::SHIPMENT_SORTED;
            case ShipmentStatusLabel::SORTED_DEPOT;
                return ShipmentStatus::SHIPMENT_SORTED_AT_SUB_DEPOT;
            case ShipmentStatusLabel::OUT_FOR_DELIVERY:
                return ShipmentStatus::SHIPMENT_ACCEPTED_BY_DRIVER;
            case ShipmentStatusLabel::DELIVERED:
                return ShipmentStatus::SHIPMENT_DELIVERED;
            case ShipmentStatusLabel::DELIVERED_NEIGHBOR:
                return ShipmentStatus::SHIPMENT_DELIVERED_TO_NEIGHBOR;
            case ShipmentStatusLabel::NOT_DELIVERED:
                return ShipmentStatus::SHIPMENT_NOT_DELIVERED;
            case ShipmentStatusLabel::DECLINED_DRIVER:
                return ShipmentStatus::EXCEPTION_SHIPMENT_DECLINED_BY_DRIVER;
            case ShipmentStatusLabel::CANCELLED:
                return ShipmentStatus::EXCEPTION_SHIPMENT_CANCELLED_BY_SENDER;
            default:
                return $label;
        }
    }

    const DATA_RECEIVED = "RC";
    const SORTED_HUB = "MC";
    const SORTED_DEPOT = "AC";
    const OUT_FOR_DELIVERY = "CM";
    const DELIVERED = "OK";
    const DELIVERED_NEIGHBOR = "DN";
    const NOT_DELIVERED = "ND";
    const DECLINED_DRIVER = "NA";
    const CANCELLED = "CA";
}