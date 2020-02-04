<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntryRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 * )
 */
class Entry
{
    /**
     * An entry, it's a generic data to get
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    protected $id;

    /**
     * @var array
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $properties = [];

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $dataType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entry", inversedBy="children")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Entry", mappedBy="parent")
     */
    protected $children;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", nullable=true, nullable=true)
     */
    private $hidden;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $catalogueId;

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

    /**
     * @param string $catalogueId
     * @return Entry
     */
    public function setCatalogueId(string $catalogueId): Entry
    {
        $this->catalogueId = $catalogueId;
        return $this;
    }

    /**
     * @return string
     */
    public function getCatalogueId(): string
    {
        return $this->catalogueId;
    }

    /**
     * @param Entry $parent
     * @return Entry
     */
    public function setParent(Entry $parent): Entry
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        return $this->parent;
    }

    /**
     * @param mixed $children
     * @return Entry
     */
    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param Entry $entry
     */
    public function addChild(Entry $entry){
        $this->children[] = $entry;
    }

    /**
     * @param string $dataType
     * @return Entry
     */
    public function setDataType(string $dataType): Entry
    {
        $this->dataType = $dataType;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDataType()
    {
        return $this->dataType;
    }
}
