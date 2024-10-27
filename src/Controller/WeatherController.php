<?php

namespace App\Controller;
use App\Entity\Date;
use App\Entity\Location;
use App\Entity\Measurement;
use App\Repository\MeasurementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

class WeatherController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager){$this->entityManager = $entityManager;}
    #[Route('/weather/{city}/{country}?}', name: 'app_weather_city')]
    public function city(string $city, ?string $country, MeasurementRepository $repository): Response{
        $location = $this->entityManager->getRepository(Location::class)->findOneByCityAndCountry($city, $country);
        if (!$location) {
            throw $this->createNotFoundException('Location not found');
        }
        $measurements = $repository->findByLocation($location);
        if(!$measurements){
            throw  $this->createNotFoundException("Measurment not found");
        }
        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }

    #[Route('/add-loc', name: 'add-loc')]
    public function allLOcation(Request $request): Response
    {
        $locationId = $request->query->get('locationId', null);
        if ($locationId) {
            $location = $this->entityManager->getRepository(Location::class)->find($locationId);
            if (!$location) {
                throw $this->createNotFoundException('Location not found');
            }
            return $this->addMeasure($location);
        }
        $location = new Location();
        $location->setCity('Warszawa');
        $location->setCountry('Poland');
        $location->setLatitude('54.5521');
        $location->setLongitude('24.5718');
        $this->entityManager->persist($location);
        $this->entityManager->flush();
        return $this->addMeasure($location);
    }
    private function addMeasure(Location $location): Response
    {
        $measurement = new Measurement();
        $measurement->setLocation($location);
        $measurement->setDate(new \DateTime());
        $measurement->setCelsius('40');
        $this->entityManager->persist($measurement);
        $this->entityManager->flush();
        return new Response('Measurement  ID: ' . $location->getId());
    }
}
