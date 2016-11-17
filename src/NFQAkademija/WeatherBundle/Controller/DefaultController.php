<?php

namespace NFQAkademija\WeatherBundle\Controller;

use NFQAkademija\WeatherBundle\Provider\CachedWeatherProvider;
use NFQAkademija\WeatherBundle\Provider\DelegatingWeatherProvider;
use NFQAkademija\WeatherBundle\Provider\OpenWeatherMapWeatherProvider;
use NFQAkademija\WeatherBundle\Provider\YahooWeatherProvider;
use NFQAkademija\WeatherBundle\Service\Location;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/weather")
     */
    public function indexAction()
    {
        return $this->render('WeatherBundle:Default:index.html.twig');
    }

    /**
     * @Route("/weather/{longitude}/{latitude}")
     *
     * @param float $longitude
     * @param float $latitude
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function weatherAction(float $longitude, float $latitude) {
        $location = new Location($longitude, $latitude);
        $provider = new CachedWeatherProvider(
            new DelegatingWeatherProvider([
                new YahooWeatherProvider(),
                new OpenWeatherMapWeatherProvider(),
            ])
        );

        $weather = $provider->fetch($location);

        $temp = $weather->getTemperature();

        return $this->render('WeatherBundle:Default:weather.html.twig', [
            'temp'    => $temp,
        ]);
    }
}
