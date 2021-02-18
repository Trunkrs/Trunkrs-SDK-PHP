<?php


namespace Trunkrs\SDK;

class ShipmentStateV2MappingTest extends APIV2TestCase
{
    public function testShouldMapFromV2Response() {
        $srcState = Mocks::getFakeShipmentState();
        $json = MockV2Responses::getFakeShipmentStateBody($srcState);

        $state = new ShipmentState($json);

        $this->assertEquals($srcState->state->code, $state->state->code);
        $this->assertEquals($srcState->state->reason, $state->state->reason);
        $this->assertEquals($srcState->timestamp, $state->timestamp);
        $this->assertInstanceOf(PackageOwner::class, $srcState->owner);
    }
}