<?php


namespace Trunkrs\SDK;

class ShipmentV2CreateTest extends APIV2TestCase
{
    public function testShouldExecutePostRequest() {
        $trunkrsNr = Mocks::getTrunkrsNr();
        $serviceLevel = Mocks::getRandomServiceLevel();
        $state = Mocks::getFakeShipmentState();
        $labelUrls = Mocks::getFakeLabelUrls();
        $timeSlot = Mocks::getFakeTimeSlot();
        $featureCodes = Mocks::getFakeFeatureCodes();
        $details = Mocks::getFakeDetails();

        $this->mockResponseCallback(function ($method) use ($featureCodes, $timeSlot, $state, $labelUrls, $serviceLevel, $trunkrsNr, $details) {
            $this->assertEquals("POST", $method);

            return [
                "status" => 200,
                "headers" => [],
                "body" => json_encode(MockV2Responses::getFakeShipmentFromDetailsBody(
                    $trunkrsNr,
                    $serviceLevel,
                    $labelUrls,
                    $state,
                    $timeSlot,
                    $featureCodes,
                    $details
                )),
            ];
        });

        Shipment::create($details);
    }

    public function testShouldCreateAV2ShipmentRequest() {
        $trunkrsNr = Mocks::getTrunkrsNr();
        $serviceLevel = Mocks::getRandomServiceLevel();
        $state = Mocks::getFakeShipmentState();
        $labelUrls = Mocks::getFakeLabelUrls();
        $timeSlot = Mocks::getFakeTimeSlot();
        $featureCodes = Mocks::getFakeFeatureCodes();
        $details = Mocks::getFakeDetails();

        $this->mockResponseCallback(function ($method, $url, $headers, $params) use ($details, $featureCodes, $timeSlot, $state, $labelUrls, $serviceLevel, $trunkrsNr) {
            $this->assertArraySubset([
                'sender' => [
                    'companyName' => $details->sender->companyName,
                    'name' => $details->sender->contactName,
                    'emailAddress' => $details->sender->email,
                    'phoneNumber' => $details->sender->phone,
                    'address' => $details->sender->addressLine,
                    'postalCode' => $details->sender->postal,
                    'city' => $details->sender->city,
                    'country' => $details->sender->country,
                    'additionalRemarks' => $details->sender->remarks,
                ],
                'recipient' => [
                    'companyName' => $details->recipient->companyName,
                    'name' => $details->recipient->contactName,
                    'emailAddress' => $details->recipient->email,
                    'phoneNumber' => $details->recipient->phone,
                    'address' => $details->recipient->addressLine,
                    'postalCode' => $details->recipient->postal,
                    'city' => $details->recipient->city,
                    'country' => $details->recipient->country,
                    'additionalRemarks' => $details->recipient->remarks,
                ],
                'parcel' => array_map(function ($parcel) {
                    return [
                        'description' => $parcel->description,
                        'contents' => array_map(function ($item) {
                            return [
                              'name' => $item->name,
                              'reference' => $item->reference,
                              'additionalRemarks' => $item->remarks,
                            ];
                        }, $parcel->contents),
                        'weight' => [
                            'unit' => $parcel->measurements->weight->unit,
                            'value' => $parcel->measurements->weight->value,
                        ],
                        'size' => [
                            'width' => [
                                'unit' => $parcel->measurements->width->unit,
                                'value' => $parcel->measurements->width->value,
                            ],
                            'height' => [
                                'unit' => $parcel->measurements->height->unit,
                                'value' => $parcel->measurements->height->value,
                            ],
                            'depth' => [
                                'unit' => $parcel->measurements->depth->unit,
                                'value' => $parcel->measurements->depth->value,
                            ],
                        ],
                    ];
                }, $details->parcels),
                'timeSlotId' => $details->timeSlotId,
                'featureCodes' => [
                    'noNeighbourDelivery' => $details->featureCodes->noNeighbourDelivery,
                    'noSignature' => $details->featureCodes->noSignature,
                    'deliverInMailBox' => $details->featureCodes->deliverInMailBox,
                    'maxDeliveryAttempts' => $details->featureCodes->maxDeliveryAttempts,
                    'maxTimeOutsideFreezer' => $details->featureCodes->maxHoursOutsideFreezer,
                ],
                'service' => $details->service,
            ], $params);

            return [
                "status" => 200,
                "headers" => [],
                "body" => json_encode(MockV2Responses::getFakeShipmentFromDetailsBody(
                    $trunkrsNr,
                    $serviceLevel,
                    $labelUrls,
                    $state,
                    $timeSlot,
                    $featureCodes,
                    $details
                )),
            ];
        });

        Shipment::create($details);
    }

    public function testShouldReturnTheCreatedShipment() {
        $trunkrsNr = Mocks::getTrunkrsNr();
        $serviceLevel = Mocks::getRandomServiceLevel();
        $state = Mocks::getFakeShipmentState();
        $labelUrls = Mocks::getFakeLabelUrls();
        $timeSlot = Mocks::getFakeTimeSlot();
        $featureCodes = Mocks::getFakeFeatureCodes();
        $details = Mocks::getFakeDetails();

        $this->mockResponse(200, (object) [
            'data' => [
                MockV2Responses::getFakeShipmentFromDetailsBody(
                    $trunkrsNr,
                    $serviceLevel,
                    $labelUrls,
                    $state,
                    $timeSlot,
                    $featureCodes,
                    $details
                ),
            ]
        ]);

        $shipments = Shipment::create($details);

        $this->assertCount(1, $shipments);
        foreach ($shipments as $shipment) {
            $this->assertInstanceOf(Shipment::class, $shipment);
        }
    }
}