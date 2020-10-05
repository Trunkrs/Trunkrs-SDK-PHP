<?php

namespace Trunkrs\SDK;

/**
 * Class Parcel
 * @package Trunkrs\SDK
 */
class Parcel
{
    private static function applyV2(Parcel $parcel, $json) {
        $parcel->reference = $json->reference;
        $parcel->description = $json->description;
        $parcel->measurements = new ParcelMeasurements($json);
        $parcel->contents = new ParcelContent($json->contents);
    }

    private static function toV2Request(Parcel $parcel): array {
        return [
            'reference' => $parcel->reference,
            'description' => $parcel->description,
            'contents' => array_map(function ($contentItem) {
                return $contentItem->serialize();
            }, $parcel->contents),
            'weight' => $parcel->measurements->serialize(),
            'size' => [
                'width' => $parcel->measurements->width->serialize(),
                'height' => $parcel->measurements->height->serialize(),
                'depth' => $parcel->measurements->depth->serialize(),
            ],
        ];
    }

    /**
     * @var string Optional reference to this specific parcel. When provided, this value needs to be unique per parcel.
     */
    public $reference;

    /**
     * @var string A description of the parcel and its contents.
     */
    public $description;

    /**
     * @var ParcelContent[] The content of the parcel. Declaration of the parcel content is required when shipping outside the Netherlands.
     */
    public $contents;

    /**
     * @var ParcelMeasurements The measurements of the parcel. This is required when shipping outside of the Netherlands.
     */
    public $measurements;

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
            case 2:
                return self::toV2Request($this);
        }
    }
}