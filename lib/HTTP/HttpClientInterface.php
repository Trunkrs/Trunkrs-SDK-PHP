<?php

namespace Trunkrs\SDK\HTTP;

interface HttpClientInterface {
    /**
     * @param string $method The HTTP method being used
     * @param string $url The URL being requested, including domain and protocol
     * @param array $headers Dictionary containing all headers to be used in the request.
     * @param array $params Dictionary for request parameters. Can be nested for arrays and hashes.
     *
     * @return array an array whose first element is raw request body, second
     *    element is HTTP status code and third array of HTTP headers
     */
    public function request(string $method, string $url, array $headers, array $params): array;

    /**
     * @param string $method The HTTP method being used
     * @param string $url The URL being requested, including domain and protocol
     * @param string $fileName The file name to download the resulting data into.
     * @param array $headers Dictionary containing all headers to be used in the request.
     * @param array $params Dictionary for request parameters. Can be nested for arrays and hashes.
     *
     * @return array an array whose first element is raw request body, second
     *    element is HTTP status code and third array of HTTP headers
     */
    public function download(string $method, string $url, string $fileName, array $headers = [], array $params = []): array;
}