<?php

namespace App\Entity;

use App\Repository\StationsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StationsRepository::class)]
class Stations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $idStation = null;

    #[ORM\Column(length: 255)]
    private ?string $stationName = null;

    #[ORM\Column(length: 255)]
    private ?string $stationAddress = null;

    #[ORM\Column(nullable: true)]
    private ?int $inseeCode = null;

    #[ORM\Column]
    private ?float $longitude = null;

    #[ORM\Column]
    private ?float $latitude = null;

    #[ORM\Column(nullable: true)]
    private ?float $maxPower = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isFree = null;

    #[ORM\Column(nullable: true)]
    private ?float $geopoint = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdStation(): ?string
    {
        return $this->idStation;
    }

    public function setIdStation(string $idStation): static
    {
        $this->idStation = $idStation;

        return $this;
    }

    public function getStationName(): ?string
    {
        return $this->stationName;
    }

    public function setStationName(string $stationName): static
    {
        $this->stationName = $stationName;

        return $this;
    }

    public function getStationAddress(): ?string
    {
        return $this->stationAddress;
    }

    public function setStationAddress(string $stationAddress): static
    {
        $this->stationAddress = $stationAddress;

        return $this;
    }

    public function getInseeCode(): ?int
    {
        return $this->inseeCode;
    }

    public function setInseeCode(?int $inseeCode): static
    {
        $this->inseeCode = $inseeCode;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getMaxPower(): ?float
    {
        return $this->maxPower;
    }

    public function setMaxPower(?float $maxPower): static
    {
        $this->maxPower = $maxPower;

        return $this;
    }

    public function isFree(): ?bool
    {
        return $this->isFree;
    }

    public function setFree(?bool $isFree): static
    {
        $this->isFree = $isFree;

        return $this;
    }

    public function getGeopoint(): ?float
    {
        return $this->geopoint;
    }

    public function setGeopoint(?float $geopoint): static
    {
        $this->geopoint = $geopoint;

        return $this;
    }
}
