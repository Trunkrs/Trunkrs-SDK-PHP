<?php

namespace Trunkrs\SDK;

class TimeWindowV2MappingTest extends APIV2TestCase
{
    public function testShouldMapV2Response() {
        $srcTimeWindow = Mocks::getFakeTimeWindow();
        $json = MockV2Responses::getFakeTimeWindowBody($srcTimeWindow);

        $subject = new TimeWindow($json);

        $this->assertEquals($srcTimeWindow->from, $subject->from);
        $this->assertEquals($srcTimeWindow->to, $subject->to);
    }
}