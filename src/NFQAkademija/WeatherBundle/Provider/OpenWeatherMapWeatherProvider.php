<?php

namespace NFQAkademija\WeatherBundle\Provider;

use NFQAkademija\WeatherBundle\Weather;
use NFQAkademija\WeatherBundle\Location;
use NFQAkademija\WeatherBundle\WeatherException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

class OpenWeatherMapWeatherProvider implements WeatherProviderInterface
{
    private $appID;

    /**
     * OpenWeatherMapWeatherProvider constructor.
     *
     * @param string $appID
     */
    public function __construct(string $appID)
    {
        $this->appID = $appID;
    }

    /**
     * @param Location $location
     *
     * @return Weather
     *
     * @throws WeatherException
     */
    public function fetch(Location $location): Weather
    {
        $latitude = $location->getLatitude();
        $longitude = $location->getLongitude();

        $owm_url = 'http://api.openweathermap.org/data/2.5/weather?lat='
                .$latitude.'&lon='
                .$longitude.'&appid='
                .$this->appID.'&units=metric';

        // Guzzle Request
        $client = new GuzzleClient();

        try {
            $response = $client->get($owm_url);
        } catch (RequestException $e) {
            throw new WeatherException('Can\'t get temperature');
        }

        $json = json_decode($response->getBody()->getContents());

        $temp = $json->main->temp;

        return new Weather($temp);
    }
}
