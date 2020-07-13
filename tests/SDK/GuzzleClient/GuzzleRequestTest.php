<?php

namespace Trunkrs\SDK;

use GuzzleHttp\Psr7\Response;
use Trunkrs\SDK\HTTP\GuzzleClient;

class GuzzleRequestTest extends GuzzleTestCase {
    private $url;

    private $subject;

    protected function setUp()
    {
        parent::setUp();

        $this->url = Mocks::getGenerator()->url;
        $this->subject = new GuzzleClient($this->guzzleClient);
    }

    public function testShouldPlaceMethodsCorrectly() {
        $this->mockHandler->append(new Response());
        $this->mockHandler->append(new Response());
        $this->mockHandler->append(new Response());
        $this->mockHandler->append(new Response());

        $this->subject->request("GET", $this->url);
        $this->subject->request("POST", $this->url);
        $this->subject->request("PUT", $this->url);
        $this->subject->request("DELETE", $this->url);

        $this->assertRequestMethod("GET", 1);
        $this->assertRequestMethod("POST", 2);
        $this->assertRequestMethod("PUT", 3);
        $this->assertRequestMethod("DELETE", 4);
    }

    public function testShouldPlaceHeadersCorrectly() {
        $headers = [
            "X-Foo" => "Bar",
            "Content-Type" => "application/x-test-suite",
            "Accept" => "human/joke",
        ];
        $this->mockHandler->append(new Response());

        $this->subject->request("POST", $this->url, $headers);

        $this->assertRequestHeaders($headers);
    }

    public function testShouldPlaceUrlCorrectly() {
        $this->mockHandler->append(new Response());

        $this->subject->request("GET", $this->url);

        $this->assertRequestUrl($this->url);
    }

    public function testPassesBackStatusCodeCorrectly() {
        $this->mockHandler->append(new Response(204));
        $this->mockHandler->append(new Response(404));
        $this->mockHandler->append(new Response(500));

        $response1 = $this->subject->request("DELETE", $this->url);
        $response2 = $this->subject->request("POST", $this->url);
        $response3 = $this->subject->request("PUT", $this->url);

        $this->assertEquals(204, $response1['status']);
        $this->assertEquals(404, $response2['status']);
        $this->assertEquals(500, $response3['status']);
    }

    public function testPassesBackHeadersCorrectly() {
        $headers = ["X-Foo" => "Bar", "X-Bar" => "Baz"];
        $this->mockHandler->append(new Response(200, $headers));

        $response = $this->subject->request("GET", $this->url);

        $this->assertEquals($headers, $response['headers']);
    }

    public function testPassesBackBodyCorrectly() {
        $body = json_encode(["X-Foo" => "Bar", "X-Bar" => "Baz"]);
        $this->mockHandler->append(new Response(200, ["Content-Type" => "application/json"], $body));

        $response = $this->subject->request("GET", $this->url);

        $this->assertEquals($body, $response['body']);
    }
}