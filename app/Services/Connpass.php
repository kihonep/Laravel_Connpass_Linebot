<?php

namespace App\Services;

use GuzzleHttp\Client;

class Connpass
{
    private const MEETUP_SEARCH_API_URL = 'https://connpass.com/api/v1/event/';

    public function searchMeetups($word): array
    {
        $client = new Client();
        $response = $client
            ->get(self::MEETUP_SEARCH_API_URL, [
                'query' => [
                    'keyword' => str_replace(' ', ',', $word),
                    'ym' => date('yyyymm'),
                    'count' => '10',
                    'order' => '1',
                    'format' => 'json',
                ],

                'http_errors' => false,
            ]);
            
        return json_decode($response->getBody()->getContents(), true);
    }
}