<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Exception\GeneralApiException;
use Trunkrs\SDK\Exception\WebhookNotFoundException;
use Trunkrs\SDK\Util\JsonDateTime;

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

    private static function applyV1(Webhook $webhook, $json) {
        $webhook->id = $json->id;
        $webhook->callbackUrl = $json->url ?? '';
        $webhook->sessionHeaderName = $json->key;
        $webhook->sessionToken = $json->token;
        $webhook->uponShipmentCreation = $json->uponShipmentCreation;
        $webhook->uponLabelReady = $json->uponLabelReady;
        $webhook->uponStatusUpdate = $json->uponStatusUpdate;
        $webhook->uponShipmentCancellation = $json->uponShipmentCancellation;
        $webhook->createdAt = JsonDateTime::from($json->created_at);
    }

    /**
     * Registers the web hook based on specified web hook settings.
     *
     * @param Webhook $webhook The web hook settings for creation.
     * @return Webhook The newly created web hook registration.
     * @throws Exception\GeneralApiException
     * @throws Exception\NotAuthorizedException
     * @throws Exception\ServerValidationException
     */
    public static function register(Webhook $webhook): Webhook {
        switch (Settings::$apiVersion) {
            case 1:
                $json = RequestHandler::post("webhooks", self::toV1Request($webhook));
                return new Webhook($json);
        }
    }

    /**
     * Find the details for the specified web hook by its identifier.
     *
     * @param string $id The identifier of the web hook.
     * @return Webhook The web hook registration.
     * @throws Exception\WebhookNotFoundException
     * @throws Exception\GeneralApiException
     * @throws Exception\NotAuthorizedException
     */
    public static function find(string $id): Webhook {
        try {
            $json = RequestHandler::get(sprintf("webhooks/%d", $id));
            return new Webhook($json);
        } catch (GeneralApiException $exception) {
            $isWebhookNotFound = $exception->getStatusCode() == 404;
            if ($isWebhookNotFound)  {
                throw new WebhookNotFoundException($id);
            }
            throw $exception;
        }
    }

    /**
     * Retrieves all currently registered web hooks.
     *
     * @return array An array of Webhook
     * @throws Exception\GeneralApiException
     * @throws Exception\NotAuthorizedException
     */
    public static function retrieve(): array {
        $jsonResult = RequestHandler::get('webhooks');

        return array_map(function ($json) {
            return new Webhook($json);
        }, $jsonResult);
    }

    /**
     * Removes the specified web hook by its identifier.
     *
     * @param string $id The identifier of the web hook to remove.
     * @throws Exception\WebhookNotFoundException
     * @throws Exception\GeneralApiException
     * @throws Exception\NotAuthorizedException
     */
    public static function removeById(string $id) {
        try {
            RequestHandler::delete(sprintf('webhooks/%d', $id));
        } catch (GeneralApiException $exception) {
            $isWebhookNotFound = $exception->getStatusCode() == 404;
            if ($isWebhookNotFound)  {
                throw new WebhookNotFoundException($id);
            }
            throw $exception;
        }
    }

    /**
     * @var string $id The identifier for the web hook. Only available after creation of the web hook.
     */
    public $id;

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
     * @deprecated Please use the generic status update web hook instead. Only available when using the V1 API.
     */
    public $uponShipmentCreation = true;

    /**
     * @var bool Enables updates for when shipment labels have been created.
     * @deprecated Please use the generic status update web hook instead. Only available when using the V1 API.
     */
    public $uponLabelReady = true;

    /**
     * @var bool $uponStatusUpdate Enables updates when the shipment status changes.
     */
    public $uponStatusUpdate = true;

    /**
     * @var bool $uponShipmentCancellation Enables update when the shipment has been cancelled.
     * @deprecated Please use the generic status update web hook instead. Only available when using the V1 API.
     */
    public $uponShipmentCancellation = true;

    /**
     * @var \DateTime $createdAt The moment the web hook was created. Only available after creation of the web hook.
     */
    public $createdAt;

    /**
     * Webhook constructor.
     *
     * @param array|null $json Optional associative array to decode web hook from.
     */
    public function __construct($json = null) {
        if ($json) {
            switch (Settings::$apiVersion) {
                case 1:
                    self::applyV1($this, $json);
                    break;
            }
        }
    }

    /**
     * Removes the web hook registration and disables status updates.
     *
     * @throws Exception\WebhookNotFoundException
     * @throws Exception\GeneralApiException
     * @throws Exception\NotAuthorizedException
     */
    public function remove() {
        self::removeById($this->id);
    }
}
