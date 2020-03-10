<?php

namespace Trunkrs\SDK;

class TimeWindowV1MappingTest extends APIV1TestCase {
    public function testTimeWindowV1Mapping() {
        $srcTimeWindow = Mocks::getFakeTimeWindow();
        $json = Mocks::getFakeTimeWindowBody($srcTimeWindow);

        $timeWindow = new TimeWindow($json);

        $this->assertAttributeEquals($srcTimeWindow->from, 'from', $timeWindow);
        $this->assertAttributeEquals($srcTimeWindow->to, 'to', $timeWindow);
    }
}