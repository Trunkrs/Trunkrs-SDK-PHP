<?php


namespace Trunkrs\SDK\Exception;


class WebhookNotFoundException extends \Exception
{
    /**
     * WebhookNotFoundException constructor.
     * @param string $webhookId The web hook identifier.
     */
    public function __construct($webhookId)
    {
        parent::__construct(sprintf("The web hook with id '%d' couldn't be found.", $webhookId));
    }
}