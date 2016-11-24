<?php

namespace NFQAkademija\WeatherBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('weather');

        $rootNode
            ->children()
                ->scalarNode('provider')
                    ->defaultValue('cached')
                    ->isRequired()
                ->end()
                ->arrayNode('providers')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('openweathermap')
                            ->children()
                                ->scalarNode('api_key')
                                    ->isRequired()
                                ->end()
                            ->end()
                        ->end() // OpenWeatherMap
                        ->arrayNode('delegating')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('providers')
                                    ->prototype('scalar')->end()
                                    ->defaultValue(['yahoo', 'openweathermap'])
                                ->end()
                            ->end()
                        ->end() // Delegating
                        ->arrayNode('cached')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('provider')
                                    ->defaultValue('delegating')
                                    ->isRequired()
                                ->end()
                                ->scalarNode('ttl')
                                    ->defaultValue(30)
                                    ->isRequired()
                                ->end()
                            ->end()
                        ->end() // Cached
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
