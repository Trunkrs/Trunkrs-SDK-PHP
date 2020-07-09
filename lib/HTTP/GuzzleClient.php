<?php

namespace Trunkrs\SDK\HTTP;

use GuzzleHttp\Client;

class GuzzleClient implements HttpClientInterface {
    private static function hasRequestBody($method): bool {
        return $method == 'POST'
            || $method == 'PUT'
            || $method == 'PATCH';
    }

    private static function handleResponse($response): array {
        return [
            'status' => $response->getStatusCode(),
            'headers' => $response->getHeaders(),
            'body' => $response->getBody(),
        ];
    }

    private $_client;

    public function __construct()
    {
        $this->_client = new Client();
    }

    public function request(string $method, string $url, array $headers = [], array $params = []): array
    {
        $comparableMethod = strtoupper($method);
        $options = [
            'headers' => $headers,
        ];

        if (self::hasRequestBody($comparableMethod)) {
            $options['json'] = $params;
        } else {
            $options['query'] = $params;
        }

        $response = $this->_client->request($comparableMethod, $url, $options);
        return self::handleResponse($response);
    }
}
