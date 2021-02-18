<?php

namespace Trunkrs\SDK;

use PHPUnit\Framework\TestCase;

class IntegrationTestCase extends TestCase {
    protected function setUp() {
        parent::setUp();

        $apiKey = getenv('INTEGRATION_API_KEY');
        if (!$apiKey) {
            throw new \Exception("When running integration tests, you'll need to supply an API key");
        }

        Settings::useStaging();
        Settings::setApiKey($apiKey);
    }
}