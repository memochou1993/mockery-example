<?php

namespace Tests\Unit\Services;

use Mockery;
use App\Services\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as GuzzleClient;

class ClientTest extends TestCase
{
    /** @test */
    public function testQuery()
    {
        $guzzleClient = Mockery::mock(GuzzleClient::class);
        $guzzleClient->shouldReceive('request')->andReturn(
            new Response('200', [], file_get_contents(__DIR__.'/result.json'))
        );

        $client = new Client($guzzleClient);

        $this->assertEquals([
            'USD' => [
                'price' => 8004.20129962,
                'volume_24h' => 16527569995.0296,
                'percent_change_1h' => 0.0534322,
                'percent_change_24h' => -3.30471,
                'percent_change_7d' => -5.0892,
                'market_cap' => 144038403857.11676,
                'last_updated' => '2019-10-16T15:52:37.000Z'
            ],
        ], $client->query('BTC'));
    }
}
