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

    #[ORM\Column(type: 'string', length: 100)]
    private $nourriture;

    #[ORM\Column(type: 'string', length: 100)]
    private $grammage;

    #[ORM\ManyToOne(targetEntity: Animal::class, inversedBy: 'rapportsVeterinaires')]
    #[ORM\JoinColumn(name: 'animal_id', referencedColumnName: 'animal_id', nullable: false)]
    private ?Animal $animal;

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

    public function getNourriture(): ?string
    {
        return $this->nourriture;
    }

    public function setNourriture(string $nourriture): self
    {
        $this->nourriture = $nourriture;

        return $this;
    }

    public function getGrammage(): ?string
    {
        return $this->grammage;
    }

    public function setGrammage(string $grammage): self
    {
        $this->grammage = $grammage;

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

    public function getEtatAnimal(): ?string
    {
        return $this->animal ? $this->animal->getEtat() : null;
    }

    public function getDetailEtatAnimal(): ?string
    {
        return $this->animal ? $this->animal->getDetailEtat() : null;
    }

    public function setEtatAnimal(string $etatAnimal): self
    {
        if ($this->animal) {
            $this->animal->setEtat($etatAnimal);
        }
        return $this;
    }

    public function setDetailEtatAnimal(?string $detailEtatAnimal): self
    {
        if ($this->animal) {
            $this->animal->setDetailEtat($detailEtatAnimal);
        }
        return $this;
    }

    public function getCommentaireHabitat(): ?string
    {
        return $this->animal ? $this->animal->getHabitat()->getCommentaireHabitat() : null;
    }

    public function setCommentaireHabitat(?string $commentaireHabitat): self
    {
        if ($this->animal && $this->animal->getHabitat()) {
            $this->animal->getHabitat()->setCommentaireHabitat($commentaireHabitat);
        }
        return $this;
    }
}
