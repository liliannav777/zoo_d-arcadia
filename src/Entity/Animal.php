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

    #[ORM\OneToMany(mappedBy: 'animal', targetEntity: RapportVeterinaire::class)]
    private $rapportsVeterinaires;

    #[ORM\ManyToOne(targetEntity: Race::class, inversedBy: 'animal')]
    #[ORM\JoinColumn(nullable: false, name: 'race_id', referencedColumnName: 'race_id')]
    private ?Race $race = null;

    #[ORM\ManyToOne(targetEntity: Habitat::class, inversedBy: 'animaux')]
    #[ORM\JoinColumn(name: 'habitat_id', referencedColumnName: 'habitat_id', nullable: false)]
    private ?Habitat $habitat = null;

    public function __construct() {
        $this->rapportsVeterinaires = new ArrayCollection();
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

    /**
     * @return Collection|RapportVeterinaire[]
     */
    public function getRapportsVeterinaires(): Collection
    {
        return $this->rapportsVeterinaires;
    }

    public function addRapportVeterinaire(RapportVeterinaire $rapportVeterinaire): self
    {
        if (!$this->rapportsVeterinaires->contains($rapportVeterinaire)) {
            $this->rapportsVeterinaires[] = $rapportVeterinaire;
            $rapportVeterinaire->setAnimal($this);
        }

        return $this;
    }

    public function removeRapportVeterinaire(RapportVeterinaire $rapportVeterinaire): self
    {
        if ($this->rapportsVeterinaires->removeElement($rapportVeterinaire)) {
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

    public function getRaceName():string
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

    public function getImagePath(): string
    {
        // Supposons que l'image est stockée sous le format 'assets/styles/images/animals/{slug}.jpg'
        return 'assets/styles/images/animals/' . $this->animal_id . '.jpg';
    }
}
