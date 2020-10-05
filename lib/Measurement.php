<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Enum\MeasurementUnit;

/**
 * Class Measurement
 * @package Trunkrs\SDK
 */
class Measurement
{
    private static function applyV2(Measurement $measurement, $json) {
        $measurement->value = $json->value;
        $measurement->unit = $json->unit;
    }

    private static function toV1Request(Measurement $measurement): string {
        return sprintf('%d %s', $measurement->value, $measurement->unit);
    }

    private static function toV2Request(Measurement $measurement): array {
        return [
          'value' => $measurement->value,
          'unit'=> $measurement->unit,
        ];
    }

    /**
     * @var string Value The measurement value.
     */
    public $value;

    /**
     * @see MeasurementUnit
     * @var string The measurement unit.
     */
    public $unit;

    public function __construct($json = null)
    {
        if ($json) {
            switch (Settings::$apiVersion) {
                case 2:
                    self::applyV2($this, $json);
                    break;
            }
        }
    }

    /**
     * @internal
     */
    function serialize() {
        switch (Settings::$apiVersion) {
            case 1:
                return self::toV1Request($this);
            case 2:
                return self::toV2Request($this);
        }
    }
}