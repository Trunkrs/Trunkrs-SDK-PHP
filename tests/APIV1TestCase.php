<?php

namespace Trunkrs\SDK;

abstract class APIV1TestCase extends SDKTestCase {
    public function setUp()
    {
        parent::setUp();

        Settings::setApiVersion(1);
    }
}