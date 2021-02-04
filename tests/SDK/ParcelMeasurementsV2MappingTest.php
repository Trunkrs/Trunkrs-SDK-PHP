<?php


namespace Trunkrs\SDK;


class ParcelMeasurementsV2MappingTest extends APIV2TestCase
{
    public function testShouldMapToV2Request() {
        $subject = Mocks::getFakeParcelMeasurements();

        $request = $subject->serialize();

        $this->assertEquals($subject->weight->unit, $request['weight']['unit']);
        $this->assertEquals($subject->weight->value, $request['weight']['value']);
        $this->assertEquals($subject->depth->unit, $request['size']['depth']['unit']);
        $this->assertEquals($subject->depth->value, $request['size']['depth']['value']);
        $this->assertEquals($subject->width->unit, $request['size']['width']['unit']);
        $this->assertEquals($subject->width->value, $request['size']['width']['value']);
        $this->assertEquals($subject->height->unit, $request['size']['height']['unit']);
        $this->assertEquals($subject->height->value, $request['size']['height']['value']);
    }

    public function testShouldMapToV2Response() {
        $expected = Mocks::getFakeParcelMeasurements();
        $json = MockV2Responses::getFakeParcelMeasurementsBody($expected);

        $subject = new ParcelMeasurements($json);

        $this->assertEquals($expected->weight, $subject->weight);
        $this->assertEquals($expected->depth, $subject->depth);
        $this->assertEquals($expected->width, $subject->width);
        $this->assertEquals($expected->height, $subject->height);
    }
}