<?php

namespace App\Services;

use Illuminate\Support\Arr;
use GuzzleHttp\Client as GuzzleClient;

class Client
{
    protected $client;

    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
    }

    public function query($symbol = 'BTC')
    {
        $response = $this->client->request('GET', 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest', [
            'headers' => [
                'X-CMC_PRO_API_KEY' => env('CMC_PRO_API_KEY'),
            ],
        ]);

        $results = json_decode($response->getBody()->getContents(), true);

        $item = collect($results['data'])->where('symbol', $symbol)->first();

        return Arr::get($item, 'quote');
    }
}
