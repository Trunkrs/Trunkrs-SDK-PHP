<?php


namespace Trunkrs\SDK\Enum;


class ShipmentLabelSize
{
    /**
     * Used in in printing ZPL labels on Dymo printers.
     */
    const ZPL_DEFAULT_LABEL = 'shipping-label';
    /*
     * Use this size to render 4 labels per PDF page.
     */
    const A4 = 'A4';
}