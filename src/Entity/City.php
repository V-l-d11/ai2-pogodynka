<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'cities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Country $country = null;

    #[ORM\OneToMany(targetEntity: WeatherDate::class, mappedBy: 'city', orphanRemoval: true)]
    private Collection $weatherDates;

    #[ORM\OneToMany(targetEntity: ClimateHistory::class, mappedBy: 'city', orphanRemoval: true)]
    private Collection $climateHistories;

    public function __construct()
    {
        $this->weatherDates = new ArrayCollection();
        $this->climateHistories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, WeatherDate>
     */
    public function getWeatherDates(): Collection
    {
        return $this->weatherDates;
    }

    public function addWeatherDate(WeatherDate $weatherDate): static
    {
        if (!$this->weatherDates->contains($weatherDate)) {
            $this->weatherDates->add($weatherDate);
            $weatherDate->setCity($this);
        }

        return $this;
    }

    public function removeWeatherDate(WeatherDate $weatherDate): static
    {
        if ($this->weatherDates->removeElement($weatherDate)) {
            // set the owning side to null (unless already changed)
            if ($weatherDate->getCity() === $this) {
                $weatherDate->setCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ClimateHistory>
     */
    public function getClimateHistories(): Collection
    {
        return $this->climateHistories;
    }

    public function addClimateHistory(ClimateHistory $climateHistory): static
    {
        if (!$this->climateHistories->contains($climateHistory)) {
            $this->climateHistories->add($climateHistory);
            $climateHistory->setCity($this);
        }

        return $this;
    }

    public function removeClimateHistory(ClimateHistory $climateHistory): static
    {
        if ($this->climateHistories->removeElement($climateHistory)) {
            // set the owning side to null (unless already changed)
            if ($climateHistory->getCity() === $this) {
                $climateHistory->setCity(null);
            }
        }

        return $this;
    }
}

