<?php


namespace Trunkrs\SDK;

/**
 * Class ServiceArea
 * @package Trunkrs\SDK
 */
class ServiceArea
{
    private static function applyV2(ServiceArea $area, \stdClass $json) {
        $area->country = $json->country;
        $area->region = $json->region;
    }

    /**
     * @var string $country The country code of the service area.
     */
    public $country;

    /**
     * @var string[] $region Three letter postal codes that define the contents of a service area.
     */
    public $region;

    public function __construct(\stdClass $json = null)
    {
        if ($json) {
            switch (Settings::$apiVersion) {
                case 2:
                    self::applyV2($this, $json);
            }
        }
    }
}