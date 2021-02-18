<?php

namespace Trunkrs\SDK\Util;

use Trunkrs\SDK\Settings;

class ResultUnwrapper
{
    public static function unwrap($result, $default = []) {
        switch (Settings::$apiVersion) {
            case 1:
                return $result ?? $default;
            case 2:
                return $result->data ?? $default;
        }
    }
}