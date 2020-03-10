<?php

namespace Trunkrs\SDK;

class ShipmentDetailsV1MappingTest extends APIV1TestCase {
    public function testShipmentDetailsV1Mapping() {
        $srcDetails = Mocks::getFakeDetails();

        $json = $srcDetails->serialize();

        self::assertEquals($srcDetails->reference, $json['orderReference']);
    }
}