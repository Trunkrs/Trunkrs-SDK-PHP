<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Enum\ShipmentLabelType;

class LabelTest extends SDKTestCase
{
    public function testShouldCreateTempFile() {
        $subject = new Label(ShipmentLabelType::PDF);

        $this->assertFileExists($subject->getRealPath());
    }

    public function testShouldNotLeaveBehindTempFile() {
        $subject = new Label(ShipmentLabelType::PDF);
        $filePath = $subject->getRealPath();

        $subject = null;

        $this->assertFileNotExists($filePath);
    }
}