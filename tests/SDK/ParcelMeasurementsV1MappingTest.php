<?php


namespace Trunkrs\SDK;


class ParcelMeasurementsV1MappingTest extends APIV1TestCase
{
    public function testShouldMapV1Request() {
        $subject = Mocks::getFakeParcelMeasurements();

        $request = $subject->serialize();

        $this->assertArrayHasKey('width', $request);
        $this->assertArrayHasKey('height', $request);
        $this->assertArrayHasKey('volume', $request);
        $this->assertArrayHasKey('weight', $request);
    }
}