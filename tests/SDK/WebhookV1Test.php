<?php

namespace Trunkrs\SDK;

class WebhookV1Test extends APIV1TestCase {
    public function testShouldPostWebhookData() {
        $webhook = Mocks::getFakeWebhook();
        $this->mockResponseCallback(function($method, $url, $headers, $params) use ($webhook) {
            $this->assertEquals("POST", $method);

            $this->assertArraySubset([
                "url" => $webhook->callbackUrl,
                "key" => $webhook->sessionHeaderName,
                "token" => $webhook->sessionToken,
            ], $params);

            return ["status" => 204];
        });

        Webhook::register($webhook);
    }
}