<?php

namespace Trunkrs\SDK;

use PHPUnit\Framework\TestCase;
use Trunkrs\SDK\Exception\UnsupportedVersionException;

class SettingsTest extends TestCase {
    public function testSetCredentials() {
        $clientId = uniqid();
        $clientSecret = 'team-runtime-terror-1337';

        Settings::setCredentials($clientId, $clientSecret);

        $this->assertEquals($clientId, Settings::$clientId);
        $this->assertEquals($clientSecret, Settings::$clientSecret);
    }

    public function testSetsApiKey() {
        $apiKey = uniqid();

        Settings::setApiKey($apiKey);

        $this->assertEquals($apiKey, Settings::$apiKey);
    }

    public function testSetsAPIVersionV1() {
        $apiVersion = 1;

        Settings::setApiVersion($apiVersion);

        $this->assertEquals($apiVersion, Settings::$apiVersion);
    }

    public function testSetsAPIVersionV2() {
        $apiVersion = 2;

        Settings::setApiVersion($apiVersion);

        $this->assertEquals($apiVersion, Settings::$apiVersion);
    }

    public function testThrowsOnUnsupportedAPIVersion() {
        $this->expectException(UnsupportedVersionException::class);

        Settings::setApiVersion(100);
    }

    public function testSwitchesToStagingEnvironment() {
        $origBaseUrl = Settings::$baseUrl;
        $origTrackingUrl = Settings::$trackingBaseUrl;

        Settings::useStaging();

        self::assertNotEquals($origBaseUrl, Settings::$baseUrl);
        self::assertNotEquals($origTrackingUrl, Settings::$trackingBaseUrl);
    }
}