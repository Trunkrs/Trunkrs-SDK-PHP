<?php

namespace Trunkrs\SDK;

/**
 * Class Settings
 */
class Settings {
    private static $_supportedApiVersions = [1];

    /**
     * @var string The client to used in requests.
     */
    public static $clientId;

    /**
     * @var string The client secret to be used in requests.
     */
    public static $clientSecret;

    /**
     * @var string The API endpoint url to be used in requests.
     */
    public static $baseUrl = "https://api.trunkrs.nl/api";

    public static $trackingBaseUrl = "https://parcel.trunkrs.nl";

    /**
     * @var int The API version to be used. Only version 1 is supported at the moment.
     */
    public static $apiVersion = 1;

    /**
     * @var string The current version of the SDK.
     */
    public static $sdkVersion = '1.2.3';

    /**
     * Sets the client credentials that will be used in subsequent requests.
     *
     * @param $clientId string The client id.
     * @param $clientSecret string The client secret.
     */
    public static function setCredentials($clientId, $clientSecret) {
        self::$clientId = $clientId;
        self::$clientSecret = $clientSecret;
    }

    /**
     * Sets the API version to be used by the SDK.
     *
     * @param $apiVersion int The API version to be used.
     * @throws Exception\UnsupportedVersionException When an unsupported version is requested will throw.
     */
    public static function setApiVersion($apiVersion) {
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
