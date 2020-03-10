<?php

namespace Trunkrs\SDK\Exception;

use Throwable;

class ServerValidationException extends \Exception {
    private $_message;

    public function __construct(array $response)
    {
        $json = array_key_exists("body", $response)
            ? json_decode($response["body"])
            : null;

        $this->_message = $json
            ? $json->message
            : "No validation message specified.";

        parent::__construct(
            sprintf("Your payload did not match the expectation of the Trunkrs API\n\nValidation: %s", $this->_message)
        );
    }

    public function getValidationMessage(): string {
        return $this->_message;
    }
}