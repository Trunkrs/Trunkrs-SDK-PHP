<?php

namespace Trunkrs\SDK\Enum;

class WebhookEvent
{
    /**
     * Fired every time the shipment state is updated.
     */
    const ON_STATE_UPDATE = 'onStateUpdate';
    /**
     * Fired every time a new shipment has been created.
     */
    const ON_CREATION = 'onCreation';
    /**
     * Fired every time a shipment has been cancelled.
     */
    const ON_CANCELLATION = 'onCancellation';
    /**
     * Fired every time a shipment has been reviewed by a recipient.
     * @since 2.0.0 - Only supported in API version 2.
     */
    const ON_REVIEW = 'onReview';
}