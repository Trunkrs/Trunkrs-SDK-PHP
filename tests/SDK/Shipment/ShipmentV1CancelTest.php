<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Exception\ShipmentNotFoundException;

class ShipmentV1CancelTest extends APIV1TestCase {
    public function testShouldExecuteDeleteRequest() {
        $this->mockResponseCallback(function ($method) {
            $this->assertEquals("DELETE", $method);
            return ["status" => 204];
        });

        Shipment::cancelById(100);
    }

    public function testShouldThrowWhenShipmentNotFound() {
        $this->mockResponse(404);
        $this->expectException(ShipmentNotFoundException::class);

        Shipment::cancelById(101);
    }

    public function testShouldExecuteDeleteFromInstanceCancel() {
        $shipment = new Shipment(MockV1Responses::getFakeShipmentBody());
        $this->mockResponseCallback(function ($method, $url) use ($shipment) {
            $this->assertEquals("DELETE", $method);
            $this->assertContains(sprintf("/%d", $shipment->id), $url);

            return ["status" => 204];
        });

        $shipment->cancel();
    }
}