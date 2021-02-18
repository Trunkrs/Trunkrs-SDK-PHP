<?php

namespace Trunkrs\SDK;

class ShipmentStateV1MappingTest extends APIV1TestCase {
    public function testMapsShipmentStateV1() {
        $srcState = Mocks::getFakeShipmentState();
        $json = MockV1Responses::getShipmentStateBody($srcState);

        $state = new ShipmentState($json);

        $this->assertAttributeEquals($srcState->shipmentId, 'shipmentId', $state);
        $this->assertAttributeEquals($srcState->timestamp, 'timestamp', $state);
    }
}