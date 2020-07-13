<?php

namespace Trunkrs\SDK\HTTP;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class GuzzleClient implements HttpClientInterface {
    private static function hasRequestBody($method): bool {
        return $method == 'POST'
            || $method == 'PUT'
            || $method == 'PATCH';
    }

    private static function handleResponse(ResponseInterface $response): array {
        $headers = array_map(function ($header) {
            return is_array($header)
                ? join($header)
                : $header;
        }, $response->getHeaders());
        $body = $response->getBody()->getContents();

        $result = [
            'status' => $response->getStatusCode(),
            'headers' => $headers,
        ];
        if (!empty($body)) {
            $result['body'] = $body;
        }

        return $result;
    }

    private $_client;

    public function __construct(Client $client = null)
    {
        $this->_client = is_null($client)
            ? new Client()
            : $client;
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

        try {
            $response = $this->_client->request($comparableMethod, $url, $options);
            return self::handleResponse($response);
        } catch (RequestException $exception) {
            return self::handleResponse($exception->getResponse());
        }
    }

    public function download(string $method, string $url, string $fileName, array $headers = [], array $params = []): array {
        $comparableMethod = strtoupper($method);
        $stream = fopen($fileName, 'w');

        $options = [
            'headers' => $headers,
            'sink' => $stream,
        ];

        if (self::hasRequestBody($comparableMethod)) {
            $options['json'] = $params;
        } else {
            $options['query'] = $params;
        }

        try {
            $response = $this->_client->request($comparableMethod, $url, $options);
            return self::handleResponse($response);
        } catch (RequestException $exception) {
            return self::handleResponse($exception->getResponse());
        } finally {
            fclose($stream);
        }
    }
}
