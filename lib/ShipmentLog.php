<?php

namespace Trunkrs\SDK;

/**
 * Class ShipmentLog
 */
class ShipmentLog {
    private static function applyV1(ShipmentLog $log, array $json) {
        $log->id = $json['id'];
        $log->label = $json['label'];
        $log->name = $json['name'];
        $log->description = $json['status'];
        $log->reason = $json['reasonCode'];
    }

    /**
     * @var int $id The numeric state identifier.
     */
    public $id;

    /**
     * @see ShipmentStatusLabel
     * @var string $label The state identifier label.
     */
    public $label;

    /**
     * @var string $name The name of the shipment state.
     */
    public $name;

    /**
     * @var string $description The human description of the shipment state.
     */
    public $description;

    /**
     * @var string|null $reason The reason why this state was applied. Only applies to shipments in the SHIPMENT_NOT_DELIVERED state.
     */
    public $reason;

    public function __construct(array $json = null)
    {
        if ($json) {
            switch (Settings::$apiVersion) {
                case 1:
                    self::applyV1($this, $json);
                    break;
            }
        }
    }
}