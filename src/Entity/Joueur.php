<?php

namespace App\Entity;

use App\Repository\JoueurRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: JoueurRepository::class)]
class Joueur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id =null;

    #[ORM\Column(length: 255)]
    private ?string $nom;

    #[ORM\Column(length: 255)]
    private ?string $prenom;

    #[ORM\Column]
    private ?int $age =null;

    #[ORM\Column(length: 255)]
    private ?string $position;

    #[ORM\Column]
    private ?int $hauteur =null;

    #[ORM\Column]
    private ?int $poids =null;

    #[ORM\Column(length: 255)]
    private ?string $piedfort;

    #[ORM\Column(length: 255)]
    private ?string $imagepath;

    #[ORM\Column(length: 255)]
    private ?string $link;

    #[ORM\Column(length: 255)]
    private ?string $QRcode;

    #[ORM\Column]
    private ?int $shirtnum =null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getHauteur(): ?int
    {
        return $this->hauteur;
    }

    public function setHauteur(int $hauteur): static
    {
        $this->hauteur = $hauteur;

        return $this;
    }

    public function getPoids(): ?int
    {
        return $this->poids;
    }

    public function setPoids(int $poids): static
    {
        $this->poids = $poids;

        return $this;
    }

    public function getPiedfort(): ?string
    {
        return $this->piedfort;
    }

    public function setPiedfort(string $piedfort): static
    {
        $this->piedfort = $piedfort;

        return $this;
    }

    public function getImagepath(): ?string
    {
        return $this->imagepath;
    }

    public function setImagepath(?string $imagepath): static
    {
        $this->imagepath = $imagepath;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getQRcode(): ?string
    {
        return $this->QRcode;
    }

    public function setQRcode(?string $QRcode): static
    {
        $this->QRcode = $QRcode;

        return $this;
    }

    public function getShirtnum(): ?int
    {
        return $this->shirtnum;
    }

    public function setShirtnum(int $shirtnum): static
    {
        $this->shirtnum = $shirtnum;

        return $this;
    }

}
