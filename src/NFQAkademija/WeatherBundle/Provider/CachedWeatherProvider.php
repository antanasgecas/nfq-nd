<?php

namespace NFQAkademija\WeatherBundle\Provider;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use NFQAkademija\WeatherBundle\Location;
use NFQAkademija\WeatherBundle\Weather;

class CachedWeatherProvider implements WeatherProviderInterface
{
    private $provider;
    private $ttl;

    /**
     * CachedWeatherProvider constructor.
     *
     * @param WeatherProviderInterface $provider
     * @param int $ttl
     */
    public function __construct(WeatherProviderInterface $provider, int $ttl)
    {
        $this->provider = $provider;
        $this->ttl      = $ttl;
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
            $temperature->expiresAfter(60 * $this->ttl);
            $cache->save($temperature);
        }

        return $temperature->get();
    }
}
