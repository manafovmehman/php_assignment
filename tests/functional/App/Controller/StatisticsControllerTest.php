<?php

namespace Tests\functional\App\Controller;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class StatisticsControllerTest extends TestCase
{
    public function testStatistics(): void
    {
        $client = $this->getClient();

        $request = $client->request('GET', '/statistics?month=October,%202021');
        $stats = json_decode($request->getBody(), true);

        $this->assertArrayHasKey('stats', $stats);
    }

    protected function getClient(): Client
    {
        return new Client(
            [
                'base_uri' => 'http://localhost',
            ]
        );
    }
}
