<?php

namespace App\Entity;

use App\Repository\CandidatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CandidatRepository::class)]
class Candidat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idc =null;

    #[ORM\Column(length: 255)]
    private ?string $nomc;

    #[ORM\Column(length: 255)]
    private ?string $prenomc;

    #[ORM\Column]
    private ?int $agec =null;

    #[ORM\Column(length: 255)]
    private ?string $imgcpath;

    // #[ORM\ManyToOne(targetEntity: Evenement::class, inversedBy: 'candidats')]
    // #[ORM\JoinColumn(name: 'idelection', referencedColumnName: 'ide')]
    // private ?Evenement $idelection;
    #[ORM\ManyToOne(targetEntity: Evenement::class, inversedBy: 'candidats')]
    #[ORM\JoinColumn(name: 'idelection', referencedColumnName: 'ide')]
    private ?Evenement $idelection;

    public function getIdc(): ?int
    {
        return $this->idc;
    }

    public function getNomc(): ?string
    {
        return $this->nomc;
    }

    public function setNomc(string $nomc): static
    {
        $this->nomc = $nomc;

        return $this;
    }

    public function getPrenomc(): ?string
    {
        return $this->prenomc;
    }

    public function setPrenomc(string $prenomc): static
    {
        $this->prenomc = $prenomc;

        return $this;
    }

    public function getAgec(): ?int
    {
        return $this->agec;
    }

    public function setAgec(int $agec): static
    {
        $this->agec = $agec;

        return $this;
    }

    public function getImgcpath(): ?string
    {
        return $this->imgcpath;
    }

    public function setImgcpath(string $imgcpath): static
    {
        $this->imgcpath = $imgcpath;

        return $this;
    }

    public function getIdelection(): ?Evenement
    {
        return $this->idelection;
    }

    public function setIdelection(?Evenement $idelection): static
    {
        $this->idelection = $idelection;

        return $this;
    }


}
