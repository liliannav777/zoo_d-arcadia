<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class RapportEmploye
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Animal::class, inversedBy: 'rapportsEmploye')]
    #[ORM\JoinColumn(nullable: false, name: 'animal_id', referencedColumnName: 'animal_id')]
    private ?Animal $animal = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $date;

    #[ORM\Column(type: 'string', length: 100)]
    private string $nourriture;

    #[ORM\Column(type: 'float')]
    private float $quantite;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'rapportsEmploye')]
    #[ORM\JoinColumn(nullable: false, name: 'username', referencedColumnName: 'username')]
    private ?Utilisateur $employe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): self
    {
        $this->animal = $animal;
        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getNourriture(): string
    {
        return $this->nourriture;
    }

    public function setNourriture(string $nourriture): self
    {
        $this->nourriture = $nourriture;
        return $this;
    }

    public function getQuantite(): float
    {
        return $this->quantite;
    }

    public function setQuantite(float $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getEmploye(): ?Utilisateur
    {
        return $this->employe;
    }

    public function setEmploye(?Utilisateur $employe): self
    {
        $this->employe = $employe;
        return $this;
    }
}
