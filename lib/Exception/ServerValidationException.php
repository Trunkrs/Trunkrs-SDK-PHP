<?php

namespace Trunkrs\SDK\Exception;

use Trunkrs\SDK\Settings;

class ServerValidationException extends \Exception {
    private $_message;

    public function __construct(array $response)
    {
        $json = array_key_exists("body", $response)
            ? json_decode($response["body"])
            : null;

        switch (Settings::$apiVersion) {
            case 1:
                $this->_message = $json
                    ? $json->message
                    : "No validation message specified.";
                break;
            case 2:
                $this->_message = $json
                    ? $json->reason
                    : "No validation message specified.";
                break;
        }

        parent::__construct(
            sprintf("Your payload did not match the expectation of the Trunkrs API\n\nValidation: %s", $this->_message)
        );
    }

    public function getValidationMessage(): string {
        return $this->_message;
    }
}