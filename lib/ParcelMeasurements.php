<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Util\Defaults;
use Trunkrs\SDK\Util\SerializableInterface;

class ParcelMeasurements implements SerializableInterface {
    private static function applyV2(ParcelMeasurements $measurements, $json) {
        $measurements->weight = new Measurement($json->weight);
        $measurements->width = new Measurement($json->size->width);
        $measurements->height = new Measurement($json->size->height);
        $measurements->depth = new Measurement($json->size->depth);
    }

    private static function toV1Request(ParcelMeasurements $measurements) {
        return [
            'width' => $measurements->width->serialize(),
            'height' => $measurements->height->serialize(),
            'volume' => $measurements->depth->serialize(),
            'weight' => $measurements->weight->serialize(),
        ];
    }

    private static function toV2Request(ParcelMeasurements $measurements) {
        $hasSize = !is_null($measurements->width)
            || !is_null($measurements->height)
            || !is_null($measurements->depth);

        return [
            'weight' => $measurements->weight->serialize(),
            'size' => $hasSize
                ? [
                    'width' => !is_null($measurements->width)
                        ? $measurements->width->serialize()
                        : null,
                    'height' => !is_null($measurements->height)
                        ? $measurements->height->serialize()
                        : null,
                    'depth' => !is_null($measurements->depth)
                        ? $measurements->depth->serialize()
                        : null,
                ]
                : null,
        ];
    }

    /**
     * @var Measurement The width of the parcel.
     */
    public $width;

    /**
     * @var Measurement The height of the parcel.
     */
    public $height;

    /**
     * @var Measurement The depth of the parcel.
     */
    public $depth;

    /**
     * @var Measurement The weight of the parcel
     */
    public $weight;

    public function __construct($json = null) {
        $this->weight = Defaults::getDefaultWeight();

        if ($json) {
            switch (Settings::$apiVersion) {
                case 2:
                    self::applyV2($this, $json);
                    break;
            }
        }
    }

    /**
     * @internal
     */
    function serialize(): array {
        switch (Settings::$apiVersion) {
            case 1:
                return self::toV1Request($this);
            case 2:
                return self::toV2Request($this);
        }
    }
}