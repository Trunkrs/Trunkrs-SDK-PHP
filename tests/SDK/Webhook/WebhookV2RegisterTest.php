<?php


namespace Trunkrs\SDK;

class WebhookV2RegisterTest extends APIV2TestCase
{
    public function testShouldPostWebhookData() {
        $webhook = Mocks::getFakeWebhook();

        $this->mockResponseCallback(function($method, $url, $headers, $params) use ($webhook) {
            $this->assertEquals("POST", $method);

            $this->assertArraySubset([
                "url" => $webhook->callbackUrl,
                "header" => [
                    "key" => $webhook->sessionHeaderName,
                    "token" => $webhook->sessionToken,
                ],
                'event' => $webhook->event,
            ], $params);

            return [
                "status" => 201,
                "headers" => [],
                "body" => json_encode([
                    'data' => MockV2Responses::getFakeWebhookBody($webhook),
                ]),
            ];
        });

        Webhook::register($webhook);
    }

    public function testShouldCreateAWebhook() {
        $webhook = Mocks::getFakeWebhook();
        // Deprecated
        $webhook->createdAt = null;

        $this->mockResponse(201, ['data' => MockV2Responses::getFakeWebhookBody($webhook)]);

        $webhookResult = Webhook::register($webhook);

        $this->assertInstanceOf(Webhook::class, $webhookResult);
        $this->assertEquals($webhook, $webhookResult);
    }
}