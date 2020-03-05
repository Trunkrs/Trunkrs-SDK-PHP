<?php

namespace Trunkrs\SDK\Enum;

class ShipmentStatusLabel {
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