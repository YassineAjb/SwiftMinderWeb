<?php

namespace App\Entity;


use App\Repository\BilletRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: BilletRepository::class)]
class Billet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idbillet =null;

    #[ORM\Column]
    private ?int $prixbillet =null;

    #[ORM\ManyToOne(targetEntity: Rencontre::class, inversedBy: 'billets')]
    #[ORM\JoinColumn(name: 'idrencontre_id', referencedColumnName: 'idrencontre')]
    private ?Rencontre $idrencontre;

    public function getIdbillet(): ?int
    {
        return $this->idbillet;
    }

    public function getPrixbillet(): ?int
    {
        return $this->prixbillet;
    }

    public function setPrixbillet(int $prixbillet): static
    {
        $this->prixbillet = $prixbillet;

        return $this;
    }

    public function getIdrencontre(): ?Rencontre
    {
        return $this->idrencontre;
    }

    public function setIdrencontre(?Rencontre $idrencontre): static
    {
        $this->idrencontre = $idrencontre;

        return $this;
    }


}
