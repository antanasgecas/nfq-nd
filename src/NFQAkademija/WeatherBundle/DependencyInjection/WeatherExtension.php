<?php

namespace NFQAkademija\WeatherBundle\DependencyInjection;

use NFQAkademija\WeatherBundle\Provider\CachedWeatherProvider;
use NFQAkademija\WeatherBundle\Provider\DelegatingWeatherProvider;
use NFQAkademija\WeatherBundle\Provider\OpenWeatherMapWeatherProvider;
use NFQAkademija\WeatherBundle\Provider\YahooWeatherProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class WeatherExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Yahoo weather provider
        $container->register('weather.yahoo', YahooWeatherProvider::class);

        // OpenWeatherMap weather provider
        $container
            ->register('weather.openweathermap', OpenWeatherMapWeatherProvider::class)
            ->addArgument($config['providers']['openweathermap']['api_key']);

        // Delegating weather provider
        $providers = [];

        foreach ($config['providers']['delegating']['providers'] as $provider) {
            $providers[] = new Reference('weather.' . $provider);
        }

        $container
            ->register('weather.delegating', DelegatingWeatherProvider::class)
            ->addArgument($providers);

        // Cached weather provider
        $provider = new Reference('weather.' . $config['providers']['cached']['provider']);
        $ttl = $config['providers']['cached']['ttl'];

        $container
            ->register('weather.cached', CachedWeatherProvider::class)
            ->addArgument($provider)
            ->addArgument($ttl);

        // Set alias
        $container->setAlias('weather', 'weather.'.$config['provider']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
