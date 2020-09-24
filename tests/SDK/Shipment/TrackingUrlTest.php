<?php

namespace Trunkr\SDK;

use PHPUnit\Framework\TestCase;
use Trunkrs\SDK\MockV1Responses;
use Trunkrs\SDK\Shipment;
use Trunkr\SDK\Settings;

class TrackingUrlTest extends TestCase {
    public function testCreatesValidTrackingURL() {
        $shipment = new Shipment(MockV1Responses::getFakeShipmentBody());

        $trackingUrl = $shipment->getTrackingUrl();

        $this->assertEquals(
            sprintf('%s/%s/%s', Settings::$trackingBaseUrl, $shipment->trunkrsNr, $shipment->deliveryAddress->postal),
            $trackingUrl
        );
    }
}
