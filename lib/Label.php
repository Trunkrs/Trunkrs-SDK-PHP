<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Enum\ShipmentLabelType;
use Trunkrs\SDK\Exception\GeneralApiException;
use Trunkrs\SDK\Exception\NotAuthorizedException;
use Trunkrs\SDK\Exception\NotSupportedException;

/**
 * Wrapper around Label data.
 * @package Trunkrs\SDK
 * @see \SplFileObject
 */
class Label extends \SplFileObject {
    private static function getShipmentToken(
        string $trunkrsNr,
        string $postalCode
    ): string {
        $cleanPostal = str_replace(' ', '', strtolower($postalCode));
        return md5($trunkrsNr . $cleanPostal);
    }

    /**
     * Downloads the label for the specified shipment details.
     * @param string $type The label format type. Either pdf or zpl.
     * @param string $trunkrsNr The Trunkrs number of the shipment.
     * @param string $postalCode The postal code of the shipment.
     * @return Label The label in a SplFileObject wrapper.
     * @throws NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws GeneralApiException When the API responds with an unexpected answer.
     * @see Trunkrs\SDK\Enum\ShipmentLabelType
     * @see \SplFileObject
     */
    public static function download(
        string $type,
        string $trunkrsNr,
        string $postalCode
    ): Label {
        try {
            $label = new Label($type);
            $tmpFilename = $label->getRealPath();

            $token = self::getShipmentToken($trunkrsNr, $postalCode);
            $resource = $type == ShipmentLabelType::ZPL
                ? sprintf("label/%s/%s/zpl", $token, $trunkrsNr)
                : sprintf("label/%s/%s", $token, $trunkrsNr);

            RequestHandler::downloadGet($resource, $tmpFilename);

            return $label;
        } catch (\Exception $exception) {
            $label = null;
            throw $exception;
        }
    }

    /**
     * Downloads a single file containing all specified shipment labels. Only PDF is supported at this moment.s
     * @param string $type The label format type. Only PDf is supported at this moment.
     * @param array $trunkrsNrs The Trunkrs numbers of the shipments to create the label for.
     * @return Label
     * @throws NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws GeneralApiException When the API responds with an unexpected answer.
     * @throws NotSupportedException Thrown when trying to create batched ZPL labels.
     * @see Trunkrs\SDK\Enum\ShipmentLabelType
     * @see \SplFileObject
     */
    public static function downloadBatch(
        string $type,
        array $trunkrsNrs
    ): Label {
        try {
            $label = new Label($type);
            $tmpFilename = $label->getRealPath();

            if ($type == ShipmentLabelType::ZPL) {
                throw new NotSupportedException('Batching ZPL labels is not supported at this moment.');
            }

            RequestHandler::downloadPut(
                'shipments/labels',
                $tmpFilename,
                ['trunkrsNrs' => $trunkrsNrs]
            );

            return $label;
        } catch (\Exception $exception) {
            $label = null;
            throw $exception;
        }
    }

    /**
     * The shipment label constructor.
     * @param string $type The label type.
     */
    public function __construct(string $type)
    {
        $this->type = $type;
        $this->filePath = tempnam(sys_get_temp_dir(), "TSDK-Label-");

        parent::__construct($this->filePath, 'r');
    }

    private $filePath;

    /**
     * @var string The label type.
     * @see Trunkrs\SDK\Enum\ShipmentLabelType
     */
    public $type;

    public function __destruct() {
        @unlink($this->filePath);
    }
}