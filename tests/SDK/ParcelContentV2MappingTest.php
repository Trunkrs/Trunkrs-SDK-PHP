<?php

namespace Trunkrs\SDK;

class ParcelContentV2MappingTest extends APIV2TestCase {
    public function testShouldMapToV2Request() {
        $subject = Mocks::getFakeParcelContent();

        $request = $subject->serialize();

        $this->assertEquals($subject->reference, $request['reference']);
        $this->assertEquals($subject->name, $request['name']);
        $this->assertEquals($subject->remarks, $request['additionalRemarks']);
    }

    public function testShouldMapFromV2Response() {
        $expected = Mocks::getFakeParcelContent();
        $json = MockV2Responses::getFakeContentItemBody($expected);

        $subject = new ParcelContent((object)$json);

        $this->assertEquals($expected->reference, $subject->reference);
        $this->assertEquals($expected->name, $subject->name);
        $this->assertEquals($expected->remarks, $subject->remarks);
    }
}