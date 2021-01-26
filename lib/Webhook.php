<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Enum\WebhookEvent;
use Trunkrs\SDK\Exception\GeneralApiException;
use Trunkrs\SDK\Exception\WebhookNotFoundException;
use Trunkrs\SDK\Util\JsonDateTime;
use Trunkrs\SDK\Util\SerializableInterface;

class Webhook implements SerializableInterface {
    private static function applyV1(Webhook $webhook, \stdClass $json)
    {
        $webhook->id = $json->id;
        $webhook->callbackUrl = $json->url;
        $webhook->sessionHeaderName = $json->key;
        $webhook->sessionToken = $json->token;

        if ($json->uponStatusUpdate) {
            $webhook->event = WebhookEvent::ON_STATE_UPDATE;
        } else if ($json->uponShipmentCreation) {
            $webhook->event = WebhookEvent::ON_CREATION;
        } else if ($json->uponShipmentCancellation) {
            $webhook->event = WebhookEvent::ON_CANCELLATION;
        }

        $webhook->createdAt = JsonDateTime::from($json->createdAt);
    }

    private static function applyV2(Webhook $hook, \stdClass $json) {
        $hook->id = $json->id;
        $hook->callbackUrl = $json->url;
        $hook->sessionHeaderName = $json->header->key;
        $hook->sessionToken = $json->header->token;
        $hook->event = $json->event;
    }

    private function toV1Request(): array {
        return [
            'url' => $this->callbackUrl,
            'key' => $this->sessionHeaderName,
            'token' => $this->sessionToken,
            'uponShipmentCreation' => $this->event == WebhookEvent::ON_CREATION,
            'uponLabelReady' => false,
            'uponStatusUpdate' => $this->event == WebhookEvent::ON_STATE_UPDATE,
            'uponShipmentCancellation' => $this->event == WebhookEvent::ON_CANCELLATION,
        ];
    }

    private function toV2Request(): array {
        return [
            'url' => $this->callbackUrl,
            'header' => [
                'key' => $this->sessionHeaderName,
                'token' => $this->sessionToken,
            ],
            'event' => $this->event,
        ];
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
        $json = RequestHandler::post("webhooks", $webhook->serialize());

        return new Webhook($json);
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
    public $sessionHeaderName = 'X-API-Key';

    /**
     * @var string $sessionToken A token that will be added in the specified session header.
     */
    public $sessionToken;

    /**
     * @var string The event on which to fire this webhook.
     * @see WebhookEvent
     * @since 2.0.0
     */
    public $event;

    /**
     * @var \DateTime $createdAt The moment the web hook was created. Only available after creation of the web hook.
     * @deprecated Removed as of API version 2.
     */
    public $createdAt;

    /**
     * Webhook constructor.
     *
     * @param \stdClass|null $json Optional associative array to decode web hook from.
     */
    public function __construct($json = null) {
        if ($json) {
            switch (Settings::$apiVersion) {
                case 1:
                    self::applyV1($this, $json);
                    break;
                case 2:
                    self::applyV2($this, $json);
                    break;
            }
        }
    }

    /**
     * @internal
     */
    function serialize(): array
    {
        switch (Settings::$apiVersion) {
            case 1:
                return self::toV1Request();
            case 2:
                return self::toV2Request();
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
