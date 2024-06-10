<?php

namespace App\Entity;

use App\Repository\RencontreRepository;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: RencontreRepository::class)]
class Rencontre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $idrencontre;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $daterencontre;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ adversaire ne doit pas être vide.")]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+$/',
        message: 'Le champ adversaire ne doit contenir que des lettres.'
    )]
    private ?string $adversaire;

    #[ORM\Column(length: 255)]
    private ?string $score;

    public function getIdrencontre(): ?int
    {
        return $this->idrencontre;
    }

    public function getDaterencontre(): ?\DateTimeInterface
    {
        return $this->daterencontre;
    }

    public function setDaterencontre(\DateTimeInterface $daterencontre): static
    {
        $this->daterencontre = $daterencontre;

        return $this;
    }

    public function getAdversaire(): ?string
    {
        return $this->adversaire;
    }

    public function setAdversaire(string $adversaire): static
    {
        $this->adversaire = $adversaire;

        return $this;
    }

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(?string $score): static
    {
        $this->score = $score;

        return $this;
    }
}