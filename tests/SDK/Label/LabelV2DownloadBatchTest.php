<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Enum\ShipmentLabelSize;
use Trunkrs\SDK\Enum\ShipmentLabelType;
use Trunkrs\SDK\Exception\NotSupportedException;

class LabelV2DownloadBatchTest extends APIV2TestCase {
    public function testShouldExecuteAPutRequestForPDF() {
        $this->mockDownloadCallback(function ($method) {
            $this->assertEquals("PUT", $method);
            return ["status" => 200];
        });

        Label::downloadBatch(ShipmentLabelType::PDF, "cool-label.pdf", [
            Mocks::getTrunkrsNr(),
            Mocks::getTrunkrsNr(),
        ]);
    }

    public function testShouldPassTrunkrsNrsAndSizeInBody() {
        $size = ShipmentLabelSize::A4;
        $trunkrsNrs = [
            Mocks::getTrunkrsNr(),
            Mocks::getTrunkrsNr(),
            Mocks::getTrunkrsNr(),
            Mocks::getTrunkrsNr(),
        ];

        $this->mockDownloadCallback(function ($method, $url, $filename, $headers, $params) use ($trunkrsNrs, $size) {
            $this->assertEquals($trunkrsNrs, $params['trunkrsNrs']);
            $this->assertEquals($size, $params['size']);
            return ["status" => 200];
        });

        Label::downloadBatch(ShipmentLabelType::PDF, "cool-label.pdf", $trunkrsNrs, $size);
    }

    public function testShouldThrowWhenFormatZPL() {
        $this->mockDownload(200);
        $this->expectException(NotSupportedException::class);

        Label::downloadBatch(ShipmentLabelType::ZPL, "cool-label.pdf", [
            Mocks::getTrunkrsNr(),
            Mocks::getTrunkrsNr(),
        ]);
    }
}