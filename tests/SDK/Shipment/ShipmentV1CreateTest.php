<?php

namespace Trunkrs\SDK;

class ShipmentV1CreateTest extends APIV1TestCase {
    private $shipmentId;
    private $trunkrsNr;
    private $labelUrl;

    private $pickupAddress;
    private $deliveryAddress;
    private $details;

    public function setUp()
    {
        parent::setUp();

        $this->shipmentId = Mocks::getGenerator()->randomNumber();
        $this->trunkrsNr = Mocks::getTrunkrsNr();
        $this->labelUrl = Mocks::getGenerator()->url;

        $this->pickupAddress = Mocks::getFakeAddress();
        $this->deliveryAddress = Mocks::getFakeAddress();
        $this->details = Mocks::getFakeDetails();
    }

    public function testShouldExecuteAPostRequest() {
        $this->mockResponseCallback(function ($method) {
            $this->assertEquals("POST", $method);

            return [
                "status" => 200,
                "headers" => [],
                "body" => json_encode(Mocks::getFakeShipmentBody(
                    $this->shipmentId,
                    $this->trunkrsNr,
                    $this->labelUrl,
                    $this->pickupAddress,
                    $this->deliveryAddress
                )),
            ];
        });

        Shipment::create($this->details, $this->pickupAddress, $this->deliveryAddress);
    }

    public function testShouldCreateAV1Shipment() {
        $this->mockResponseCallback(function ($method, $url, $headers, $params) {
            $this->assertArraySubset([
                "orderReference" => $this->details->reference,
                "weight" => $this->details->weight,
                "volume" => $this->details->volume,
                "width" => $this->details->width,
                "height" => $this->details->height,
                "goodsDescription" => $this->details->description,
                "totalQuantity" => $this->details->quantity,
                "pickupName" => $this->pickupAddress->companyName,
                "pickupContact" => $this->pickupAddress->contactName,
                "pickupAddress" => $this->pickupAddress->addressLine,
                "pickupCity" => $this->pickupAddress->city,
                "pickupPostCode" => $this->pickupAddress->postal,
                "pickupCountry" => $this->pickupAddress->country,
                "pickupEmail" => $this->pickupAddress->email,
                "pickupTell" => $this->pickupAddress->phone,
                "pickupRemarks" => $this->pickupAddress->remarks,
                "deliveryName" => $this->deliveryAddress->companyName,
                "deliveryContact" => $this->deliveryAddress->contactName,
                "deliveryAddress" => $this->deliveryAddress->addressLine,
                "deliveryCity" => $this->deliveryAddress->city,
                "deliveryPostCode" => $this->deliveryAddress->postal,
                "deliveryCountry" => $this->deliveryAddress->country,
                "deliveryEmail" => $this->deliveryAddress->email,
                "deliveryTell" => $this->deliveryAddress->phone,
                "deliveryRemarks" => $this->deliveryAddress->remarks,
            ], $params);

            return [
                "status" => 200,
                "headers" => [],
                "body" => json_encode(Mocks::getFakeShipmentBody(
                    $this->shipmentId,
                    $this->trunkrsNr,
                    $this->labelUrl,
                    $this->pickupAddress,
                    $this->deliveryAddress
                )),
            ];
        });

        $shipmentResult = Shipment::create(
            $this->details,
            $this->pickupAddress,
            $this->deliveryAddress
        );

        $this->assertCount(1, $shipmentResult);

        $shipment = $shipmentResult[0];
        $this->assertInstanceOf(Shipment::class, $shipment);
    }
}