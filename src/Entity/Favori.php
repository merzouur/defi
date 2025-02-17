<?php

namespace App\Entity;

use App\Repository\FavoriRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriRepository::class)]
class Favori
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'favoris')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'favoris')]
    private ?Figurine $figurine = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFigurine(): ?Figurine
    {
        return $this->figurine;
    }

    public function setFigurine(?Figurine $figurine): static
    {
        $this->figurine = $figurine;

        return $this;
    }
}
