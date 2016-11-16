<?php

namespace NFQAkademija\WeatherBundle\Service;

class Weather
{
    private $temperature;

    public function __construct(float $temperature)
    {
        $this->temperature = $temperature;
    }

    public function getTemperature(): float
    {
        return $this->temperature;
    }
}
