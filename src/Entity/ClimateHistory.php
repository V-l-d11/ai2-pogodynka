<?php

namespace App\Entity;

use App\Repository\ClimateHistoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClimateHistoryRepository::class)]
class ClimateHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $avarage_temperature = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $avarage_precipitation = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $avarage_wind_speed = null;

    #[ORM\OneToMany(targetEntity: Date::class, mappedBy: 'clim', orphanRemoval: true)]
    private Collection $date;


    #[ORM\ManyToOne(targetEntity: City::class, inversedBy: 'climateHistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $city = null;

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): static
    {
        $this->city = $city;

        return $this;
    }


    public function __construct()
    {
        $this->date = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAvarageTemperature(): ?string
    {
        return $this->avarage_temperature;
    }

    public function setAvarageTemperature(string $avarage_temperature): static
    {
        $this->avarage_temperature = $avarage_temperature;

        return $this;
    }

    public function getAvaragePrecipitation(): ?string
    {
        return $this->avarage_precipitation;
    }

    public function setAvaragePrecipitation(string $avarage_precipitation): static
    {
        $this->avarage_precipitation = $avarage_precipitation;

        return $this;
    }

    public function getAvarageWindSpeed(): ?string
    {
        return $this->avarage_wind_speed;
    }

    public function setAvarageWindSpeed(string $avarage_wind_speed): static
    {
        $this->avarage_wind_speed = $avarage_wind_speed;

        return $this;
    }

    /**
     * @return Collection<int, Date>
     */
    public function getDate(): Collection
    {
        return $this->date;
    }

    public function addDate(Date $date): static
    {
        if (!$this->date->contains($date)) {
            $this->date->add($date);
            $date->setClim($this);
        }

        return $this;
    }

    public function removeDate(Date $date): static
    {
        if ($this->date->removeElement($date)) {
            // set the owning side to null (unless already changed)
            if ($date->getClim() === $this) {
                $date->setClim(null);
            }
        }

        return $this;
    }
}

