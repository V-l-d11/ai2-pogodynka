<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use App\Entity\Measurement;
use App\Service\WeatherUtil;


class WeatherApiController extends AbstractController
{
    private WeatherUtil $weatherUtil;
    public function __construct(WeatherUtil $weatherUtil){$this->weatherUtil = $weatherUtil;}

    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] ?string $country = null,
        #[MapQueryParameter] ?string $city = null,
        #[MapQueryParameter] ?string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,
    ): Response {
        if ($city === null) {
            return $this->json(['error' => 'City parameter is required'], 400);
        }
        $weatherData = $this->weatherUtil->getWeatherForCountryAndCity($city, $country);
        $measurements = $weatherData['measurements'];
        if ($format === 'csv'  ) {
         if($twig){
             $measurementsData = array_map(fn($m) => [
                 'date' => $m->getDate()->format('Y-m-d'),
                 'celsius' => $m->getCelsius(),
                 'fahrenheit' => $m->getFahrenheit(),
             ], $measurements);
             return $this->render('weather_api/index.csv.twig', [
                 'city' => $city,
                 'country' => $country,
                 'measurements' => $measurementsData,
             ]);
         }else{
             return $this->createCsvResponse($weatherData);
         }
        }

        if($format === 'json'){
            if($twig){
                $measurementsData = array_map(fn($m) => [
                    'date' => $m->getDate()->format('Y-m-d'),
                    'celsius' => $m->getCelsius(),
                    'fahrenheit' => $m->getFahrenheit(),
                ], $measurements);
                return $this->render('weather_api/index.json.twig', [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurementsData,
                ]);
            }else{
                $measurementsArray = array_map(fn($m) => [
                    'date' => $m->getDate()->format('Y-m-d'),
                    'celsius' => $m->getCelsius(),
                    'fahrenheit' => $m->getFahrenheit()
                ], $measurements);
                return $this->json([
                    'message' => 'Weather forecast',
                    'country' => $weatherData['location']->getCountry(),
                    'city' => $weatherData['location']->getCity(),
                    'measurements' => $measurementsArray,
                ]);
            }
        }
     return  $this->json(['massage'=> 'please change format'],400);
    }

    private function createCsvResponse(array $weatherData): Response
    {
        $headers = ['City', 'Country', 'Date', 'Celsius'];
        $columnWidths = [20, 20, 10, 10,10];
        $csvOutput = '';
        foreach ($headers as $index => $header) {
            $csvOutput .= str_pad($header, $columnWidths[$index]) . ' ';
        }
        $csvOutput .= "\n";
        $location = $weatherData['location'];
        foreach ($weatherData['measurements'] as $measurement) {
            $csvOutput .= str_pad($location->getCity(), $columnWidths[0]) . ' ' .
                str_pad($location->getCountry(), $columnWidths[1]) . ' ' .
                str_pad($measurement->getDate()->format('Y-m-d'), $columnWidths[2]) . ' ' .
                str_pad($measurement->getCelsius(), $columnWidths[3]) . ' ' .
                str_pad($measurement->getFahrenheit(), $columnWidths[4]) . "\n";
        }
        $response = new Response($csvOutput);
        $response->headers->set('Content-Type', 'text/plain');
        $response->headers->set('Content-Disposition', 'inline; filename="weather.txt"');
        return $response;
    }


}
