<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Enum\ShipmentLabelType;
use Trunkrs\SDK\Exception\NotSupportedException;

class LabelV1DownloadBatchTest extends APIV1TestCase {
    public function testShouldExecuteAPutRequestForPDF() {
        $this->mockDownloadCallback(function ($method) {
            $this->assertEquals("PUT", $method);
            return ["status" => 200];
        });

        Label::downloadBatch(ShipmentLabelType::PDF, [
            Mocks::getTrunkrsNr(),
            Mocks::getTrunkrsNr(),
        ]);
    }

    public function testShouldPassTrunkrsNrsInBody() {
        $trunkrsNrs = [
            Mocks::getTrunkrsNr(),
            Mocks::getTrunkrsNr(),
            Mocks::getTrunkrsNr(),
            Mocks::getTrunkrsNr(),
        ];

        $this->mockDownloadCallback(function ($method, $url, $filename, $headers, $params) use ($trunkrsNrs) {
            $this->assertEquals($trunkrsNrs, $params['trunkrsNrs']);
            return ["status" => 200];
        });

        Label::downloadBatch(ShipmentLabelType::PDF, $trunkrsNrs);
    }

    public function testShouldThrowWhenFormatZPL() {
        $this->mockDownload(200);
        $this->expectException(NotSupportedException::class);

        Label::downloadBatch(ShipmentLabelType::ZPL, [
            Mocks::getTrunkrsNr(),
            Mocks::getTrunkrsNr(),
        ]);
    }
}