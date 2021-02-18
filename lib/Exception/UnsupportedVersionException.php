<?php

namespace Trunkrs\SDK\Exception;

use Throwable;

/**
 * Class UnsupportedVersionException
 */
class UnsupportedVersionException extends \Exception {
    public function __construct($requested, $supported)
    {
        $message = sprintf(
            "You requested an invalid API version '%d'. Supported API versions are: %s.",
            $requested,
            join(', ', $supported)
        );

        parent::__construct($message);
    }
}