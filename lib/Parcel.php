<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Util\SerializableInterface;

/**
 * Class Parcel
 * @package Trunkrs\SDK
 */
class Parcel implements SerializableInterface
{
    private static function applyV2(Parcel $parcel, $json) {
        $parcel->reference = $json->reference;
        $parcel->description = $json->description;
        $parcel->measurements = new ParcelMeasurements($json);
        $parcel->contents = array_map(function ($contentItem) {
            return new ParcelContent($contentItem);
        }, $json->contents);
    }

    private static function toV2Request(Parcel $parcel): array {
        $measurements = isset($parcel->measurements)
            ? $parcel->measurements->serialize()
            : [];

        $details = [
            'reference' => $parcel->reference,
            'description' => $parcel->description,
            'contents' => !is_null($parcel->contents)
                ? array_map(function ($contentItem) { return $contentItem->serialize(); }, $parcel->contents)
                : null,
        ];

        return array_merge($details, $measurements);
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
        $this->measurements = new ParcelMeasurements();

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
            case 2:
                return self::toV2Request($this);
        }
    }
}