<?php

namespace NFQAkademija\WeatherBundle\Service;

class Weather
{
    private $temperature;

    /**
     * Weather constructor.
     *
     * @param float $temperature
     */
    public function __construct(float $temperature)
    {
        $this->temperature = $temperature;
    }

    /**
     * Get temperature
     *
     * @return float
     */
    public function getTemperature(): float
    {
        return $this->temperature;
    }
}
