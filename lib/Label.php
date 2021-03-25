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
     * @deprecated As of API version 2, the shipment has both ZPL and PDF label urls.
     * @see Trunkrs\SDK\Enum\ShipmentLabelType
     * @see \SplFileObject
     */
    public static function download(
        string $type,
        string $trunkrsNr,
        string $postalCode
    ): Label {
        if (Settings::$apiVersion > 1) {
            throw new NotSupportedException("The use of this functionality is not supported in API version 2.");
        }

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
     * @param string $filename The filename to download the labels to.
     * @param array $trunkrsNrs The Trunkrs numbers of the shipments to create the label for.
     * @param string $size The optional size specifier for the labels.
     * @return void
     * @throws NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws GeneralApiException When the API responds with an unexpected answer.
     * @throws NotSupportedException Thrown when trying to create batched ZPL labels.
     * @see Trunkrs\SDK\Enum\ShipmentLabelType
     * @see Trunkrs\SDK\Enum\ShipmentLabelSize
     */
    public static function downloadBatch(
        string $type,
        string $filename,
        array $trunkrsNrs,
        string $size = null
    ) {
        try {

            if ($type == ShipmentLabelType::ZPL) {
                throw new NotSupportedException('Batching ZPL labels is not supported at this moment.');
            }

            switch (Settings::$apiVersion) {
                case 1:
                    RequestHandler::downloadPut(
                        'shipments/labels',
                        $filename,
                        ['trunkrsNrs' => $trunkrsNrs]
                    );
                    break;
                case 2:
                    $sizeParam = is_null($size) ? [] : ['size' => $size];
                    RequestHandler::downloadPut(
                        sprintf('shipments/labels/%s', $type),
                        $filename,
                        array_merge(
                            ['trunkrsNrs' => $trunkrsNrs],
                            $sizeParam
                        )
                    );
                    break;
            }
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

    private function getStringContents(): string {
        $content = $this->fread($this->getSize());
        $this->seek(0);

        return $content;
    }

    /**
     * @return string Returns the label content as BASE-64 encoded string.
     */
    public function getBase64Contents(): string {
        return base64_encode($this->getStringContents());
    }

    /**
     * @var string The label type.
     * @see Trunkrs\SDK\Enum\ShipmentLabelType
     */
    public $type;

    public function __toString()
    {
        return $this->getStringContents();
    }

    public function __destruct() {
        @unlink($this->filePath);
    }
}