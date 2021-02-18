<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Enum\ShipmentLabelType;

class LabelV1DownloadTest extends APIV1TestCase {
    public function testShouldExecuteAGetRequestForZPL() {
        $this->mockDownloadCallback(function ($method) {
            $this->assertEquals("GET", $method);
            return ["status" => 200];
        });

        Label::download(
            ShipmentLabelType::ZPL,
            Mocks::getTrunkrsNr(),
            '3439 LC'
        );
    }

    public function testShouldExecuteAGetRequestForPDF() {
        $this->mockDownloadCallback(function ($method) {
            $this->assertEquals("GET", $method);
            return ["status" => 200];
        });

        Label::download(
            ShipmentLabelType::PDF,
            Mocks::getTrunkrsNr(),
            '3439 LC'
        );
    }

    public function testShouldUseTrunkrsNr() {
        $trunkrsNr = Mocks::getTrunkrsNr();

        $this->mockDownloadCallback(function ($method, $url) use ($trunkrsNr) {
            $this->assertContains($trunkrsNr, $url);
            return ["status" => 200];
        });

        Label::download(ShipmentLabelType::ZPL, $trunkrsNr, '3439 LC');
    }
}