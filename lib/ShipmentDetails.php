<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Enum\ShipmentService;
use Trunkrs\SDK\Util\Defaults;
use Trunkrs\SDK\Util\SerializableInterface;

class ShipmentDetails implements SerializableInterface {
    private static function toV1Request(ShipmentDetails $details): array {
        $firstParcel = $details->parcels[0];
        $measurementsJson = $firstParcel->measurements->serialize();
        $pickupAddressJson = $details->sender->serialize('pickup');
        $recipientAddressJson = $details->recipient->serialize('delivery');

        $generalJson = [
            'orderReference' => $firstParcel->reference,
            'goodsDescription' => $firstParcel->description,
            'totalQuantity' => count($details->parcels),
        ];
        if ($details->timeSlotId != -1) {
            $generalJson['timeSlotId'] = $details->timeSlotId;
        }

        return array_merge(
            $generalJson,
            $measurementsJson,
            $pickupAddressJson,
            $recipientAddressJson
        );
    }

    private static function toV2Request(ShipmentDetails $details) {
        return [
            'sender' => $details->sender->serialize(),
            'recipient' => $details->recipient->serialize(),
            'parcel' => array_map(function ($parcel) {
                return $parcel->serialize();
            }, $details->parcels),
            'featureCodes' => $details->featureCodes->serialize(),
            'timeSlotId' => $details->timeSlotId,
            'service' => $details->service,
        ];
    }

    /**
     * @var Parcel[] Details about the parcel.
     */
    public $parcels;

    /**
     * @see TimeSlot
     * @var int Optional time slot id. Default is next available based on data cut-off.
     */
    public $timeSlotId = -1;

    /**
     * @var FeatureCodes The feature codes for this shipment.
     */
    public $featureCodes;

    /**
     * @see ShipmentService
     * @var string The service level of the shipment.
     */
    public $service;

    /**
     * @var Address The sender address of which to arrange pick-ups.
     */
    public $sender;

    /**
     * @var Address The recipient address to which to deliver this shipment.
     */
    public $recipient;

    public function __construct()
    {
        $this->service = Defaults::getDefaultService();
        $this->featureCodes = Defaults::getDefaultFeatureCodes();
    }

    /**
     * @internal
     * @return array JSON encodable array.
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
