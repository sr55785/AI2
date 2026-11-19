<?php

namespace App\Command;

use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:countryandcity',
    description: 'Add a short description for your command',
)]
class WeatherCountryandcityCommand{

    public function __construct(
        private readonly WeatherUtil $weatherUtil,
    ){}

    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Country')] string $countryCode,
        #[Argument('City')] string $cityName,
    ): int {
        $measurements = $this->weatherUtil->getWeatherForCountryAndCity($countryCode, $cityName);
        $io->writeln(sprintf('Location: %s, %s', $cityName, $countryCode));
        $io->table(['Date', 'Temperature'], array_map(fn($m) => [
            $m->getDate()->format('Y-m-d'),
            $m->getCelsius(),
        ], $measurements));

        return Command::SUCCESS;
    }
}
