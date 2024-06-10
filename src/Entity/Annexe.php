<?php

namespace App\Entity;

use App\Repository\AnnexeRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: AnnexeRepository::class)]
class Annexe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idannexe =null;

    #[ORM\Column(length: 255)]
    private ?string $nomannexe;

    #[ORM\Column(length: 255)]
    private ?string $localisation;

    public function getIdannexe(): ?int
    {
        return $this->idannexe;
    }

    public function getNomannexe(): ?string
    {
        return $this->nomannexe;
    }

    public function setNomannexe(string $nomannexe): static
    {
        $this->nomannexe = $nomannexe;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }


}
