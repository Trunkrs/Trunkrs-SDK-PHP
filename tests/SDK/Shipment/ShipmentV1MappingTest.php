<?php

namespace Trunkrs\SDK;

class ShipmentV1MappingTest extends APIV1TestCase {
    public function testCorrectlyMapsV1Shipment() {
        $shipmentId = Mocks::getGenerator()->randomNumber();
        $trunkrsNr = Mocks::getTrunkrsNr();
        $labelUrl = Mocks::getGenerator()->url;

        $shipment = new Shipment(MockV1Responses::getFakeShipmentBody(
            $shipmentId,
            $trunkrsNr,
            $labelUrl
        ));

        $this->assertAttributeEquals($shipmentId, 'id', $shipment);
        $this->assertAttributeEquals($trunkrsNr, 'trunkrsNr', $shipment);
        $this->assertAttributeEquals($labelUrl, 'pdfUrl', $shipment->label);
    }
}