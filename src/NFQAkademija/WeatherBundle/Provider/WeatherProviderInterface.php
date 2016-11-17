<?php

namespace NFQAkademija\WeatherBundle\Provider;

use NFQAkademija\WeatherBundle\Service\Weather;
use NFQAkademija\WeatherBundle\Service\Location;

interface WeatherProviderInterface
{
    /**
     * @param Location $location
     *
     * @return Weather
     */
    public function fetch(Location $location): Weather;
}
