<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Util\JsonDateTime;

/**
 * Class TimeSlotWindow
 */
class TimeWindow {
    private static function applyV1(TimeWindow $window, $json) {
        $window->from = JsonDateTime::from($json->from);
        $window->to = JsonDateTime::from($json->to);
    }

    /**
     * @var \DateTime $from The start of the window.
     */
    public $from;

    /**
     * @var \DateTime $to The end of the window.
     */
    public $to;

    /**
     * TimeSlotWindow constructor.
     * @param array|null $json An optional associative array for parsing the window.
     */
    public function __construct($json = null)
    {
        if ($json) {
            switch (Settings::$apiVersion) {
                case 1:
                    self::applyV1($this, $json);
                    break;
            }
        }
    }
}