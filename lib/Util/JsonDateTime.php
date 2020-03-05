<?php

namespace Trunkrs\SDK\Util;

class JsonDateTime {
    private static $_defaultTimeZone;

    private static function getTimeZone(): \DateTimeZone {
        if (!self::$_defaultTimeZone) {
            self::$_defaultTimeZone = new \DateTimeZone('UTC');
        }
        return self::$_defaultTimeZone;
    }

    /**
     * Converts the DateTime to JSON ISO date string.
     *
     * @param \DateTime $date The PHP DateTime object.
     * @return string The ISO date string.
     */
    public static function to(\DateTime $date): string {
        return $date->format("Y-m-d\TH:i:s.v\Z");
    }

    /**
     * Converts an ISO date string to PHP DateTime object.
     *
     * @param string $dateString The ISO date string.
     * @return \DateTime The DateTime representation.
     */
    public static function from(string $dateString): \DateTime {
        return \DateTime::createFromFormat(
            "Y-m-d\TH:i:s.u\Z",
            $dateString,
            self::getTimeZone()
        );
    }
}