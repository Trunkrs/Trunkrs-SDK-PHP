<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Enum\ShipmentLabelType;
use Trunkrs\SDK\Exception\NotSupportedException;

class LabelUrls {
    private static function applyV1(LabelUrls $labels, $json) {
        $labels->pdfUrl = $json->label;
    }

    private static function applyV2(LabelUrls $labels, $json) {
        $labels->pdfUrl = $json->pdf;
        $labels->zplUrl = $json->zpl;
    }

    /**
     * @var string The URL of the PDF label for this shipment.
     */
    public $pdfUrl;

    /**
     * @var string The URL of the ZPL label for this shipment. Only available under the V2 API.
     */
    public $zplUrl;

    /**
     * Downloads the specified shipment label.
     * @see ShipmentLabelType
     * @param string $filename The filename of the file to download to.
     * @param string $type The label type. Only pdf supported in API V1.
     * @throws Exception\GeneralApiException
     * @throws Exception\NotAuthorizedException
     * @since 2.0.0
     */
    public function download(string $filename, $type = ShipmentLabelType::PDF) {
        switch ($type) {
            case ShipmentLabelType::PDF:
                RequestHandler::downloadGet($this->pdfUrl, $filename);
                break;
            case ShipmentLabelType::ZPL:
                if (Settings::$apiVersion == 1) {
                    throw new NotSupportedException("Please use the Label class to download ZPL labels.");
                }
                RequestHandler::downloadGet($this->zplUrl, $filename);
                break;
        }
    }

    public function __construct($json = null) {
        if ($json) {
            switch (Settings::$apiVersion) {
                case 1:
                    self::applyV1($this, $json);
                    break;
                case 2:
                    self::applyV2($this, $json);
                    break;
            }
        }
    }
}