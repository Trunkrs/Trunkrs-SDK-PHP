<?php

namespace Trunkrs\SDK;

class ShipmentDetails {
    private static function toV1Request(ShipmentDetails $details): array {
        return [
            'orderReference' => $details->reference,
            'weight' => $details->weight,
            'volume' => $details->volume,
            'width' => $details->width,
            'height' => $details->height,
            'goodsDescription' => $details->description,
            'totalQuantity' => $details->quantity,
        ];
    }

    /**
     * @var string The external reference to a shipment. This can be a order reference or some other identifier in your system.
     */
    public $reference;

    /**
     * @var string $weight Optional weight descriptor of the shipment.
     */
    public $weight = '';

    /**
     * @var string $volume Optional volume descriptor of the shipment.
     */
    public $volume = '';

    /**
     * @var string $width Optional width descriptor of the shipment.
     */
    public $width = '';

    /**
     * @var string $height Optional height descriptor of the shipment.
     */
    public $height = '';

    /**
     * @var string $description Description of the goods inside this shipment for customs or internal use.
     */
    public $description = '';

    /**
     * @var int $quantity Optionally defines how many physical parcels are part of the shipment. For every parcel a unique label must be generated.
     */
    public $quantity = 1;

    /**
     * @internal
     * @return array JSON encodable array.
     */
    function serialize(): array {
        switch (Settings::$apiVersion) {
            case 1:
                return self::toV1Request($this);
        }
    }
}
