<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Enum\ShipmentOwnerType;
use Trunkrs\SDK\Enum\ShipmentStatusLabel;
use Trunkrs\SDK\Util\JsonDateTime;

/**
 * Class ShipmentLog
 */
class ShipmentLog {
    public static function applyV1(ShipmentLog $log, array $json) {
        $log->id = $json['id'];
        $log->label = $json['label'];
        $log->name = $json['name'];
        $log->description = $json['status'];
        $log->reason = $json['reasonCode'];
    }

    /**
     * @var int $id The numeric state identifier.
     */
    public $id;

    /**
     * @see ShipmentStatusLabel
     * @var string $label The state identifier label.
     */
    public $label;

    /**
     * @var string $name The name of the shipment state.
     */
    public $name;

    /**
     * @var string $description The human description of the shipment state.
     */
    public $description;

    /**
     * @var string|null $reason The reason why this state was applied. Only applies to shipments in the SHIPMENT_NOT_DELIVERED state.
     */
    public $reason;

    public function __construct(array $json = null)
    {
        if ($json) {
            switch (Settings::$apiVersion) {
                case 1:
                    self::applyV1($this, $json);
                    break;
            }
        }
    }
}

/**
 * Class PackageOwner
 */
class PackageOwner {
    public static function applyV1(PackageOwner $owner, array $json) {
        $owner->type = $json['type'];
        $owner->name = $json['name'];
        $owner->addressLine = $json['address'];
        $owner->postal = $json['postCode'];
        $owner->city = $json['city'];
        $owner->country = $json['country'];
    }

    /**
     * @see ShipmentOwnerType
     * @var string $type The owner type.
     */
    public $type;

    /**
     * @var string $name Name of the package owner.
     */
    public $name;

    /**
     * @var string $addresLine Address line of the package owner.
     */
    public $addressLine;

    /**
     * @var string $postal Postal code of the package owner.
     */
    public $postal;

    /**
     * @var string $city City of the package owner.
     */
    public $city;

    /**
     * @var string Country code of the package owner.
     */
    public $country;

    /**
     * PackageOwner constructor.
     * @param array|null $json
     */
    public function __construct(array $json = null)
    {
        if ($json) {
            switch (Settings::$apiVersion) {
                case 1:
                    self::applyV1($this, $json);
                    break;
            }
        }
    }
}

/**
 * Class ShipmentState
 */
class ShipmentState {
    private static function applyV1(ShipmentState $state, array $json) {
        $state->shipmentId = $json['shipmentId'];
        $state->timestamp = JsonDateTime::from($json['timestamp']);

        $state->owner = new ShipmentLog($json['stateObj']);
        $state->state = new PackageOwner($json['currentOwner']);
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
     * ShipmentState constructor.
     * @param array|null $json Optional associative array to decode shipment state from.
     */
    public function __construct(array $json = null)
    {
        if ($json) {
            switch (Settings::$apiVersion) {
                case 1:
                    self::applyV1($this, $json);
                    break;
            }
        }
    }
}