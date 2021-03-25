<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Enum\ShipmentLabelType;
use Trunkrs\SDK\Exception\NotSupportedException;

class LabelV2DownloadTest extends APIV2TestCase {
    public function testShouldThrowNotSupportedException() {
        $this->expectException(NotSupportedException::class);

        Label::download(
            ShipmentLabelType::ZPL,
            Mocks::getTrunkrsNr(),
            '3439 LC'
        );
    }
}