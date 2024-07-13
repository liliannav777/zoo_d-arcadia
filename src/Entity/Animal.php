<?php

// src/Entity/Animal.php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $animal_id;

    #[ORM\Column(type: 'string', length: 50)]
    private $prenom;

    #[ORM\Column(type: 'string', length: 50)]
    private $etat;

    #[ORM\OneToMany(mappedBy: 'animal', targetEntity: RapportEmploye::class)]
    private $rapportsEmploye;

    #[ORM\OneToMany(mappedBy: 'animal', targetEntity: RapportVeterinaire::class)]
    private $rapportVeterinaire;

    #[ORM\ManyToOne(targetEntity: Race::class, inversedBy: 'animaux')]
    #[ORM\JoinColumn(nullable: false, name: 'race_id', referencedColumnName: 'race_id')]
    private ?Race $race = null;

    #[ORM\ManyToOne(targetEntity: Habitat::class, inversedBy: 'animaux')]
    #[ORM\JoinColumn(name: 'habitat_id', referencedColumnName: 'habitat_id', nullable: false)]
    private ?Habitat $habitat = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imagePath = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private $detailsEtat;

    public function __construct() {
        $this->rapportsEmploye = new ArrayCollection();
        $this->rapportVeterinaire = new ArrayCollection();
    }

    public function getAnimalId(): ?int
    {
        return $this->animal_id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getRapportsEmploye(): Collection
    {
        return $this->rapportsEmploye;
    }

    public function addRapportEmploye(RapportEmploye $rapportEmploye): self
    {
        if (!$this->rapportsEmploye->contains($rapportEmploye)) {
            $this->rapportsEmploye[] = $rapportEmploye;
            $rapportEmploye->setAnimal($this);
        }

        return $this;
    }

    public function removeRapportEmploye(RapportEmploye $rapportEmploye): self
    {
        if ($this->rapportsEmploye->removeElement($rapportEmploye)) {
            // set the owning side to null (unless already changed)
            if ($rapportEmploye->getAnimal() === $this) {
                $rapportEmploye->setAnimal(null);
            }
        }

        return $this;
    }

    public function getRapportVeterinaire(): Collection
    {
        return $this->rapportVeterinaire;
    }

    public function addRapportVeterinaire(RapportVeterinaire $rapportVeterinaire): self
    {
        if (!$this->rapportVeterinaire->contains($rapportVeterinaire)) {
            $this->rapportVeterinaire[] = $rapportVeterinaire;
            $rapportVeterinaire->setAnimal($this);
        }

        return $this;
    }

    public function removeRapportVeterinaire(RapportVeterinaire $rapportVeterinaire): self
    {
        if ($this->rapportVeterinaire->removeElement($rapportVeterinaire)) {
            // set the owning side to null (unless already changed)
            if ($rapportVeterinaire->getAnimal() === $this) {
                $rapportVeterinaire->setAnimal(null);
            }
        }

        return $this;
    }

    public function getRace(): ?Race
    {
        return $this->race;
    }

    public function setRace(?Race $race): self
    {
        $this->race = $race;

        return $this;
    }

    public function getRaceName(): string
    {
        return $this->race ? $this->race->getLabel() : '';
    }

    public function getHabitat(): ?Habitat
    {
        return $this->habitat;
    }

    public function setHabitat(?Habitat $habitat): self
    {
        $this->habitat = $habitat;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath ? 'assets/styles/images/animals/' . $this->imagePath : 'assets/styles/images/default.jpg';
    }

    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function getDetailEtat(): ?string
    {
        return $this->detailsEtat;
    }

    public function setDetailEtat(?string $detailsEtat): self
    {
        $this->detailsEtat = $detailsEtat;

        return $this;
    }
}
