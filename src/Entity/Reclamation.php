<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idreclamation =null;

    #[ORM\Column(length: 255)]
    private ?string $titre;

    #[ORM\Column(length: 255)]
    private ?string $description;

    #[ORM\Column]
    private ?bool $etat =null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reclamations')]

    #[ORM\JoinColumn(name: 'iduser', referencedColumnName: 'iduser')]
    private ?User $user;

    public function getIdreclamation(): ?int
    {
        return $this->idreclamation;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }


}