<?php

namespace NFQAkademija\WeatherBundle\Controller;

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
     * @Route("/weather/{city}")
     */
    public function weatherAction($city) {
        $yql_query = 'select item.condition, item.forecast
                      from weather.forecast
                      where woeid
                      in (select woeid from geo.places(1) where text="'. $city .'")
                      and u="c"';
        $yql_url = 'http://query.yahooapis.com/v1/public/yql?q=' . urlencode($yql_query) . '&format=json';

        // Guzzle Request
        $client   = new \GuzzleHttp\Client();
        $response = $client->get($yql_url);
        $json = json_decode($response->getBody()->getContents(), true);

        $success = true;
        if ($json['query']['count'] == 0)
            $success = false;

        $today = $json['query']['results']['channel'][0]['item'];
        $temp = $today['condition']['temp'];
        $low_temp = $today['forecast']['low'];
        $high_temp = $today['forecast']['high'];

        return $this->render('WeatherBundle:Default:weather.html.twig', [
            'city'    => $city,
            'success' => $success,
            'temp'    => $temp,
            'low'     => $low_temp,
            'high'    => $high_temp
        ]);
    }
}
