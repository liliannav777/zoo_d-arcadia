<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $image_id;

    #[ORM\Column(type: 'blob')]
    private $image_data;

    #[ORM\ManyToMany(targetEntity: Habitat::class, mappedBy: 'images')]
    private Collection $habitats;

    public function __construct()
    {
        $this->habitats = new ArrayCollection();
    }

    public function getImageId(): ?int
    {
        return $this->image_id;
    }

    public function getImageData(): ?string
    {
        if ($this->image_data) {
            return base64_encode(stream_get_contents($this->image_data));
        }

        return null;
    }

    public function setImageData(string $image_data): self
    {
        $this->image_data = $image_data;

        return $this;
    }

    /**
     * @return Collection<int, Habitat>
     */
    public function getHabitats(): Collection
    {
        return $this->habitats;
    }

    public function addHabitat(Habitat $habitat): self
    {
        if (!$this->habitats->contains($habitat)) {
            $this->habitats->add($habitat);
            $habitat->addImage($this);
        }

        return $this;
    }

    public function removeHabitat(Habitat $habitat): self
    {
        if ($this->habitats->removeElement($habitat)) {
            $habitat->removeImage($this);
        }

        return $this;
    }
}
