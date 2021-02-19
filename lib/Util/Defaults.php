<?php

namespace Trunkrs\SDK\Util;

use Trunkrs\SDK\Enum\MeasurementUnit;
use Trunkrs\SDK\Enum\ShipmentService;
use Trunkrs\SDK\FeatureCodes;
use Trunkrs\SDK\Measurement;

class Defaults
{
    /**
     * @internal
     */
    static function getDefaultService(): string {
        return ShipmentService::SAME_DAY;
    }

    /**
     * @internal
     */
    static function getDefaultFeatureCodes(): FeatureCodes {
        return new FeatureCodes();
    }

    /**
     * @internal
     */
    static function getDefaultWeight(): Measurement {
        $weight = new Measurement();
        $weight->value = 2;
        $weight->unit = MeasurementUnit::KILOGRAMS;

        return $weight;
    }
}