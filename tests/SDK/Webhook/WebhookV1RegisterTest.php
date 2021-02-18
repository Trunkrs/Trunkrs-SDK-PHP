<?php

namespace Trunkrs\SDK;

class WebhookV1RegisterTest extends APIV1TestCase {
    private $webhook;

    public function setUp()
    {
        parent::setUp();

        $this->webhook = Mocks::getFakeWebhook();
    }

    public function testShouldPostWebhookData() {
        $this->mockResponseCallback(function($method, $url, $headers, $params) {
            $this->assertEquals("POST", $method);

            $this->assertArraySubset([
                "url" => $this->webhook->callbackUrl,
                "key" => $this->webhook->sessionHeaderName,
                "token" => $this->webhook->sessionToken,
            ], $params);

            return [
                "status" => 201,
                "headers" => [],
                "body" => json_encode(MockV1Responses::getFakeWebhookBody($this->webhook)),
            ];
        });

        Webhook::register($this->webhook);
    }

    public function testShouldCreateAWebhook() {
        $this->mockResponse(201, MockV1Responses::getFakeWebhookBody($this->webhook));

        $webhookResult = Webhook::register($this->webhook);

        $this->assertInstanceOf(Webhook::class, $webhookResult);
        $this->assertEquals($this->webhook, $webhookResult);
    }
}