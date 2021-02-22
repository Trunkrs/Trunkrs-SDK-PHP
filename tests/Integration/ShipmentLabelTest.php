<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Enum\ShipmentLabelType;
use Trunkrs\SDK\Enum\ShipmentService;

class ShipmentLabelTest extends IntegrationTestCase
{
    /**
     * @var string|null
     */
    private $fileName = null;
    /**
     * @var Shipment|null
     */
    private $sut = null;

    protected function setUp()
    {
        parent::setUp();

        $this->fileName = tempnam(sys_get_temp_dir(), "TSDK-Test-Label-");
        var_dump($this->fileName);

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

        $this->sut = Shipment::create($details)[0];
    }

    public function testShouldDownloadAPDFLabel() {
        $this->sut->label->download($this->fileName, ShipmentLabelType::PDF);

        $this->assertNotEquals(filesize($this->fileName), 0);
    }

    public function testShouldDownloadAZPLabel() {
        $this->sut->label->download($this->fileName, ShipmentLabelType::ZPL);

        $this->assertNotEquals(filesize($this->fileName), 0);
    }

    protected function tearDown()
    {
        parent::tearDown();

        @unlink($this->fileName);
        if ($this->sut != null) {
            $this->sut->cancel();
        }
    }
}