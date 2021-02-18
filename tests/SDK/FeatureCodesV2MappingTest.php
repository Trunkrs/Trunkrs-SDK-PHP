<?php

namespace Trunkrs\SDK;

class FeatureCodesV2MappingTest extends APIV2TestCase {
    public function testShouldMapToV2Request() {
        $subject = Mocks::getFakeFeatureCodes();

        $request = $subject->serialize();

        $this->assertEquals($subject->noNeighbourDelivery, $request['noNeighbourDelivery']);
        $this->assertEquals($subject->noSignature, $request['noSignature']);
        $this->assertEquals($subject->deliverInMailBox, $request['deliverInMailBox']);
        $this->assertEquals($subject->maxDeliveryAttempts, $request['maxDeliveryAttempts']);
        $this->assertEquals($subject->maxHoursOutsideFreezer, $request['maxTimeOutsideFreezer']);
    }

    public function testShouldMapFromV2Response() {
        $expected = Mocks::getFakeFeatureCodes();
        $json = MockV2Responses::getFakeFeatureCodesBody($expected);

        $subject = new FeatureCodes($json);

        $this->assertEquals($expected->noNeighbourDelivery, $subject->noNeighbourDelivery);
        $this->assertEquals($expected->noSignature, $subject->noSignature);
        $this->assertEquals($expected->deliverInMailBox, $subject->deliverInMailBox);
        $this->assertEquals($expected->maxDeliveryAttempts, $subject->maxDeliveryAttempts);
        $this->assertEquals($expected->maxHoursOutsideFreezer, $subject->maxHoursOutsideFreezer);
    }
}