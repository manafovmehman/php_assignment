<?php

namespace Tests\unit\SocialPost\Driver;

use Auth\Authenticator;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use SocialPost\Client\FictionalClient;
use SocialPost\Driver\FictionalDriver;
use PHPUnit\Framework\TestCase;

class FictionalDriverTest extends TestCase
{
    private FictionalDriver $driver;

    private MockHandler $mockHandler;

    private Authenticator $authenticator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = new MockHandler();

        $handlerStack = HandlerStack::create($this->mockHandler);
        $client = new Client(['handler' => $handlerStack]);
        $socialClient = new FictionalClient($client, 'test_client_id');

        $this->authenticator = $this->createMock(Authenticator::class);

        $this->driver = new FictionalDriver($socialClient, $this->authenticator);
    }

    public function testFetchPostsByPage(): void
    {
        $this->authenticator->expects($this->once())->method('getAccessToken')->willReturn('test_token');
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/../../../data/social-posts-response.json')));

        $posts = $this->driver->fetchPostsByPage(1);
        $postsArr = iterator_to_array($posts);

        $this->assertSame(4, count($postsArr));
    }
}
