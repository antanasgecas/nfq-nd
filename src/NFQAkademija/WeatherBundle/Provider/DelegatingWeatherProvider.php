<?php

namespace NFQAkademija\WeatherBundle\Provider;

use NFQAkademija\WeatherBundle\Service\WeatherException;
use NFQAkademija\WeatherBundle\Service\Location;
use NFQAkademija\WeatherBundle\Service\Weather;

class DelegatingWeatherProvider implements WeatherProviderInterface
{
    private $providers;

    /**
     * DelegatingWeatherProvider constructor.
     *
     * @param array $providers
     */
    public function __construct(array $providers)
    {
        $this->providers = $providers;
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
        foreach ($this->providers as $provider)
        {
            try {
                return $provider->fetch($location);
            } catch (WeatherException $e) {
                // TODO: Logint klaida
            }
        }

        throw new WeatherException('No one knows temperature');
    }
}
