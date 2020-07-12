<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Enum\ShipmentLabelType;

class ShipmentDownloadLabelTest extends SDKTestCase {
    public function testShouldDownloadLabelInZPL() {
        $shipment = new Shipment(MockV1Responses::getFakeShipmentBody());
        $this->mockDownloadCallback(function ($method, $url) use ($shipment) {
            $this->assertEquals("GET", $method);
            $this->assertContains(sprintf("/%d", $shipment->trunkrsNr), $url);
            $this->assertContains("/zpl", $url);

            return ["status" => 200];
        });

        $shipment->downloadLabel(ShipmentLabelType::ZPL);
    }

    public function testShouldDownloadLabelInPDF() {
        $shipment = new Shipment(MockV1Responses::getFakeShipmentBody());
        $this->mockDownloadCallback(function ($method, $url) use ($shipment) {
            $this->assertEquals("GET", $method);
            $this->assertContains($shipment->trunkrsNr, $url);
            $this->assertNotContains("/zpl", $url);

            return ["status" => 200];
        });

        $shipment->downloadLabel(ShipmentLabelType::PDF);
    }
}