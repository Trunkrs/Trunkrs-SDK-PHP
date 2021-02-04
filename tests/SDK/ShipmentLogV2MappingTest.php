<?php


namespace Trunkrs\SDK;

class ShipmentLogV2MappingTest extends APIV2TestCase
{
    public function testShouldMapV2Response() {
        $srcLog = Mocks::getFakeShipmentLog();
        $json = MockV2Responses::getFakeShipmentLogBody($srcLog);

        $log = new ShipmentLog($json);

        $this->assertAttributeEquals($srcLog->code, 'code', $log);
        $this->assertAttributeEquals($srcLog->reason, 'reason', $log);
    }
}