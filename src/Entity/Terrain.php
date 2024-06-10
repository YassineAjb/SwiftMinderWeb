<?php

namespace App\Entity;

use App\Repository\TerrainRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TerrainRepository::class)]
class Terrain

{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id =null;

    #[ORM\Column(length: 255)]
    private ?string $nomTerrain = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;


    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $geoX;

    #[ORM\Column]
    private ?float $geoY;

    #[ORM\Column]
    private ?DateTime $ouverture;

    #[ORM\Column]
    private ?DateTime $fermeture;

    #[ORM\Column(length: 255)]
    private ?string $datedispo =null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomTerrain(): ?string
    {
        return $this->nomTerrain;
    }

    public function setNomTerrain(string $nomTerrain): static
    {
        $this->nomTerrain = $nomTerrain;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getGeoX(): ?float
    {
        return $this->geoX;
    }

    public function setGeoX(?float $geoX): static
    {
        $this->geoX = $geoX;

        return $this;
    }

    public function getGeoY(): ?float
    {
        return $this->geoY;
    }

    public function setGeoY(?float $geoY): static
    {
        $this->geoY = $geoY;

        return $this;
    }

    public function getOuverture(): ?\DateTimeInterface
    {
        return $this->ouverture;
    }

    public function setOuverture(?\DateTimeInterface $ouverture): static
    {
        $this->ouverture = $ouverture;

        return $this;
    }

    public function getFermeture(): ?\DateTimeInterface
    {
        return $this->fermeture;
    }

    public function setFermeture(?\DateTimeInterface $fermeture): static
    {
        $this->fermeture = $fermeture;

        return $this;
    }

    public function getDatedispo(): ?string
    {
        return $this->datedispo;
    }

    public function setDatedispo(?string $datedispo): static
    {
        $this->datedispo = $datedispo;

        return $this;
    }


}
