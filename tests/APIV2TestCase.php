<?php

namespace Trunkrs\SDK;

abstract class APIV2TestCase extends SDKTestCase {
    private $version;

    public function setUp()
    {
        parent::setUp();

        $version = Settings::$apiVersion;
        Settings::setApiVersion(2);
    }
}