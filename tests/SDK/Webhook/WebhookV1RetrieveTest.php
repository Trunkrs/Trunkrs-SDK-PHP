<?php

namespace Trunkrs\SDK;

class WebhookV1RetrieveTest extends APIV1TestCase {
    private $webhooks;

    public function setUp()
    {
        parent::setUp();

        $this->webhooks = [
            Mocks::getFakeWebhook(),
            Mocks::getFakeWebhook(),
            Mocks::getFakeWebhook(),
        ];
    }

    public function testShouldExecuteGetRequest() {
        $this->mockResponseCallback(function ($method) {
            $this->assertSame("GET", $method);

            return [
                "status" => 200,
                "body" => json_encode(array_map(function ($webhook) {
                    return MockV1Responses::getFakeWebhookBody($webhook);
                }, $this->webhooks)),
            ];
        });

        Webhook::retrieve();
    }

    public function testShouldReturnWebhooks() {
        $this->mockResponse(200, array_map(function ($webhook) {
            return MockV1Responses::getFakeWebhookBody($webhook);
        }, $this->webhooks));

        $results = Webhook::retrieve();

        $this->assertCount(3, $results);
        foreach ($results as $index => $result) {
            $this->assertInstanceOf(Webhook::class, $result);
            $this->assertEquals($this->webhooks[$index], $result);
        }
    }
}