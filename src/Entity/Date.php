<?php

namespace App\Entity;

use App\Repository\DateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DateRepository::class)]
class Date
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $day = null;

    #[ORM\Column]
    private ?int $month = null;

    #[ORM\Column]
    private ?int $hour = null;

    #[ORM\ManyToOne(targetEntity: ClimateHistory::class, inversedBy: 'date')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ClimateHistory $clim = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(int $day): static
    {
        $this->day = $day;

        return $this;
    }

    public function getMonth(): ?int
    {
        return $this->month;
    }

    public function setMonth(int $month): static
    {
        $this->month = $month;

        return $this;
    }

    public function getHour(): ?int
    {
        return $this->hour;
    }

    public function setHour(int $hour): static
    {
        $this->hour = $hour;

        return $this;
    }

    public function getClim(): ?ClimateHistory
    {
        return $this->clim;
    }

    public function setClim(?ClimateHistory $clim): static
    {
        $this->clim = $clim;

        return $this;
    }
}
