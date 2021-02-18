<?php

namespace Trunkrs\SDK;

class WebhookV2MappingTest extends APIV2TestCase
{
    public function testShouldMapV2Response() {
        $webhook = Mocks::getFakeWebhook();
        $json = MockV2Responses::getFakeWebhookBody($webhook);

        $subject = new Webhook($json);

        $this->assertEquals($webhook->id, $subject->id);
        $this->assertEquals($webhook->callbackUrl, $subject->callbackUrl);
        $this->assertEquals($webhook->sessionHeaderName, $subject->sessionHeaderName);
        $this->assertEquals($webhook->sessionToken, $subject->sessionToken);
        $this->assertEquals($webhook->event, $subject->event);
    }

    public function testShouldMapToV2Request() {
        $subject = Mocks::getFakeWebhook();

        $json = $subject->serialize();

        $this->assertEquals($subject->callbackUrl, $json['url']);
        $this->assertEquals($subject->sessionHeaderName, $json['header']['key']);
        $this->assertEquals($subject->sessionToken, $json['header']['token']);
        $this->assertEquals($subject->event, $json['event']);
    }
}