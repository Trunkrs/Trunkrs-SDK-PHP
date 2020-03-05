<?php

namespace Trunkrs\SDK;

/**
 * Class PackageOwner
 */
class PackageOwner {
    private static function applyV1(PackageOwner $owner, array $json) {
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