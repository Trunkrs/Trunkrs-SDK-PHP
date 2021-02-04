<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Enum\ShipmentOwnerType;
use Trunkrs\SDK\Enum\ShipmentStatusLabel;
use Trunkrs\SDK\Exception\GeneralApiException;
use Trunkrs\SDK\Exception\NotSupportedException;
use Trunkrs\SDK\Exception\ShipmentNotFoundException;
use Trunkrs\SDK\Util\JsonDateTime;
use Trunkrs\SDK\Util\ResultUnwrapper;

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
     * @since 2.0.0
     * @param string $trunkrsNr The shipment identifier of the shipment to retrieve the state for.
     * @return ShipmentState The shipment state.
     * @throws ShipmentNotFoundException When the specified shipment couldn't be found.
     * @throws Exception\NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws Exception\GeneralApiException When the API responds with an unexpected answer.
     */
    public static function forShipment(string $trunkrsNr): ShipmentState {
        if (Settings::$apiVersion == 1) {
            throw new NotSupportedException('Please use ShipmentState::forShipmentById in combination with the id instead.');
        }

        try {
            $response = RequestHandler::get(sprintf('shipments/%s/state', $trunkrsNr));
            $result = ResultUnwrapper::unwrap($response);

            return new ShipmentState($result);
        } catch (GeneralApiException $exception) {
            $isShipmentNotFound = $exception->getStatusCode() == 404;
            if ($isShipmentNotFound)  {
                throw new ShipmentNotFoundException($trunkrsNr);
            }
            throw $exception;
        }
    }

    /**
     * Retrieves the current shipment state for the specified shipment.
     *
     * @deprecated
     * @since 2.0.0
     * @param int $shipmentId The shipment identifier of the shipment to retrieve the state for.
     * @return ShipmentState The shipment state.
     * @throws Exception\NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws Exception\GeneralApiException When the API responds with an unexpected answer.
     */
    public static function forShipmentById(int $shipmentId): ShipmentState {
        if (Settings::$apiVersion == 2) {
            throw new NotSupportedException('Please use ShipmentState::forShipment in combination with the Trunkrs number instead.');
        }

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