<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Enum\ShipmentLabelType;

class LabelTest extends SDKTestCase
{
    public function testShouldCreateTempFile() {
        $subject = new Label(ShipmentLabelType::PDF);

        $this->assertFileExists($subject->getRealPath());
    }

    public function testShouldReflectFormatType() {
        $zplLabel = new Label(ShipmentLabelType::ZPL);
        $pdfLabel = new Label(ShipmentLabelType::PDF);

        $this->assertEquals($zplLabel->type, ShipmentLabelType::ZPL);
        $this->assertEquals($pdfLabel->type, ShipmentLabelType::PDF);
    }
}