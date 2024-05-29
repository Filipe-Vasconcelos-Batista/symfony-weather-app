<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherApi
{


    public function __construct(private readonly string $apiKey, protected HttpClientInterface $client)
    {
    }

    public function getWeather(string $cityName)
    {
        $client = $this->client;
        $url = "http://api.openweathermap.org/data/2.5/weather?q=$cityName&appid={$this->apiKey}&units=metric";
        $response = $client->request('GET', $url);
        if ($response->getStatusCode() != 200) {
            throw new \Exception("Weather not found for $cityName");
        }
        $data = $response->toArray();
        if (isset($data->cod) && $data->cod != 200) {
            throw new \Exception("Api returned error for city: $cityName");
        }
        return $data;
    }
    

}