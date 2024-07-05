<?php

// src/Entity/RapportVeterinaire.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class RapportVeterinaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $rapport_veterinaire_id;

    #[ORM\Column(type: 'date')]
    private $date;

    #[ORM\Column(type: 'string', length: 50)]
    private $detail;

    #[ORM\ManyToOne(targetEntity: Animal::class, inversedBy: 'rapportsVeterinaires')]
    #[ORM\JoinColumn(name: 'animal_id', referencedColumnName: 'animal_id', nullable: false)]
    private ?Animal $animal = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'rapportsVeterinaires')]
    #[ORM\JoinColumn(name: 'username', referencedColumnName: 'username', nullable: false)]
    private ?Utilisateur $utilisateur = null;

    public function getRapportVeterinaireId(): ?int
    {
        return $this->rapport_veterinaire_id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(string $detail): self
    {
        $this->detail = $detail;

        return $this;
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

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
