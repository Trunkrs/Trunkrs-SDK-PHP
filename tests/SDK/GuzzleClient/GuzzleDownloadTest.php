<?php

namespace Trunkrs\SDK;

use GuzzleHttp\Psr7\Response;
use Trunkrs\SDK\HTTP\GuzzleClient;

class GuzzleDownloadTest extends GuzzleTestCase {
    private $url;
    private $subject;
    private $filename;

    protected function setUp()
    {
        parent::setUp();

        $this->url = Mocks::getGenerator()->url;
        $this->subject = new GuzzleClient($this->guzzleClient);
        $this->filename = tempnam(sys_get_temp_dir(), "TSDK-Testing-");
    }

    public function testShouldPlaceGetMethodCorrectly() {
        $this->mockHandler->append(new Response());

        $this->subject->download("GET", $this->url, $this->filename);

        $this->assertRequestMethod("GET");
    }

    public function testShouldPlacePostMethodCorrectly() {
        $this->mockHandler->append(new Response());

        $this->subject->download("POST", $this->url, $this->filename);

        $this->assertRequestMethod("POST");
    }

    public function testShouldPlaceHeadersCorrectly() {
        $headers = [
            "X-Foo" => "Bar",
            "Content-Type" => "application/x-test-suite",
            "Accept" => "human/joke",
        ];
        $this->mockHandler->append(new Response());

        $this->subject->download("POST", $this->url, $this->filename, $headers);

        $this->assertRequestHeaders($headers);
    }

    public function testShouldPlaceUrlCorrectly() {
        $this->mockHandler->append(new Response());

        $this->subject->download("GET", $this->url, $this->filename);

        $this->assertRequestUrl($this->url);
    }

    public function testPassesBackStatusCodeCorrectly() {
        $this->mockHandler->append(new Response(204));

        $response1 = $this->subject->download("DELETE", $this->url, $this->filename);

        $this->assertEquals(204, $response1['status']);
    }

    public function testPassesBackStatusCodeCorrectlyForClientErrors() {
        $this->mockHandler->append(new Response(400));

        $response1 = $this->subject->download("PUT", $this->url, $this->filename);

        $this->assertEquals(400, $response1['status']);
    }

    public function testPassesBackStatusCodeCorrectlyForServerErrors() {
        $this->mockHandler->append(new Response(500));

        $response1 = $this->subject->download("DELETE", $this->url, $this->filename);

        $this->assertEquals(500, $response1['status']);
    }

    public function testPassesBackHeadersCorrectly() {
        $headers = ["X-Foo" => "Bar", "X-Bar" => "Baz"];
        $this->mockHandler->append(new Response(200, $headers));

        $response = $this->subject->download("GET", $this->url, $this->filename);

        $this->assertEquals($headers, $response['headers']);
    }

    public function testWritesBodyIntoFile() {
        $body = json_encode(["X-Foo" => "Bar", "X-Bar" => "Baz"]);
        $this->mockHandler->append(new Response(200, ["Content-Type" => "application/json"], $body));

        $this->subject->download("GET", $this->url, $this->filename);

        $fileContent = file_get_contents($this->filename);
        $this->assertEquals($body, $fileContent);
    }

    protected function tearDown()
    {
        parent::tearDown();

        if (file_exists($this->filename)) {
            unlink($this->filename);
        }
    }
}