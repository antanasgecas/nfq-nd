<?php

namespace NFQAkademija\WeatherBundle\Provider;

use NFQAkademija\WeatherBundle\Service\Location;
use NFQAkademija\WeatherBundle\Service\Weather;

class YahooWeatherProvider implements WeatherProviderInterface
{
    /**
     * @param Location $location
     *
     * @return Weather
     */
    public function fetch(Location $location): Weather
    {
        return new Weather(20);
    }
}
