<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Exception\ServerValidationException;
use Trunkrs\SDK\HTTP\HttpClientInterface;

class GeneralTest extends SDKTestCase {
    public function testUsesSpecifiedHttpClient() {
        $client = $this->createMock(HttpClientInterface::class);

        RequestHandler::setHttpClient($client);

        $client->expects($this->once())->method("request")
            ->will($this->returnValue(["status" => 200]));
        RequestHandler::get("shipments");
    }

    public function testAppliesCredentials() {
        $clientId = uniqid();
        $clientSecret = uniqid();
        Settings::setCredentials($clientId, $clientSecret);

        $this->mockResponseCallback(function ($method, $url, $headers) use($clientId, $clientSecret) {
            $this->assertArraySubset([
                'X-API-ClientId' => $clientId,
                'X-API-ClientSecret' => $clientSecret,
            ], $headers);

            return ["status" => 200];
        });

        RequestHandler::get("shipments");
    }

    public function testAppliesSDKUserAgent() {
        $this->mockResponseCallback(function ($method, $url, $headers) {
            $this->assertArraySubset([
                'User-Agent' => sprintf('Trunkrs SDK/PHP/v%s', Settings::$sdkVersion),
            ], $headers);

            return ["status" => 200];
        });

        RequestHandler::get("shipments");
    }

    public function testApiValidationMessageInException() {
        $message = uniqid();
        $this->mockResponse(422, [
            'message' => $message,
        ]);

        try {
            RequestHandler::post("shipments", []);
        } catch (ServerValidationException $exception) {
            $this->assertEquals($message, $exception->getValidationMessage());
        }
    }
}