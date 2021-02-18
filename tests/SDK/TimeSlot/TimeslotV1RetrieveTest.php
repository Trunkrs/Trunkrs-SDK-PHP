<?php

namespace Trunkrs\SDK;


class TimeslotV1RetrieveTest extends APIV1TestCase {
    public function testShouldEmitGetRequest() {
        $postalCode = Mocks::getGenerator()->postcode;
        $this->mockResponseCallback(function($method) {
            $this->assertEquals("GET", $method);

            return [
                "status" => 200,
                "body" => json_encode([
                    MockV1Responses::getFakeTimeSlotBody(),
                    MockV1Responses::getFakeTimeSlotBody(),
                ]),
            ];
        });

        TimeSlot::retrieve($postalCode);
    }

    public function testShouldExecute() {
        $postalCode = Mocks::getGenerator()->postcode;
        $this->mockResponse(200, [
            MockV1Responses::getFakeTimeSlotBody(),
            MockV1Responses::getFakeTimeSlotBody(),
        ]);

        $timeSlots = TimeSlot::retrieve($postalCode);

        $this->assertCount(2, $timeSlots);
        foreach ($timeSlots as $timeSlot) {
            $this->assertInstanceOf(TimeSlot::class, $timeSlot);
        }
    }

    public function testShouldApplyPostalCodeParameter() {
        $postalCode = Mocks::getGenerator()->postcode;
        $countryCode = Mocks::getGenerator()->countryCode;
        $this->mockResponseCallback(function($method, $url, $headers, $params) use ($postalCode) {
            $this->assertArraySubset([
                'postalCode' => $postalCode,
            ], $params);

            return [
                "status" => 200,
                "body" => json_encode([MockV1Responses::getFakeTimeSlotBody()]),
            ];
        });

        TimeSlot::retrieve($postalCode, $countryCode);
    }

    public function testShouldApplyCountryParameter() {
        $postalCode = Mocks::getGenerator()->postcode;
        $countryCode = Mocks::getGenerator()->countryCode;
        $this->mockResponseCallback(function($method, $url, $headers, $params) use ($countryCode) {
            $this->assertArraySubset([
                'country' => $countryCode,
            ], $params);

            return [
                "status" => 200,
                "body" => json_encode([MockV1Responses::getFakeTimeSlotBody()]),
            ];
        });

        TimeSlot::retrieve($postalCode, $countryCode);
    }
}