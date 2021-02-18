<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Exception\WebhookNotFoundException;

class WebhookV1FindTest extends APIV1TestCase {
    private $webhook;
    private $webhookId;

    public function setUp()
    {
        parent::setUp();

        $this->webhook = Mocks::getFakeWebhook();
        $this->webhookId = $this->webhook->id;
    }

    public function testShouldExecuteAGetRequest() {
        $this->mockResponseCallback(function ($method) {
            $this->assertSame("GET", $method);

            return [
                "status" => 200,
                "body" => json_encode(MockV1Responses::getFakeWebhookBody($this->webhook)),
            ];
        });

        Webhook::find($this->webhookId);
    }

    public function testShouldFindWebhookById() {
        $this->mockResponseCallback(function ($method, $url) {
            $this->assertContains(strval($this->webhookId), $url);

            return [
                "status" => 200,
                "body" => json_encode(MockV1Responses::getFakeWebhookBody($this->webhook)),
            ];
        });

        $result = Webhook::find($this->webhookId);

        $this->assertInstanceOf(Webhook::class, $result);
    }

    public function testShouldThrowWhenNotFound() {
        $this->expectException(WebhookNotFoundException::class);
        $this->mockResponse(404);

        Webhook::find(100);
    }
}