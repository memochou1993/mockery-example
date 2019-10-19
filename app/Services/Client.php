<?php

namespace App\Services;

use Illuminate\Support\Arr;
use GuzzleHttp\Client as GuzzleClient;

class Client
{
    protected $client;

    protected $log;

    public function __construct(GuzzleClient $client, Log $log)
    {
        $this->client = $client;
        $this->log = $log;
    }

    public function query($symbol = 'BTC')
    {
        $key = env('CMC_PRO_API_KEY');
        $this->log->info($key);

        $response = $this->client->request('GET', 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest', [
            'headers' => [
                'X-CMC_PRO_API_KEY' => $key,
            ],
        ]);

        $results = json_decode($response->getBody()->getContents(), true);

        $item = collect($results['data'])->filter(function ($item) use ($symbol) {
            return $item['symbol'] === trim(strtoupper($symbol));
        })->first();

        return Arr::get($item, 'quote');
    }
}
