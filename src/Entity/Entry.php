<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntryRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 */
class Entry
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    protected $id;

    /**
     * @var array
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $properties;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hidden;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getHidden(): ?bool
    {
        return $this->hidden;
    }

    public function setHidden(?bool $hidden): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Entry
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param array $properties
     * @return Entry
     */
    public function setProperties(array $properties): Entry
    {
        $this->properties = $properties;
        return $this;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param $property
     * @param $value
     */
    public function addProperty($property, $value){
        $this->properties[$property] = $value;
    }

    /**
     * @param $property
     */
    public function removeProperty($property){
        unset($this->properties[$property]);
    }
}
