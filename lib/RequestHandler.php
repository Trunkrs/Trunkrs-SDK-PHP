<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Exception\GeneralApiException;
use Trunkrs\SDK\Exception\NotAuthorizedException;
use Trunkrs\SDK\Exception\ServerValidationException;

class RequestHandler {
    private static $_httpClient;

    private static function createUrl(string $resource): string {
        return sprintf("%s/v%d/%s", Settings::$baseUrl, Settings::$apiVersion, ltrim($resource, "/"));
    }

    private static function createHeaders(bool $withBody): array {
        $headers = [
            "User-Agent" => sprintf("Trunkrs SDK/PHP/v%s", Settings::$sdkVersion),
            "X-API-ClientId" => Settings::$clientId,
            "X-API-ClientSecret" => Settings::$clientSecret,
            "Accept" => "application/json; charset=utf-8",
        ];

        if ($withBody) {
            $headers["Content-Type"] = "application/json; charset=utf-8";
        }

        return $headers;
    }

    private static function isSuccessful(array $response): bool {
        $statusCode = $response['status'];
        return $statusCode >= 200 && $statusCode <= 204;
    }

    private static function handleSuccess(array $response) {
        return array_key_exists("body", $response)
            ? json_decode($response["body"])
            : null;
    }

    private static function handleFailure(array $response) {
        switch ($response['status']) {
            case 401:
                throw new NotAuthorizedException();
            case 422:
                throw new ServerValidationException($response);
            default:
                throw new GeneralApiException($response);
        }
    }

    /**
     * Executes a GET request on the specified resource and optional query parameters.
     *
     * @param string $resource The resource to retrieve.
     * @param array $query Optional query parameters.
     * @return object JSON result as associative array.
     * @throws NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws GeneralApiException When the API responds with an unexpected answer.
     */
    public static function get(string $resource, array $query = []) {
        $response = self::getClient()->request(
            "GET",
            self::createUrl($resource),
            self::createHeaders(false),
            $query
        );

        if (!self::isSuccessful($response)) {
            self::handleFailure($response);
        }
        return self::handleSuccess($response);
    }

    /**
     * Executes a POST request on the specified resource with the specified body.
     *
     * @param string $resource The resource to create.
     * @param array $body Associative array as the body.
     * @return object JSON result as associative array.
     * @throws NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws ServerValidationException When the request payload doesn't match the expectation of the API.
     * @throws GeneralApiException When the API responds with an unexpected answer.
     */
    public static function post(string $resource, array $body) {
        $response = self::getClient()->request(
            "POST",
            self::createUrl($resource),
            self::createHeaders(true),
            $body
        );

        if (!self::isSuccessful($response)) {
            self::handleFailure($response);
        }
        return self::handleSuccess($response);
    }

    /**
     * Executes a PUT request on the specified resource with an optional body and query parameters.
     *
     * @param string $resource The resource to execute PUT on.
     * @param array $body A optional associative array as the request body.
     * @return object JSON result as associative array.
     * @throws NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws ServerValidationException When the request payload doesn't match the expectation of the API.
     * @throws GeneralApiException When the API responds with an unexpected answer.
     */
    public static function put(string $resource, array $body = []) {
        $response = self::getClient()->request(
            "PUT",
            self::createUrl($resource),
            self::createHeaders(true),
            $body
        );

        if (!self::isSuccessful($response)) {
            self::handleFailure($response);
        }
        return self::handleSuccess($response);
    }

    /**
     * Executes a DELETE on the specified resource with optional query parameters.
     *
     * @param string $resource The resource to execute delete on.
     * @param array $query A optional associative array as the query parameters.
     * @return object JSON result as associative array.
     * @throws NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws GeneralApiException When the API responds with an unexpected answer.
     */
    public static function delete(string $resource, array $query = []) {
        $response = self::getClient()->request(
            "DELETE",
            self::createUrl($resource),
            self::createHeaders(false),
            $query
        );

        if (!self::isSuccessful($response)) {
            self::handleFailure($response);
        }
        return self::handleSuccess($response);
    }

    /**
     * Sets the HTTP client based on the pre-defined client interface.
     *
     * @param $client HTTP\HttpClientInterface The client instance to use for requests.
     */
    public static function setHttpClient(HTTP\HttpClientInterface $client) {
        self::$_httpClient = $client;
    }

    private static function getClient(): HTTP\HttpClientInterface {
        if (!self::$_httpClient) {
            self::$_httpClient = new HTTP\GuzzleClient();
        }

        return self::$_httpClient;
    }
}
