<?php

namespace Trunkrs\SDK\Util;

use PHPUnit\Framework\TestCase;

class JsonDateTimeTest extends TestCase {
    public function testShouldFormatToJsonDateTime() {
        $phpDateTime = \DateTime::createFromFormat("Y-m-d H:i:s", "2020-03-09 17:57:08");

        $result = JsonDateTime::to($phpDateTime);

        $this->assertEquals("2020-03-09T17:57:08.000Z", $result);
    }

    public function testShouldFormatFromJsonDateTime() {
        $isoDateTime = "2020-03-09T17:57:08.123Z";

        $phpDateTime = JsonDateTime::from($isoDateTime);

        $this->assertEquals("2020-03-09", $phpDateTime->format("Y-m-d"));
        $this->assertEquals("17:57:08.123", $phpDateTime->format("H:i:s.v"));
    }
}