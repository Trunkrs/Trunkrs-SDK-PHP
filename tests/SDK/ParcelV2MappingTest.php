<?php

namespace Trunkrs\SDK;

class ParcelV2MappingTest extends APIV2TestCase {
    public function testShouldMapToV2ParcelRequest() {
        $nrContentItems = Mocks::getGenerator()->numberBetween(1, 6);
        $subject = Mocks::getFakeParcel($nrContentItems);
        $expected = MockV2Responses::getFakeParcelBody($subject);

        $request = $subject->serialize();

        $this->assertEquals($expected->reference, $request["reference"]);
        $this->assertEquals($expected->description, $request["description"]);
        $this->assertCount($nrContentItems, $request['contents']);
        $this->assertArrayHasKey("size", $request);
        $this->assertArrayHasKey("weight", $request);
    }

    public function testShouldMapFromV2ParcelResponse() {
        $nrContentItems = Mocks::getGenerator()->numberBetween(1, 6);
        $expected = Mocks::getFakeParcel($nrContentItems);
        $json = MockV2Responses::getFakeParcelBody($expected);

        $subject = new Parcel((object)$json);

        $this->assertEquals($expected->reference, $subject->reference);
        $this->assertEquals($expected->description, $subject->description);
        $this->assertInstanceOf(ParcelMeasurements::class, $subject->measurements);
        $this->assertCount($nrContentItems, $subject->contents);
        foreach ($subject->contents as $contentItem) {
            $this->assertInstanceOf(ParcelContent::class, $contentItem);
        }
    }
}