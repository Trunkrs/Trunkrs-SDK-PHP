<?php

namespace Trunkrs\SDK;

class Webhook {
    private static function toV1Request(Webhook $webhook):array {
        return [
            'url' => $webhook->callbackUrl,
            'key' => $webhook->sessionHeaderName,
            'token' => $webhook->sessionToken,
            'uponShipmentCreation' => $webhook->uponShipmentCreation,
            'uponLabelReady' => $webhook->uponLabelReady,
            'uponStatusUpdate' => $webhook->uponStatusUpdate,
            'uponShipmentCancellation' => $webhook->uponShipmentCancellation,
        ];
    }

    /**
     * Registers the web hook based on specified web hook settings.
     *
     * @param Webhook $webhook The web hook settings for creation.
     * @throws Exception\GeneralApiException
     * @throws Exception\NotAuthorizedException
     * @throws Exception\ServerValidationException
     */
    public function create(Webhook $webhook) {
        switch (Settings::$apiVersion) {
            case 1:
                RequestHandler::post("webhooks", self::toV1Request($webhook));
                break;
        }
    }

    /**
     * @var string $callbackUrl The callback URL for the web hook.
     */
    public $callbackUrl;

    /**
     * @var string $sessionHeaderName Optionally specify the header in which the session token will be communicated. Defaults to X-API-Token.
     */
    public $sessionHeaderName = 'X-API-Token';

    /**
     * @var string $sessionToken A token that will be added in the specified session header.
     */
    public $sessionToken;

    /**
     * @var bool Enables updates for shipment creation.
     */
    public $uponShipmentCreation = true;

    /**
     * @var bool Enables updates for when shipment labels have been created.
     */
    public $uponLabelReady = true;

    /**
     * @var bool $uponStatusUpdate Enables updates when the shipment status changes.
     */
    public $uponStatusUpdate = true;

    /**
     * @var bool $uponShipmentCancellation Enables update when the shipment has been cancelled.
     */
    public $uponShipmentCancellation = true;
}
