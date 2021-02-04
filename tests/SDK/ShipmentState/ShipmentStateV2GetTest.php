<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Exception\NotSupportedException;
use Trunkrs\SDK\Exception\ShipmentNotFoundException;

class ShipmentStateV2GetTest extends APIV2TestCase
{
    public function testShouldEmitGetRequest() {
        $this->mockResponseCallback(function ($method) {
            $this->assertEquals("GET", $method);

            return [
                "status" => 200,
                "body" => json_encode(MockV2Responses::getFakeShipmentBody()),
            ];
        });

        ShipmentState::forShipment(Mocks::getTrunkrsNr());
    }

    public function testShouldRequestState() {
        $this->mockResponse(200, MockV1Responses::getShipmentStateBody());

        $result = ShipmentState::forShipment(Mocks::getTrunkrsNr());

        $this->assertInstanceOf(ShipmentState::class, $result);
    }

    public function testShouldThrowNotFoundException() {
        $this->expectException(ShipmentNotFoundException::class);
        $this->mockResponse(404);

        ShipmentState::forShipment(Mocks::getTrunkrsNr());
    }

    public function testShouldThrowNotSupportedException() {
        $this->expectException(NotSupportedException::class);

        ShipmentState::forShipmentById(Mocks::getTrunkrsNr());
    }
}