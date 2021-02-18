<?php

namespace Trunkrs\SDK;

class ShipmentRetrievalTest extends IntegrationTestCase {
    public function testShouldRetrieveShipments() {
        $shipments = Shipment::retrieve();

        $this->assertCount(50, $shipments);
    }

    public function testShouldRetrieveSecondPageOfShipments() {
        $shipments = Shipment::retrieve(2);

        $this->assertCount(50, $shipments);
    }

    public function testShouldGetASpecificShipment() {
        $shipments = Shipment::retrieve(2);
        $trunkrsNr = $shipments[0]->trunkrsNr;

        $shipment = Shipment::find($trunkrsNr);

        $this->assertEquals($trunkrsNr, $shipment->trunkrsNr);
    }

    public function testShouldRetrieveStateForShipment() {
        $shipment = Shipment::retrieve()[0];

        $state = $shipment->getState();

        $this->assertInstanceOf(ShipmentState::class, $state);
    }
}