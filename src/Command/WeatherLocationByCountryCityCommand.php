<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use App\Entity\Measurement;
#[AsCommand(
    name: 'weather:location-by-country-city',
    description: 'Add a short description for your command',
)]
class WeatherLocationByCountryCityCommand extends Command
{
    protected static $defaultName = 'weather:location-by-country-city';

    private LocationRepository $locationRepository;
    private WeatherUtil $weatherUtil;

    public function __construct(LocationRepository $locationRepository, WeatherUtil $weatherUtil)
    {
        parent::__construct();
        $this->locationRepository = $locationRepository;
        $this->weatherUtil = $weatherUtil;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Pobiera prognozę pogody na podstawie kodu kraju i nazwy miejscowości')
            ->addArgument('countryCode', InputArgument::REQUIRED, 'Kod kraju') // Изменено на 'countryCode'
            ->addArgument('city', InputArgument::REQUIRED, 'Nazwa miejscowości');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $countryCode = $input->getArgument('countryCode'); // Изменено на 'countryCode'
        $city = $input->getArgument('city');

        try {

            $weatherData = $this->weatherUtil->getWeatherForCountryAndCity($city, $countryCode);

            // Получаем местоположение и измерения
            $measurements = $weatherData['measurements'];
            $location = $weatherData['location'];


            $io->writeln(sprintf('Location: %s, %s', $location->getCity(), $location->getCountry()));


            if (empty($measurements)) {
                $io->error('Измерения не найдены для этого местоположения.');
                return Command::FAILURE;
            }


            foreach ($measurements as $measurement) {
                if ($measurement instanceof Measurement) {
                    $io->writeln(sprintf("\t%s: %s", $measurement->getDate()->format('Y-m-d'), $measurement->getCelsius()));
                } else {
                    $io->error('Объект measurement не является экземпляром класса Measurement.');
                    $io->writeln(print_r($measurement, true));
                }
            }

        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
