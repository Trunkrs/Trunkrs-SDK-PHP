<?php

namespace Trunkrs\SDK;

class ShipmentLogV1MappingTest extends APIV1TestCase {
    public function testMapsShipmentLogV1() {
        $srcLog = Mocks::getFakeShipmentLog();
        $json = MockV1Responses::getFakeShipmentLogBody($srcLog);

        $log = new ShipmentLog($json);

        $this->assertAttributeEquals($srcLog->code, 'code', $log);
        $this->assertAttributeEquals($srcLog->reason, 'reason', $log);
    }
}