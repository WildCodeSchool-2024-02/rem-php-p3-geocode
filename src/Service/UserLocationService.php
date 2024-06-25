<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class UserLocationService
{
    public function getUserLocation(string $ipAddress): array
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://ip-api.com/json/' . $ipAddress);
        $content = $response->toArray();

        return $content;
    }
}
