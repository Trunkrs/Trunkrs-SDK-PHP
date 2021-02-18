<?php


namespace Trunkrs\SDK;

use Trunkrs\SDK\Exception\WebhookNotFoundException;

class WebhookV2FindTest extends APIV2TestCase
{
    public function testShouldExecuteAGetRequest() {
        $webhookId = Mocks::getGenerator()->randomNumber();
        $this->mockResponseCallback(function ($method) use ($webhookId) {
            $this->assertSame("GET", $method);

            return [
                "status" => 200,
                "body" => json_encode([
                    'data' => MockV2Responses::getFakeWebhookBody(),
                ]),
            ];
        });

        Webhook::find($webhookId);
    }

    public function testShouldFindWebhookById() {
        $webhookId = Mocks::getGenerator()->randomNumber();
        $this->mockResponseCallback(function ($method, $url) use ($webhookId) {
            $this->assertContains(strval($webhookId), $url);

            return [
                "status" => 200,
                "body" => json_encode([
                    'data' => MockV2Responses::getFakeWebhookBody(),
                ]),
            ];
        });

        $result = Webhook::find($webhookId);

        $this->assertInstanceOf(Webhook::class, $result);
    }

    public function testShouldThrowWhenNotFound() {
        $this->expectException(WebhookNotFoundException::class);
        $this->mockResponse(404);

        Webhook::find(100);
    }
}