<?php

namespace Trunkrs\SDK;

class ShipmentDetailsV2MappingTest extends APIV2TestCase
{
    public function testShipmentDetailsV2Mapping() {
        $details = Mocks::getFakeDetails();

        $json = $details->serialize();

        $this->assertEquals($details->timeSlotId, $json['timeSlotId']);
        $this->assertEquals($details->service, $json['service']);
    }
}