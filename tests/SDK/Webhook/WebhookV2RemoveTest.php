<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Exception\WebhookNotFoundException;

class WebhookV2RemoveTest extends APIV2TestCase
{
    public function testShouldExecuteDeleteRequest() {
        $webhookId = Mocks::getGenerator()->randomNumber();
        $this->mockResponseCallback(function ($method, $url) use ($webhookId) {
            $this->assertSame("DELETE", $method);
            $this->assertContains(strval($webhookId), $url);

            return ["status" => 202];
        });

        Webhook::removeById($webhookId);
    }

    public function testShouldRemoveWebhookThroughInstance() {
        $webhook = Mocks::getFakeWebhook();
        $this->mockResponseCallback(function ($method, $url) use ($webhook) {
            $this->assertSame("DELETE", $method);
            $this->assertContains(strval($webhook->id), $url);

            return ["status" => 202];
        });

        $webhook->remove();
    }

    public function testShouldThrowWhenWebhookNotFound() {
        $webhookId = Mocks::getGenerator()->randomNumber();
        $this->expectException(WebhookNotFoundException::class);
        $this->mockResponse(404);

        Webhook::removeById($webhookId);
    }
}