<?php

namespace Trunkrs\SDK;

class WebhookV2RetrieveTest extends APIV2TestCase
{
    public function testShouldExecuteGetRequest() {
        $this->mockResponseCallback(function ($method) {
            $this->assertSame("GET", $method);

            return [
                "status" => 200,
                "body" => json_encode([
                    'data' => [
                      MockV2Responses::getFakeWebhookBody(),
                      MockV2Responses::getFakeWebhookBody(),
                    ],
                ]),
            ];
        });

        Webhook::retrieve();
    }

    public function testShouldReturnWebhooks() {
        $webhooks = [
          Mocks::getFakeWebhook(),
          Mocks::getFakeWebhook(),
          Mocks::getFakeWebhook(),
        ];

        $this->mockResponse(200, [
            'data' => [
                MockV2Responses::getFakeWebhookBody($webhooks[0]),
                MockV2Responses::getFakeWebhookBody($webhooks[1]),
                MockV2Responses::getFakeWebhookBody($webhooks[2]),
            ],
        ]);

        $results = Webhook::retrieve();

        $this->assertCount(3, $results);
        foreach ($results as $index => $result) {
            $this->assertInstanceOf(Webhook::class, $result);
        }
    }
}