<?php

namespace Trunkrs\SDK;

class TimeSlotV2MappingTest extends APIV2TestCase
{
    public function testShouldMapV2Response() {
        $srcTimeSlot = Mocks::getFakeTimeSlot();
        $json = MockV2Responses::getFakeTimeSlotBody($srcTimeSlot);

        $subject = new TimeSlot($json);

        $this->assertEquals($srcTimeSlot->id, $subject->id);
        $this->assertInstanceOf(Address::class, $subject->sender);
        $this->assertEquals($srcTimeSlot->dataCutOff, $subject->dataCutOff);
        $this->assertInstanceOf(TimeWindow::class, $subject->collectionWindow);
        $this->assertInstanceOf(TimeWindow::class, $subject->deliveryWindow);
        $this->assertInstanceOf(ServiceArea::class, $subject->serviceArea);
    }
}