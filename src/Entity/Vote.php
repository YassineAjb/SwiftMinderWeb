<?php

namespace App\Entity;

use App\Entity\Candidat;
use App\Entity\Evenement;
use App\Repository\VoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoteRepository::class)]
class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idV')]
    private ?int $idv = null;

    #[ORM\ManyToOne(targetEntity: Candidat::class)]
    #[ORM\JoinColumn(name: 'idCandidatV', referencedColumnName: 'idc')]
    private ?Candidat $candidat = null;

    #[ORM\ManyToOne(targetEntity: Evenement::class)]
    #[ORM\JoinColumn(name: 'idElectionV', referencedColumnName: 'ide')]
    private ?Evenement $evenement = null;

    #[ORM\Column(name: 'idUser')]
    private ?int $idUser = null;    

    public function getIdv(): ?int
    {
        return $this->idv;
    }

    public function setIdv(int $idv): self
    {
        $this->idv = $idv;

        return $this;
    }

    public function getidUser(): ?int
    {
        return $this->idUser;
    }

    public function setidUser(int $idUser): self
    {
        $this->idUser = $idUser;
    
        return $this;
    }
    

    public function getCandidat(): ?Candidat
    {
        return $this->candidat;
    }

    public function setCandidat(?Candidat $candidat): self
    {
        $this->candidat = $candidat;

        return $this;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        $this->evenement = $evenement;

        return $this;
    }
}
