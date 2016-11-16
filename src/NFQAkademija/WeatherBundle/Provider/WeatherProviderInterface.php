<?php

namespace NFQAkademija\WeatherBundle\Provider;

interface WeatherProviderInterface
{
    /**
     * @param Location $location
     *
     * @return Weather
     */
    public function fetch(Location $location): Weather;
}
