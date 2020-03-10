<?php

namespace Trunkrs\SDK;

class TimeSlotV1MappingTest extends APIV1TestCase {
    public function testTimeSlotV1Mapping() {
        $srcTimeSlot = Mocks::getFakeTimeSlot();
        $json = MockV1Responses::getFakeTimeSlotBody($srcTimeSlot);

        $timeSlot = new TimeSlot($json);

        $this->assertAttributeEquals($srcTimeSlot->id, 'id', $timeSlot);
        $this->assertAttributeEquals($srcTimeSlot->senderId, 'senderId', $timeSlot);
        $this->assertAttributeEquals($srcTimeSlot->dataCutOff, 'dataCutOff', $timeSlot);
    }

}