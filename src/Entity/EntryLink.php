<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntryLinkRepository")
 */
class EntryLink extends Entry
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $targetId;

    /**
     * @var Entry
     */
    protected $targetObj;

    public function getTargetId(): ?string
    {
        return $this->targetId;
    }

    public function setTargetId(?string $targetId): self
    {
        $this->targetId = $targetId;

        return $this;
    }

    /**
     * @param Entry|null $targetObj
     * @return EntryLink
     */
    public function setTargetObj($targetObj): EntryLink
    {
        $this->targetObj = $targetObj;
        return $this;
    }

    /**
     * @return Entry|null
     */
    public function getTargetObj()
    {
        return $this->targetObj;
    }
}
