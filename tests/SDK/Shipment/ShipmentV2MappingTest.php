<?php


namespace Trunkrs\SDK;

class ShipmentV2MappingTest extends APIV2TestCase
{
    public function testShouldMapV2Response() {
        $nrParcels = 2;
        $expected = Mocks::getFakeShipment($nrParcels);
        $json = MockV2Responses::getFakeShipmentBody($expected);

        $subject = new Shipment($json);

        $this->assertEquals($expected->trunkrsNr, $subject->trunkrsNr);
        $this->assertEquals($expected->service, $subject->service);
        $this->assertInstanceOf(Address::class, $subject->sender);
        $this->assertInstanceOf(Address::class, $subject->recipient);
        $this->assertInstanceOf(TimeSlot::class, $subject->timeSlot);
        $this->assertInstanceOf(ShipmentState::class, $subject->state);
        $this->assertInstanceOf(FeatureCodes::class, $subject->featureCodes);
        foreach ($subject->parcels as $parcel) {
            $this->assertInstanceOf(Parcel::class, $parcel);
        }
    }
}