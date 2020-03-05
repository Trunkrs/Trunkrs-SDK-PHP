<?php

namespace Trunkrs\SDK\Exception;

use Throwable;

/**
 * Class GeneralApiException
 */
class GeneralApiException extends \Exception {
    private $_response;

    public function __construct($response)
    {
        parent::__construct(
            sprintf("The Trunkrs API responded with an unexpected answer of: %d", $response['status'])
        );

        $this->_response = $response;
    }

    /**
     * Gets the status code of the response.
     *
     * @return int The status code of the failed request.
     */
    public function getStatusCode(): int {
        return $this->_response['status'];
    }

    /**
     * Gets the headers of the request.
     *
     * @return array An associative array of the response headers.
     */
    public function getHeaders(): array {
        return $this->_response['headers'];
    }

    /**
     * Gets the response body as a string.
     *
     * @return string String representation of the statuscode.
     */
    public function getBody(): string {
        return $this->_response['body'];
    }
}