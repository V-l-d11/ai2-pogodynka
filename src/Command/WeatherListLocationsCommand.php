<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use App\Repository\LocationRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:list-locations',
    description: 'Add a short description for your command',
)]
class WeatherListLocationsCommand extends Command
{
    protected static $defaultName = 'weather:list-locations';
    private LocationRepository $locationRepository;

    public function __construct(LocationRepository $locationRepository)
    {
        parent::__construct();
        $this->locationRepository = $locationRepository;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Lists all available locations with country code and city name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $locations = $this->locationRepository->findAll();

        if (empty($locations)) {
            $io->warning('No locations found in the database.');
            return Command::SUCCESS;
        }

        $io->title('Available Locations');
        foreach ($locations as $location) {
            $io->writeln(sprintf('ID: %d | Country Code: %s | City: %s',
                $location->getId(),
                $location->getCountry(),
                $location->getCity()
            ));
        }

        return Command::SUCCESS;
    }
}
