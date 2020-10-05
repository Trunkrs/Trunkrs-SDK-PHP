<?php

namespace Trunkrs\SDK;

class ShipmentV1CreateTest extends APIV1TestCase {
    public function testShouldExecuteAPostRequest() {
        $shipmentId = Mocks::getGenerator()->randomNumber();
        $trunkrsNr = Mocks::getTrunkrsNr();
        $labelUrl = Mocks::getGenerator()->url;
        $details = Mocks::getFakeDetails();

        $this->mockResponseCallback(function ($method) use ($shipmentId, $trunkrsNr, $labelUrl, $details) {
            $this->assertEquals("POST", $method);

            return [
                "status" => 200,
                "headers" => [],
                "body" => json_encode(MockV1Responses::getFakeShipmentBody(
                    $shipmentId,
                    $trunkrsNr,
                    $labelUrl,
                    $details
                )),
            ];
        });

        Shipment::create($details);
    }

    public function testShouldCreateAV1Shipment() {
        $shipmentId = Mocks::getGenerator()->randomNumber();
        $trunkrsNr = Mocks::getTrunkrsNr();
        $labelUrl = Mocks::getGenerator()->url;
        $details = Mocks::getFakeDetails();

        $this->mockResponseCallback(function ($method, $url, $headers, $params) use ($details, $shipmentId, $trunkrsNr, $labelUrl) {
            $this->assertArraySubset([
                "orderReference" => $details->parcels[0]->reference,
                "weight" => $details->parcels[0]->measurements->weight->serialize(),
                "volume" => $details->parcels[0]->measurements->depth->serialize(),
                "width" => $details->parcels[0]->measurements->width->serialize(),
                "height" => $details->parcels[0]->measurements->height->serialize(),
                "goodsDescription" => $details->parcels[0]->description,
                "totalQuantity" => count($details->parcels),
                "pickupName" => $details->sender->companyName,
                "pickupContact" => $details->sender->contactName,
                "pickupAddress" => $details->sender->addressLine,
                "pickupCity" => $details->sender->city,
                "pickupPostCode" => $details->sender->postal,
                "pickupCountry" => $details->sender->country,
                "pickupEmail" => $details->sender->email,
                "pickupTell" => $details->sender->phone,
                "pickupRemarks" => $details->sender->remarks,
                "deliveryName" => $details->recipient->companyName,
                "deliveryContact" => $details->recipient->contactName,
                "deliveryAddress" => $details->recipient->addressLine,
                "deliveryCity" => $details->recipient->city,
                "deliveryPostCode" => $details->recipient->postal,
                "deliveryCountry" => $details->recipient->country,
                "deliveryEmail" => $details->recipient->email,
                "deliveryTell" => $details->recipient->phone,
                "deliveryRemarks" => $details->recipient->remarks,
                "timeSlotId" => $details->timeSlotId,
            ], $params);

            return [
                "status" => 200,
                "headers" => [],
                "body" => json_encode(MockV1Responses::getFakeShipmentBody(
                    $shipmentId,
                    $trunkrsNr,
                    $labelUrl,
                    $details
                )),
            ];
        });

        $shipmentResult = Shipment::create($details);

        $this->assertCount(1, $shipmentResult);

        $shipment = $shipmentResult[0];
        $this->assertInstanceOf(Shipment::class, $shipment);
    }
}
