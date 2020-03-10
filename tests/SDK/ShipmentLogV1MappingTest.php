<?php

namespace Trunkrs\SDK;

class ShipmentLogV1MappingTest extends APIV1TestCase {
    public function testMapsShipmentLogV1() {
        $srcLog = Mocks::getFakeShipmentLog();
        $json = MockV1Responses::getFakeShipmentLogBody($srcLog);

        $log = new ShipmentLog($json);

        $this->assertAttributeEquals($srcLog->id, 'id', $log);
        $this->assertAttributeEquals($srcLog->label, 'label', $log);
        $this->assertAttributeEquals($srcLog->name, 'name', $log);
        $this->assertAttributeEquals($srcLog->description, 'description', $log);
        $this->assertAttributeEquals($srcLog->reason, 'reason', $log);
    }
}