<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $reservationid =null;

    #[ORM\Column(length: 255)]
    private ?string $datereservation;

    #[ORM\Column(length: 255)]
    private ?string $note;

    #[ORM\Column(length: 255)]
    private ?string $emplacement;

    #[ORM\ManyToOne(targetEntity: Terrain::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'idterrain_id', referencedColumnName: 'id')]
    private ?Terrain $idterrain;

    public function getReservationid(): ?int
    {
        return $this->reservationid;
    }

    public function getDatereservation(): ?string
    {
        return $this->datereservation;
    }

    public function setDatereservation(?string $datereservation): static
    {
        $this->datereservation = $datereservation;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getEmplacement(): ?string
    {
        return $this->emplacement;
    }

    public function setEmplacement(?string $emplacement): static
    {
        $this->emplacement = $emplacement;

        return $this;
    }

    public function getIdterrain(): Terrain
    {
        return $this->idterrain;
    }

    public function setIdterrain(Terrain $idterrain): static
    {
        $this->idterrain = $idterrain;

        return $this;
    }


}
