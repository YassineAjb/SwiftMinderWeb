<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Category;
use ContainerIyR6A4u\getTicketRepositoryService;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idproduit = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du produit ne peut pas être vide.")]
    #[Assert\Length(
        min: 5,
        minMessage: "Le nom du produit doit contenir au moins {{ limit }} caractères.",
        max: 20,
        maxMessage: "Le nom du produit ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $nomproduit;

    #[ORM\Column(nullable: true)]
    #[Assert\NotNull(message: "Le prix du produit ne peut pas être vide.")]
    #[Assert\Type(type: 'numeric', message: "Le prix doit être un nombre.")]
    #[Assert\Positive(message: "Le prix doit être un nombre positif.")]
    private ?int $prixproduit = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 2, maxMessage: "La taille du produit ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $tailleproduit;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "Le type du produit ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $type;

    #[ORM\Column(nullable: true)]
    #[Assert\NotNull(message: "La quantité du produit ne peut pas être vide.")]
    #[Assert\Type(type: 'integer', message: "La quantité doit être un entier.")]
    private ?int $qauntiteproduit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?Category $category = null;    public function getIdproduit(): ?int
    {
        return $this->idproduit;
    }

    public function getNomproduit(): ?string
    {
        return $this->nomproduit;
    }

    public function setNomproduit(string $nomproduit): static
    {
        $this->nomproduit = $nomproduit;

        return $this;
    }

    public function getPrixproduit(): ?int
    {
        return $this->prixproduit;
    }

    public function setPrixproduit(int $prixproduit): static
    {
        $this->prixproduit = $prixproduit;

        return $this;
    }

    public function getTailleproduit(): ?string
    {
        return $this->tailleproduit;
    }

    public function setTailleproduit(string $tailleproduit): static
    {
        $this->tailleproduit = $tailleproduit;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getQauntiteproduit(): ?int
    {
        return $this->qauntiteproduit;
    }

    public function setQauntiteproduit(int $qauntiteproduit): static
    {
        $this->qauntiteproduit = $qauntiteproduit;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }
    public function getTitle(): ?Category
    {
        return $this->getCategory()->getTitre();
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }


}