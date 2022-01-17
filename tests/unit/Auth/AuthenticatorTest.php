<?php

namespace Tests\unit\Auth;

use Auth\Authenticator;
use Auth\User;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use SocialPost\Client\FictionalClient;
use SocialPost\Exception\BadResponseException;

class AuthenticatorTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldReturnAccessTokenSuccessfully(): void
    {
        $mock = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . '/../../data/auth-token-response.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $authenticator = new Authenticator(null, new FictionalClient($client, 'test_client_id'));

        $this->assertSame('smslt_6db89021475_4fe4a2a98c8a83', $authenticator->getAccessToken());
    }

    /**
     * @test
     */
    public function itShouldFailToReturnAccessToken(): void
    {
        $this->expectException(BadResponseException::class);
        $this->expectExceptionMessage('No access token returned');

        $mock = new MockHandler([
            new Response(200, [], '{}'),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $authenticator = new Authenticator(null, new FictionalClient($client, 'test_client_id'));

        $authenticator->getAccessToken();
    }
}
