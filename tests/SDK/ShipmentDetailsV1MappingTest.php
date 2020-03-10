<?php

namespace Trunkrs\SDK;

class ShipmentDetailsV1MappingTest extends APIV1TestCase {
    public function testShipmentDetailsV1Mapping() {
        $srcDetails = Mocks::getFakeDetails();
        $srcDetails->quantity = Mocks::getGenerator()->randomNumber();

        $json = $srcDetails->serialize();

        $this->assertEquals($srcDetails->reference, $json['orderReference']);
        $this->assertEquals($srcDetails->quantity, $json['totalQuantity']);
    }
}