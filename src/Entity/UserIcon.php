<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass="App\Repository\UserIconRepository")
 */
class UserIcon implements \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $originalName;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="user_icon", fileNameProperty="name", size="size", originalName="originalName")
     * @Assert\Image(
     *     minWidth = 20,
     *     maxWidth = 60,
     *     minHeight = 20,
     *     maxHeight = 60,
     *     mimeTypes={"image/png","image/jpg","image/jpeg"}
     * )
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(?string $originalName): self
    {
        $this->originalName = $originalName;

        return $this;
    }

    /**
     * @param mixed $updatedAt
     * @return UserIcon
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param File|null $imageFile
     * @return UserIcon
     * @throws Exception
     */
    public function setImageFile(?File $imageFile): UserIcon
    {
        $this->imageFile = $imageFile;
        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize($this->id);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        $this->id = unserialize($serialized);
    }

    public function __toString()
    {
        $ret = "undefined";
        if (isset($this->imageFile)){
            $ret = $this->imageFile->getLinkTarget();
        }
        return $ret;
    }
}
