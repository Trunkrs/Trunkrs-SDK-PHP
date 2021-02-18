<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Util\SerializableInterface;

class FeatureCodes implements SerializableInterface
{
    private static function applyV2(FeatureCodes $codes, $json) {
        $codes->noNeighbourDelivery = $json->noNeighbourDelivery;
        $codes->noSignature = $json->noSignature;
        $codes->deliverInMailBox = $json->deliverInMailBox;
        $codes->maxDeliveryAttempts = isset($json->maxDeliveryAttempts) ? $json->maxDeliveryAttempts : null;
        $codes->maxHoursOutsideFreezer = isset($json->maxTimeOutsideFreezer) ? $json->maxTimeOutsideFreezer : null;
        $codes->requireStrictProofOfDelivery = isset($json->requireProofOfDelivery) ? $json->requireProofOfDelivery : false;
    }

    private static function toV2Request(FeatureCodes $codes): array {
        return [
            'noNeighbourDelivery' => $codes->noNeighbourDelivery,
            'noSignature' => $codes->noSignature,
            'deliverInMailBox' => $codes->deliverInMailBox,
            'maxDeliveryAttempts' => $codes->maxDeliveryAttempts,
            'maxTimeOutsideFreezer' => $codes->maxHoursOutsideFreezer,
            'requireProofOfDelivery' => $codes->requireStrictProofOfDelivery,
        ];
    }

    /**
     * @var boolean Option to disable neighbour deliveries for this shipment.
     */
    public $noNeighbourDelivery = false;

    /**
     * @note This setting involves extra costs. Please discuss with your Trunkrs account manager.
     * @var boolean Sets whether strict proof of delivery is required for this shipment.
     */
    public $requireStrictProofOfDelivery = false;

    /**
     * @var boolean Option to disable the signature requirement.
     */
    public $noSignature = false;

    /**
     * @var boolean Option to disable mailbox delivery for this shipment.
     */
    public $deliverInMailBox = false;

    /**
     * @note Only applicable for service level SAME_DAY_FROZEN_FOOD.
     * @var int Maximum delivery attempts for this shipment. The absolute maximum is 3 attempts.
     */
    public $maxDeliveryAttempts = null;

    /**
     * @note Only applicable for service level SAME_DAY_FROZEN_FOOD.
     * @var int The maximum number of hours the shipment is allowed to be outside the freezer. Only applicable for frozen food shipments.
     */
    public $maxHoursOutsideFreezer = null;

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