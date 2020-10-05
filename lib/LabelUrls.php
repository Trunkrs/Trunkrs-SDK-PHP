<?php

namespace Trunkrs\SDK;

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