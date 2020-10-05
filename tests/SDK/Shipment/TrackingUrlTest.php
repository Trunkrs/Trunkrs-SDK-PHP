<?php

namespace Trunkr\SDK;

use PHPUnit\Framework\TestCase;
use Trunkrs\SDK\APIV1TestCase;
use Trunkrs\SDK\MockV1Responses;
use Trunkrs\SDK\Shipment;

class TrackingUrlTest extends APIV1TestCase {
    public function testCreatesValidTrackingURL() {
        $shipment = new Shipment(MockV1Responses::getFakeShipmentBody());

        $trackingUrl = $shipment->getTrackingUrl();

        $this->assertStringEndsWith(
            sprintf('%s/%s', $shipment->trunkrsNr, $shipment->recipient->postal),
            $trackingUrl
        );
    }
}