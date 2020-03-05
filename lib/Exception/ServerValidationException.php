<?php

namespace Trunkrs\SDK\Exception;

use Throwable;

class ServerValidationException extends \Exception {
    private $_message;

    public function __construct(array $response)
    {
        $json = json_decode($response['body']);

        parent::__construct(
            sprintf("Your payload did not match the expectation of the Trunkrs API\n\nValidation: %s", $json['message'])
        );

        $this->_message = $json['message'];
    }

    public function getValidationMessage(): string {
        return $this->_message;
    }
}