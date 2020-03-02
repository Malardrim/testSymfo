<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * Catalogues of BS, the field catalogueId for this entity references itself
 *
 * @ORM\Entity(repositoryClass="App\Repository\CatalogueRepository")
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 * )
 */
class Catalogue extends Entry
{
    /**
     * @var int
     */
    protected $entriesNb = 0;

    /**
     * @param mixed $entriesNb
     * @return Catalogue
     */
    public function setEntriesNb($entriesNb)
    {
        $this->entriesNb = $entriesNb;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntriesNb()
    {
        return $this->entriesNb;
    }
}
