<?php

namespace Trunkr\SDK;

use PHPUnit\Framework\TestCase;
use Trunkrs\SDK\APIV1TestCase;
use Trunkrs\SDK\MockV1Responses;
use Trunkrs\SDK\Shipment;
use Trunkrs\SDK\Settings;

class TrackingUrlTest extends APIV1TestCase {
    public function testCreatesValidTrackingURL() {
        $shipment = new Shipment(MockV1Responses::getFakeShipmentBody());

        $trackingUrl = $shipment->getTrackingUrl();

        $this->assertEquals(
            sprintf('%s/%s/%s', Settings::$trackingBaseUrl, $shipment->trunkrsNr, $shipment->recipient->postal),
            $trackingUrl
        );
    }
}
