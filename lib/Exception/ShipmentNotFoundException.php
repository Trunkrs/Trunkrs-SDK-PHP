<?php

namespace Trunkrs\SDK\Exception;

class ShipmentNotFoundException extends \Exception {
    /**
     * ShipmentNotFoundException constructor.
     * @param string $shipmentId The shipment identifier.
     */
    public function __construct($shipmentId)
    {
        parent::__construct(sprintf("The shipment with id '%d' couldn't be found.", $shipmentId));
    }
}