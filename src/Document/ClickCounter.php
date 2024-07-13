<?php 
// src/Document/ClickCounter.php
namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="click_counters")
 */
class ClickCounter
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $prenom;

    /** @ODM\Field(type="int") */
    private $count;

    public function getId(): ?string
    {
        return $this->id;
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

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;
        return $this;
    }
}
