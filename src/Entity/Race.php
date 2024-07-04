<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Race
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $race_id;

    #[ORM\Column(type: 'string', length: 50)]
    private $label;

    #[ORM\OneToOne(mappedBy: 'race', targetEntity: Animal::class)]
    private $animal;

    // Getters et Setters...

    public function getRaceId(): ?int
    {
        return $this->race_id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): self
    {
        // unset the owning side of the relation if necessary
        if ($animal === null && $this->animal !== null) {
            $this->animal->setRace(null);
        }

        // set the owning side of the relation if necessary
        if ($animal !== null && $animal->getRace() !== $this) {
            $animal->setRace($this);
        }

        $this->animal = $animal;

        return $this;
    }
}
