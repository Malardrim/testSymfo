<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * An entry that is selectable
 *
 * @ORM\Entity(repositoryClass="App\Repository\SelectionEntryRepository")
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 * )
 */
class SelectionEntry extends Entry
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $type;

    /**
     * @param mixed $type
     * @return SelectionEntry
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }
}
