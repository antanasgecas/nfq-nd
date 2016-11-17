<?php

namespace NFQAkademija\WeatherBundle\Provider;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use NFQAkademija\WeatherBundle\Location;
use NFQAkademija\WeatherBundle\Weather;

class CachedWeatherProvider implements WeatherProviderInterface
{
    private $provider;

    /**
     * CachedWeatherProvider constructor.
     *
     * @param WeatherProviderInterface $provider
     */
    public function __construct(WeatherProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param Location $location
     *
     * @return Weather
     */
    public function fetch(Location $location): Weather
    {
        $key = $location->getLatitude() . '-' . $location->getLongitude();

        $cache = new FilesystemAdapter();
        $temperature = $cache->getItem($key);

        if (!$temperature->isHit())
        {
            $temperature = $cache->getItem($key);
            $temperature->set($this->provider->fetch($location));
            $temperature->expiresAfter(60 * 10);
            $cache->save($temperature);
        }

        return $temperature->get();
    }
}
