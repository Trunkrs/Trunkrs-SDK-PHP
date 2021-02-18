<?php

namespace Trunkrs\SDK\Util;

use Trunkrs\SDK\Enum\ShipmentService;
use Trunkrs\SDK\FeatureCodes;

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
}