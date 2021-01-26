<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Enum\ShipmentOwnerType;
use Trunkrs\SDK\Enum\ShipmentStatusLabel;
use Trunkrs\SDK\Util\JsonDateTime;

/**
 * Class ShipmentStatus
 */
class ShipmentState {
    private static function applyV1(ShipmentState $state, \stdClass $json) {
        $state->shipmentId = $json->shipmentId;
        $state->timestamp = JsonDateTime::from($json->timestamp);

        $state->owner = new ShipmentLog($json->stateObj);
        $state->state = new PackageOwner($json->currentOwner);
    }

    private static function applyV2(ShipmentState $state, \stdClass $json) {
        $state->timestamp = JsonDateTime::from($json->timestamp);
        $state->owner = new PackageOwner($json->currentOwner);
        $state->state = new ShipmentLog($json);
    }

    /**
     * Retrieves the current shipment state for the specified shipment.
     *
     * @param int $shipmentId The shipment identifier of the shipment to retrieve the state for.
     * @return ShipmentState The shipment state.
     * @throws Exception\NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws Exception\GeneralApiException When the API responds with an unexpected answer.
     */
    public static function forShipment(int $shipmentId): ShipmentState {
        $jsonResult = RequestHandler::get(sprintf('state/%d', $shipmentId));
        return new ShipmentState($jsonResult);
    }

    /**
     * @deprecated Removed from API version 2.
     * @var int Shipment id the state belongs to.
     */
    public $shipmentId;

    /**
     * @var PackageOwner $owner The current owner of the package.
     */
    public $owner;

    /**
     * @var ShipmentLog $state The current state of the shipment.
     */
    public $state;

    /**
     * @var \DateTime $timestamp Timestamp of when the state has been applied.
     */
    public $timestamp;

    /**
     * ShipmentStatus constructor.
     * @param \stdClass|null $json Optional associative array to decode shipment state from.
     */
    public function __construct($json = null)
    {
        if ($json) {
            switch (Settings::$apiVersion) {
                case 1:
                    self::applyV1($this, $json);
                    break;
                case 2:
                    self:self::applyV2($this, $json);
                    break;
            }
        }
    }
}