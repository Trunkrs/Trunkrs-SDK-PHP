<?php

namespace Trunkrs\SDK\Exception;

use Throwable;

/**
 * Class NotAuthorizedException
 */
class NotAuthorizedException extends \Exception {
    public function __construct()
    {
        parent::__construct("Your API credentials seem to be incorrect or have expired. Please contact Trunkrs support.");
    }
}