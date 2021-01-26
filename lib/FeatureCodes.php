<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Util\SerializableInterface;

class FeatureCodes implements SerializableInterface
{
    private static function applyV2(FeatureCodes $codes, $json) {
        $codes->noNeighbourDelivery = $json->noNeighbourDelivery;
        $codes->noSignature = $json->noSignature;
        $codes->deliverInMailBox = $json->deliverInMailBox;
        $codes->maxDeliveryAttempts = $json->maxDeliveryAttempts;
        $codes->maxHoursOutsideFreezer = $json->maxTimeOutsideFreezer;
    }

    private static function toV2Request(FeatureCodes $codes): array {
        return [
            'noNeighbourDelivery' => $codes->noNeighbourDelivery,
            'noSignature' => $codes->noSignature,
            'deliverInMailBox' => $codes->deliverInMailBox,
            'maxDeliveryAttempts' => $codes->maxDeliveryAttempts,
            'maxTimeOutsideFreezer' => $codes->maxHoursOutsideFreezer,
        ];
    }

    /**
     * @var boolean Option to disable neighbour deliveries for this shipment.
     */
    public $noNeighbourDelivery = false;

    /**
     * @var boolean Option to disable the signature requirement.
     */
    public $noSignature = false;

    /**
     * @var boolean Option to disable mailbox delivery for this shipment.
     */
    public $deliverInMailBox = true;

    /**
     * @var int Maximum delivery attempts for this shipment. The absolute maximum is 3 attempts.
     */
    public $maxDeliveryAttempts = 3;

    /**
     * @var int The maximum number of hours the shipment is allowed to be outside the freezer. Only applicable for frozen food shipments.
     */
    public $maxHoursOutsideFreezer = 15;

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
    function serialize(): array {
        switch (Settings::$apiVersion) {
            case 2:
                return self::toV2Request($this);
        }
    }
}