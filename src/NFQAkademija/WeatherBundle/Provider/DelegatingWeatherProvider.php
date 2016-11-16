<?php

namespace NFQAkademija\WeatherBundle\Provider;

use NFQAkademija\WeatherBundle\Service\WeatherException;
use NFQAkademija\WeatherBundle\Service\Location;
use NFQAkademija\WeatherBundle\Service\Weather;

class DelegatingWeatherProvider implements WeatherProviderInterface
{
    private $providers;

    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }

    public function fetch(Location $location): Weather
    {
        foreach ($this->providers as $provider)
        {
            try {
                return $provider->fetch($location);
            } catch (WeatherException $e) {
                // Turbut turetu vykt klaidos loginimas
            }

            throw new WeatherException('No one knows temperature');
        }
    }
}
