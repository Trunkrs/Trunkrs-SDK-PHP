<?php

namespace Trunkrs\SDK;

/**
 * Class Settings
 */
class Settings {
    private static $_supportedApiVersions = [1, 2];

    /**
     * @var string The v2 API key used in requests.
     */
    public static $apiKey;

    /**
     * @var string The v1 client to used in requests.
     */
    public static $clientId;

    /**
     * @var string The v1 client secret to be used in requests.
     */
    public static $clientSecret;

    /**
     * @var string The API endpoint url to be used in requests.
     */
    public static $baseUrl = "https://api.trunkrs.nl/api";

    public static $trackingBaseUrl = "https://parcel.trunkrs.nl";

    /**
     * @var int The API version to be used. Version 1 will be deprecated at the start of February 2021.
     */
    public static $apiVersion = 2;

    /**
     * @var string The current version of the SDK.
     */
    public static $sdkVersion = '2.0.0';

    /**
     * Sets the client credentials that will be used in subsequent requests.
     * Should only be used in combination with api version 1.
     *
     * @param $clientId string The client id.
     * @param $clientSecret string The client secret.
     */
    public static function setCredentials(string $clientId, string $clientSecret) {
        self::$clientId = $clientId;
        self::$clientSecret = $clientSecret;
    }

    /**
     * Sets the API key that will be used in subsequent requests.
     * Should only be used in combination with api version 2.
     *
     * @param $apiKey string The API key provided by Trunkrs.
     */
    public static function setApiKey(string $apiKey) {
        self::$apiKey = $apiKey;
    }

    /**
     * Sets the API version to be used by the SDK.
     *
     * @param $apiVersion int The API version to be used.
     * @throws Exception\UnsupportedVersionException When an unsupported version is requested will throw.
     */
    public static function setApiVersion(int $apiVersion) {
        if (!in_array($apiVersion, self::$_supportedApiVersions)) {
            throw new Exception\UnsupportedVersionException($apiVersion, self::$_supportedApiVersions);
        }

        self::$apiVersion = $apiVersion;
    }

    /**
     * Switches the SDK to the staging environment.
     */
    public static function useStaging() {
        self::$baseUrl = "https://staging-api.trunkrs.nl/api";
        self::$trackingBaseUrl = "https://staging-parcel.trunkrs.nl";
    }
}
