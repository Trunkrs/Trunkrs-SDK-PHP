<?php

namespace Trunkrs\SDK;

class ShipmentDetailsV1MappingTest extends APIV1TestCase {
    public function testShipmentDetailsV1Mapping() {
        $nrParcels = Mocks::getGenerator()->numberBetween(1, 3);
        $srcDetails = Mocks::getFakeDetails($nrParcels);

        $json = $srcDetails->serialize();

        $this->assertEquals($srcDetails->timeSlotId, $json['timeSlotId']);
        $this->assertEquals($nrParcels, $json['totalQuantity']);
    }
}