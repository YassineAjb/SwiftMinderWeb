<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idcommande =null;

    #[ORM\Column]
    private ?int $iduser =null;

    #[ORM\Column]
    private ?int $somme =null;

    #[ORM\Column]
    private ?int $quantite =null;

    #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(name: 'idproduit', referencedColumnName: 'idproduit')]
/*#[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(name: 'idproduit_id', referencedColumnName: 'idproduit')]
    private ?Produit $idproduit;*/
private ?Produit $idproduit;

    public function getIdcommande(): ?int
    {
        return $this->idcommande;
    }

    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function setIduser(int $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getSomme(): ?int
    {
        return $this->somme;
    }

    public function setSomme(int $somme): static
    {
        $this->somme = $somme;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getIdproduit(): ?Produit
    {
        return $this->idproduit;
    }

    public function setIdproduit(?Produit $idproduit): static
    {
        $this->idproduit = $idproduit;

        return $this;
    }


}
