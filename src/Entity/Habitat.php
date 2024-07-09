<?php

// src/Entity/Habitat.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Habitat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $habitat_id;

    #[ORM\Column(type: 'string', length: 50)]
    private $nom;

    #[ORM\Column(type: 'text')]
    private $description;


    #[ORM\Column(type: 'string', length: 50)]
    private $commentaire_habitat;

    #[ORM\OneToMany(mappedBy: 'habitat', targetEntity: Animal::class)]
    private Collection $animaux;

    #[ORM\ManyToMany(targetEntity: Image::class, mappedBy: 'habitats')]
    private Collection $images;


    public function __construct()
    {
        $this->animaux = new ArrayCollection();
    }

    public function getHabitatId(): ?int
    {
        return $this->habitat_id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return 'assets/styles/images/habitats/' . $this->habitat_id . '.jpg';
    }

    
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCommentaireHabitat(): ?string
    {
        return $this->commentaire_habitat;
    }

    public function setCommentaireHabitat(string $commentaire_habitat): self
    {
        $this->commentaire_habitat = $commentaire_habitat;

        return $this;
    }

    /**
     * @return Collection<int, Animal>
     */
    public function getAnimal(): Collection
    {
        return $this->animaux;
    }

    public function addAnimal(Animal $animal): self
    {
        if (!$this->animaux->contains($animal)) {
            $this->animaux->add($animal);
            $animal->setHabitat($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): self
    {
        if ($this->animaux->removeElement($animal)) {
            // set the owning side to null (unless already changed)
            if ($animal->getHabitat() === $this) {
                $animal->setHabitat(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->addHabitat($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            $image->removeHabitat($this);
        }

        return $this;
    }

}
