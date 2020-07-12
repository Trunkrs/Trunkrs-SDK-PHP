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

    public function testShouldReflectFormatType() {
        $zplLabel = new Label(ShipmentLabelType::ZPL);
        $pdfLabel = new Label(ShipmentLabelType::PDF);

        $this->assertEquals($zplLabel->type, ShipmentLabelType::ZPL);
        $this->assertEquals($pdfLabel->type, ShipmentLabelType::PDF);
    }
}