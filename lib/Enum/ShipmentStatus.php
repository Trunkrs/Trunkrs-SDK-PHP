<?php


namespace Trunkrs\SDK\Enum;

class ShipmentStatus
{
    const DATA_RECEIVED = 'DATA_RECEIVED';
    const DATA_PROCESSED = 'DATA_PROCESSED';
    const SHIPMENT_SORTED = 'SHIPMENT_SORTED';
    const SHIPMENT_SORTED_AT_SUB_DEPOT = 'SHIPMENT_SORTED_AT_SUB_DEPOT';
    const SHIPMENT_ACCEPTED_BY_DRIVER = 'SHIPMENT_ACCEPTED_BY_DRIVER';
    const SHIPMENT_DELIVERED = 'SHIPMENT_DELIVERED';
    const SHIPMENT_DELIVERED_TO_NEIGHBOR = 'SHIPMENT_DELIVERED_TO_NEIGHBOR';
    const SHIPMENT_NOT_DELIVERED = 'SHIPMENT_NOT_DELIVERED';
    const EXCEPTION_SHIPMENT_NOT_ARRIVED = 'EXCEPTION_SHIPMENT_NOT_ARRIVED';
    const EXCEPTION_SHIPMENT_MISS_SORTED = 'EXCEPTION_SHIPMENT_MISS_SORTED';
    const EXCEPTION_SHIPMENT_DECLINED_BY_DRIVER = 'EXCEPTION_SHIPMENT_DECLINED_BY_DRIVER';
    const EXCEPTION_SHIPMENT_MISSING = 'EXCEPTION_SHIPMENT_MISSING';
    const EXCEPTION_SHIPMENT_LOST = 'EXCEPTION_SHIPMENT_LOST';
    const EXCEPTION_SHIPMENT_CANCELLED_BY_SENDER = 'EXCEPTION_SHIPMENT_CANCELLED_BY_SENDER';
    const EXCEPTION_SHIPMENT_CANCELLED_BY_TRUNKRS = 'EXCEPTION_SHIPMENT_CANCELLED_BY_TRUNKRS';
}