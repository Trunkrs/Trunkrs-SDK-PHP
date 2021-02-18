<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Enum\WebhookEvent;

class WebhookV1MappingTest extends APIV1TestCase
{
    public function testShouldMapV1Response() {
        $webhook = Mocks::getFakeWebhook();
        $json = MockV1Responses::getFakeWebhookBody($webhook);

        $subject = new Webhook($json);

        $this->assertEquals($webhook->id, $subject->id);
        $this->assertEquals($webhook->callbackUrl, $subject->callbackUrl);
        $this->assertEquals($webhook->sessionHeaderName, $subject->sessionHeaderName);
        $this->assertEquals($webhook->sessionToken, $subject->sessionToken);
        $this->assertEquals($webhook->event, $subject->event);
        $this->assertEquals($webhook->createdAt, $subject->createdAt);
    }

    public function testShouldMapV1RequestForShipmentCreation() {
        $webhook = Mocks::getFakeWebhook(WebhookEvent::ON_CREATION);

        $json = $webhook->serialize();

        $this->assertEquals($webhook->callbackUrl, $json['url']);
        $this->assertEquals($webhook->sessionHeaderName, $json['key']);
        $this->assertEquals($webhook->sessionToken, $json['token']);
        $this->assertTrue($json['uponShipmentCreation']);
        $this->assertFalse($json['uponLabelReady']);
        $this->assertFalse($json['uponStatusUpdate']);
        $this->assertFalse($json['uponShipmentCancellation']);
    }

    public function testShouldMapV1RequestForShipmentState() {
        $webhook = Mocks::getFakeWebhook(WebhookEvent::ON_STATE_UPDATE);

        $json = $webhook->serialize();

        $this->assertEquals($webhook->callbackUrl, $json['url']);
        $this->assertEquals($webhook->sessionHeaderName, $json['key']);
        $this->assertEquals($webhook->sessionToken, $json['token']);
        $this->assertFalse($json['uponShipmentCreation']);
        $this->assertFalse($json['uponLabelReady']);
        $this->assertTrue($json['uponStatusUpdate']);
        $this->assertFalse($json['uponShipmentCancellation']);
    }

    public function testShouldMapV1RequestForCancellation() {
        $webhook = Mocks::getFakeWebhook(WebhookEvent::ON_CANCELLATION);

        $json = $webhook->serialize();

        $this->assertEquals($webhook->callbackUrl, $json['url']);
        $this->assertEquals($webhook->sessionHeaderName, $json['key']);
        $this->assertEquals($webhook->sessionToken, $json['token']);
        $this->assertFalse($json['uponShipmentCreation']);
        $this->assertFalse($json['uponLabelReady']);
        $this->assertFalse($json['uponStatusUpdate']);
        $this->assertTrue($json['uponShipmentCancellation']);
    }
}