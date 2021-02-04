<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Enum\ReasonCode;
use Trunkrs\SDK\Enum\ShipmentStatusLabel;

/**
 * Class ShipmentLog
 */
class ShipmentLog {
    private static function applyV1(ShipmentLog $log, \stdClass $json) {
        $log->code = ShipmentStatusLabel::toShipmentStatus($json->label);
        $log->reason = $json->reasonCode;
    }

    private static function applyV2(ShipmentLog $log, \stdClass $json) {
        $log->code = $json->code;
        $log->reason = $json->reasonCode;
    }

    /**
     * @var string $code The state code for the current state.
     * @see ShipmentStatus
     */
    public $code;

    /**
     * @var string|null $reason The reason why this state was applied. Only applies to shipments in the SHIPMENT_NOT_DELIVERED state.
     * @see ReasonCode
     */
    public $reason;

    public function __construct($json = null)
    {
        if ($json) {
            switch (Settings::$apiVersion) {
                case 1:
                    self::applyV1($this, $json);
                    break;
                case 2:
                    self:self::applyV2($this, $json);
                    break;
            }
        }
    }
}