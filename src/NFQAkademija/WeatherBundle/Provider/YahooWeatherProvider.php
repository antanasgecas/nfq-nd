<?php

namespace NFQAkademija\WeatherBundle\Provider;

use NFQAkademija\WeatherBundle\Service\Location;
use NFQAkademija\WeatherBundle\Service\Weather;
use GuzzleHttp\Client as GuzzleClient;
use NFQAkademija\WeatherBundle\Service\WeatherException;

class YahooWeatherProvider implements WeatherProviderInterface
{
    /**
     * @param Location $location
     *
     * @return Weather
     */
    public function fetch(Location $location): Weather
    {
        $latitude = $location->getLatitude();
        $longitude = $location->getLongitude();

        $yql_query = 'select item.condition.temp
                      from weather.forecast
                      where woeid in
                      (SELECT woeid FROM geo.places WHERE text="('.$latitude.','.$longitude.')")
                      AND u="c"';
        $yql_url = 'http://query.yahooapis.com/v1/public/yql?q=' . urlencode($yql_query) . '&format=json';

        // Guzzle Request
        $client   = new GuzzleClient();
        $response = $client->get($yql_url);
        $json = json_decode($response->getBody()->getContents());

        if ($json->query->count == 0)
            throw new WeatherException('Can\'t get temperature');

        $temp = $json->query->results->channel->item->condition->temp;

        return new Weather($temp);
    }
}
