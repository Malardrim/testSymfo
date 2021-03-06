<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 */
class Item
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="rule.name.blank")
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="rule.name.blank")
     */
    private $points;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank(message="rule.name.blank")
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Phase")
     * @Assert\NotBlank(message="rule.name.blank")
     */
    private $phases;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="rule.name.blank")
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $reach;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Faction")
     */
    private $faction;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="rule.name.blank")
     */
    private $strength;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="rule.name.blank")
     */
    private $damage;

    public function __construct()
    {
        $this->phases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Phase[]
     */
    public function getPhases(): Collection
    {
        return $this->phases;
    }

    public function addPhase(Phase $phase): self
    {
        if (!$this->phases->contains($phase)) {
            $this->phases[] = $phase;
        }

        return $this;
    }

    public function removePhase(Phase $phase): self
    {
        if ($this->phases->contains($phase)) {
            $this->phases->removeElement($phase);
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getReach(): ?int
    {
        return $this->reach;
    }

    public function setReach(?int $reach): self
    {
        $this->reach = $reach;

        return $this;
    }


    public function getStrength(): ?string
    {
        return $this->strength;
    }

    public function setStrength(string $strength): self
    {
        $this->strength = $strength;

        return $this;
    }

    public function getDamage(): ?string
    {
        return $this->damage;
    }

    public function setDamage(string $damage): self
    {
        $this->damage = $damage;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFaction()
    {
        return $this->faction;
    }

    /**
     * @param mixed $faction
     * @return Item
     */
    public function setFaction($faction)
    {
        $this->faction = $faction;
        return $this;
    }
}
