<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Exception\WebhookNotFoundException;

class WebhookV1RemoveTest extends APIV1TestCase
{
    private $webhookId;
    private $webhook;

    public function setUp()
    {
        parent::setUp();

        $this->webhookId = Mocks::getGenerator()->randomNumber();
        $this->webhook = Mocks::getFakeWebhook();
    }

    public function testShouldExecuteDeleteRequest() {
        $this->mockResponseCallback(function ($method, $url) {
            $this->assertSame("DELETE", $method);
            $this->assertContains(strval($this->webhookId), $url);

            return ["status" => 204];
        });

        Webhook::removeById($this->webhookId);
    }

    public function testShouldRemoveWebhookThroughInstance() {
        $this->mockResponseCallback(function ($method, $url) {
            $this->assertSame("DELETE", $method);
            $this->assertContains(strval($this->webhook->id), $url);

            return ["status" => 204];
        });

        $this->webhook->remove();
    }

    public function testShouldThrowWhenWebhookNotFound() {
        $this->expectException(WebhookNotFoundException::class);
        $this->mockResponse(404);

        Webhook::removeById($this->webhookId);
    }
}