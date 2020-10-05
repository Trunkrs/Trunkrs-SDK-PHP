<?php

namespace Trunkrs\SDK;

/**
 * Class ParcelContent
 * @package Trunkrs\SDK
 */
class ParcelContent
{
    private static function applyV2(ParcelContent $contentItem, $json) {
        $contentItem->reference = $json->reference;
        $contentItem->name = $json->name;
        $contentItem->remarks = $json->additionalRemarks;
    }

    private static function toV2Request(ParcelContent $contentItem): array {
        return [
            'reference' => $contentItem->reference,
            'name' => $contentItem->name,
            'additionalRemarks' => $contentItem->remarks,
        ];
    }

    /**
     * @var string Your internal product reference or product EAN.
     */
    public $reference;

    /**
     * @var string Product name for human legibility.
     */
    public $name;

    /**
     * @var string Optional remarks about the product.
     */
    public $remarks;

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