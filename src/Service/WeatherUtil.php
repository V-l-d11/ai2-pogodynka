<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use App\Entity\Measurement;
use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WeatherUtil
{
    private LocationRepository $locationRepository;
    private MeasurementRepository $measurementRepository;

    public function __construct(LocationRepository $locationRepository,MeasurementRepository $measurementRepository)
    {
        $this->locationRepository = $locationRepository;
        $this->measurementRepository = $measurementRepository;
    }


    public function getWeatherForLocation(Location $location): array
    {
        $measurements = $this->measurementRepository->findBy(['location' => $location]);
        if (empty($measurements)) {
            throw new NotFoundHttpException('Measurements not found');
        }

        return [
            'location' => $location,
            'measurements' => $measurements,
        ];
    }



    public function getWeatherForLocationById(int $id): array
    {
        $location = $this->locationRepository->find($id);
        if (!$location) {
            throw new NotFoundHttpException('Location not found');
        }
        $measurements = $this->measurementRepository->findBy(['location' => $location]);
        if (empty($measurements)) {
            throw new NotFoundHttpException('Measurements not found');
        }
        return [
            'location' => $location,
            'measurements' => $measurements,
        ];
    }

    public function getWeatherForCountryAndCity(string $city, ?string $country = null): array
    {
        $locations = $this->locationRepository->findByCityAndCountry($city, $country);

        if (empty($locations)) {
            throw new \Exception('Location not found');
        }

        $location = $locations[0];

        return $this->getWeatherForLocation($location);
    }
}

