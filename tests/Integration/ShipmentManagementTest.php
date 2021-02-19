<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Enum\MeasurementUnit;
use Trunkrs\SDK\Enum\ShipmentService;

class ShipmentManagementTest extends IntegrationTestCase {
    /**
     * @var Shipment[]
     */
    private $shipments = [];

    public function testShouldCreateAMinimalShipment() {
        $sender = Mocks::getFakeAddress();
        $sender->addressLine = 'De Liesbosch 90';
        $sender->postal = '3439LC';
        $sender->city = 'Nieuwengein';

        $recipient = Mocks::getFakeAddress();
        $recipient->addressLine = 'De Liesbosch 90';
        $recipient->postal = '3439LC';
        $recipient->city = 'Nieuwengein';

        $details = new ShipmentDetails();
        $details->sender = $sender;
        $details->recipient = $recipient;
        $details->service = ShipmentService::SAME_DAY;
        $timeslot = TimeSlot::retrieve($recipient->postal)[0];
        $details->timeSlotId = $timeslot->id;

        $parcel = new Parcel();
        $parcel->reference = Mocks::getGenerator()->uuid;
        $details->parcels = [$parcel];

        $this->shipments = Shipment::create($details);

        $this->assertCount(1, $this->shipments);
        $this->assertEquals($sender, $this->shipments[0]->sender);
        $this->assertEquals($recipient, $this->shipments[0]->recipient);
        $this->assertEquals($timeslot->id, $this->shipments[0]->timeSlot->id);
    }

    public function testShouldCreateMultiColli() {
        $sender = Mocks::getFakeAddress();
        $sender->addressLine = 'De Liesbosch 90';
        $sender->postal = '3439LC';
        $sender->city = 'Nieuwengein';

        $recipient = Mocks::getFakeAddress();
        $recipient->addressLine = 'De Liesbosch 90';
        $recipient->postal = '3439LC';
        $recipient->city = 'Nieuwengein';

        $details = new ShipmentDetails();
        $details->sender = $sender;
        $details->recipient = $recipient;
        $details->service = ShipmentService::SAME_DAY;
        $timeslot = TimeSlot::retrieve($recipient->postal)[0];
        $details->timeSlotId = $timeslot->id;

        $dimensions = new ParcelMeasurements();
        $dimensions->weight = new Measurement();
        $dimensions->weight->value = 0;
        $dimensions->weight->unit = MeasurementUnit::KILOGRAMS;

        $parcel1 = new Parcel();
        $parcel1->reference = Mocks::getGenerator()->uuid;
        $parcel1->measurements = $dimensions;
        $parcel2 = new Parcel();
        $parcel2->reference = Mocks::getGenerator()->uuid;
        $parcel2->measurements = $dimensions;
        $parcel3 = new Parcel();
        $parcel3->reference = Mocks::getGenerator()->uuid;
        $parcel3->measurements = $dimensions;
        $parcel4 = new Parcel();
        $parcel4->reference = Mocks::getGenerator()->uuid;
        $parcel4->measurements = $dimensions;
        $details->parcels = [$parcel1, $parcel2, $parcel3, $parcel4];

        $this->shipments = Shipment::create($details);

        $this->assertCount(4, $this->shipments);
        $this->assertEquals($parcel1->reference, $this->shipments[0]->parcels[0]->reference);
        $this->assertEquals($parcel2->reference, $this->shipments[1]->parcels[0]->reference);
        $this->assertEquals($parcel3->reference, $this->shipments[2]->parcels[0]->reference);
        $this->assertEquals($parcel4->reference, $this->shipments[3]->parcels[0]->reference);
    }

    protected function tearDown()
    {
        parent::tearDown();

        foreach ($this->shipments as $shipment) {
            $shipment->cancel();
        }
    }
}