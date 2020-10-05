<?php

namespace Trunkrs\SDK;

class ParcelMeasurements {
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
        return [
            'weight' => $measurements->weight->serialize(),
            'size' => [
                'width' => $measurements->width->serialize(),
                'height' => $measurements->height->serialize(),
                'depth' => $measurements->depth->serialize(),
            ]
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
    function serialize() {
        switch (Settings::$apiVersion) {
            case 1:
                return self::toV1Request($this);
            case 2:
                return self::toV2Request($this);
        }
    }
}