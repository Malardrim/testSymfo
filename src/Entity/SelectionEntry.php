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

}
