<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $ide =null;

    #[ORM\Column(length: 255)]
    private ?string $nome;

    #[ORM\Column]
    private ?DateTime $datee;

    #[ORM\Column(length: 255)]
    private ?string $postee;

    #[ORM\Column(length: 255)]
    private ?string $periodep;

    #[ORM\Column(length: 255)]
    private ?string $imgepath;

    public function getIde(): ?int
    {
        return $this->ide;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): static
    {
        $this->nome = $nome;

        return $this;
    }

    public function getDatee(): ?\DateTimeInterface
    {
        return $this->datee;
    }

    public function setDatee(\DateTimeInterface $datee): static
    {
        $this->datee = $datee;

        return $this;
    }

    public function getPostee(): ?string
    {
        return $this->postee;
    }

    public function setPostee(string $postee): static
    {
        $this->postee = $postee;

        return $this;
    }

    public function getPeriodep(): ?string
    {
        return $this->periodep;
    }

    public function setPeriodep(string $periodep): static
    {
        $this->periodep = $periodep;

        return $this;
    }

    public function getImgepath(): ?string
    {
        return $this->imgepath;
    }

    public function setImgepath(string $imgepath): static
    {
        $this->imgepath = $imgepath;

        return $this;
    }

    public function __toString(){
        return (String)$this->getNome();
    }

}
