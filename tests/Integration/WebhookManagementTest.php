<?php

namespace Trunkrs\SDK;

class WebhookManagementTest extends IntegrationTestCase
{
    public function testShouldListWebhooks() {
        $webhook = Mocks::getFakeWebhook();

        $resultWebhook = Webhook::register($webhook);

        $this->assertInstanceOf(Webhook::class, $webhook);
        $this->assertEquals($webhook->callbackUrl, $resultWebhook->callbackUrl);

        $webhooks = Webhook::retrieve();

        $this->assertTrue(count($webhooks) > 0);

        $resultWebhook->remove();
    }
}