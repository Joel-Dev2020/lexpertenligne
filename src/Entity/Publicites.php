<?php

namespace App\Entity;

use App\Repository\PublicitesRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=PublicitesRepository::class)
 * @Vich\Uploadable
 */
class Publicites
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pubblock1;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="publicites", fileNameProperty="filenamepubblock1")
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/png"},
     *     maxHeight=150,
     *     maxWidth=644,
     *     maxHeightMessage="Votre image doit faire 150 d'hauteur",
     *     maxWidthMessage="Votre image doit faire 644 de largeur"
     * )
     */
    private $imageFile1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filenamepubblock1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlpubblock1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pubblock2;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="publicites", fileNameProperty="filenamepubblock2")
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/png"},
     *     maxHeight=150,
     *     maxWidth=644,
     *     maxHeightMessage="Votre image doit faire 150 d'hauteur",
     *     maxWidthMessage="Votre image doit faire 644 de largeur"
     * )
     */
    private $imageFile2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filenamepubblock2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlpubblock2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pubblock3;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="publicites", fileNameProperty="filenamepubblock3")
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/png"},
     *     maxHeight=210,
     *     maxWidth=1170,
     *     maxHeightMessage="Votre image doit faire 210 d'hauteur",
     *     maxWidthMessage="Votre image doit faire 1170 de largeur"
     * )
     */
    private $imageFile3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filenamepubblock3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlpubblock3;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $onlinepub1;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $onlinepub2;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $onlinepub3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pubblock4;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="publicites", fileNameProperty="filenamepubblock4")
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/png"},
     *     maxHeight=264,
     *     maxWidth=270,
     *     maxHeightMessage="Votre image doit faire 264 d'hauteur",
     *     maxWidthMessage="Votre image doit faire 270 de largeur"
     * )
     */
    private $imageFile4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filenamepubblock4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlpubblock4;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $onlinepub4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pubblock5;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="publicites", fileNameProperty="filenamepubblock5")
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/png"},
     *     maxHeight=141,
     *     maxWidth=265,
     *     maxHeightMessage="Votre image doit faire 141 d'hauteur",
     *     maxWidthMessage="Votre image doit faire 265 de largeur"
     * )
     */
    private $imageFile5;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filenamepubblock5;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlpubblock5;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $onlinepub5;

    /**
     * @var \DateTime $updatedAt
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPubblock1(): ?string
    {
        return $this->pubblock1;
    }

    public function setPubblock1(?string $pubblock1): self
    {
        $this->pubblock1 = $pubblock1;

        return $this;
    }

    public function getFilenamepubblock1(): ?string
    {
        return $this->filenamepubblock1;
    }

    /**
     * @return File|null
     */
    public function getImageFile1(): ?File
    {
        return $this->imageFile1;
    }

    /**
     * @param File|null $imageFile
     * @return Publicites
     * @throws \Exception
     */
    public function setImageFile1(?File $imageFile): Publicites
    {
        $this->imageFile1 = $imageFile;
        if ($this->imageFile1 instanceof UploadedFile) {
            $this->updatedAt = new \DateTime('now');
        }
        return $this;
    }

    public function setFilenamepubblock1(?string $filenamepubblock1): self
    {
        $this->filenamepubblock1 = $filenamepubblock1;

        return $this;
    }

    public function getUrlpubblock1(): ?string
    {
        return $this->urlpubblock1;
    }

    public function setUrlpubblock1(?string $urlpubblock1): self
    {
        $this->urlpubblock1 = $urlpubblock1;

        return $this;
    }

    public function getPubblock2(): ?string
    {
        return $this->pubblock2;
    }

    public function setPubblock2(?string $pubblock2): self
    {
        $this->pubblock2 = $pubblock2;

        return $this;
    }

    public function getFilenamepubblock2(): ?string
    {
        return $this->filenamepubblock2;
    }

    /**
     * @return File|null
     */
    public function getImageFile2(): ?File
    {
        return $this->imageFile2;
    }

    /**
     * @param File|null $imageFile
     * @return Publicites
     * @throws \Exception
     */
    public function setImageFile2(?File $imageFile): Publicites
    {
        $this->imageFile2 = $imageFile;
        if ($this->imageFile2 instanceof UploadedFile) {
            $this->updatedAt = new \DateTime('now');
        }
        return $this;
    }

    public function setFilenamepubblock2(?string $filenamepubblock2): self
    {
        $this->filenamepubblock2 = $filenamepubblock2;

        return $this;
    }

    public function getUrlpubblock2(): ?string
    {
        return $this->urlpubblock2;
    }

    public function setUrlpubblock2(?string $urlpubblock2): self
    {
        $this->urlpubblock2 = $urlpubblock2;

        return $this;
    }

    public function getPubblock3(): ?string
    {
        return $this->pubblock3;
    }

    public function setPubblock3(?string $pubblock3): self
    {
        $this->pubblock3 = $pubblock3;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile3(): ?File
    {
        return $this->imageFile3;
    }

    /**
     * @param File|null $imageFile
     * @return Publicites
     * @throws \Exception
     */
    public function setImageFile3(?File $imageFile): Publicites
    {
        $this->imageFile3 = $imageFile;
        if ($this->imageFile3 instanceof UploadedFile) {
            $this->updatedAt = new \DateTime('now');
        }
        return $this;
    }

    public function getFilenamepubblock3(): ?string
    {
        return $this->filenamepubblock3;
    }

    public function setFilenamepubblock3(?string $filenamepubblock3): self
    {
        $this->filenamepubblock3 = $filenamepubblock3;

        return $this;
    }

    public function getUrlpubblock3(): ?string
    {
        return $this->urlpubblock3;
    }

    public function setUrlpubblock3(?string $urlpubblock3): self
    {
        $this->urlpubblock3 = $urlpubblock3;

        return $this;
    }

    public function getOnlinepub1(): ?bool
    {
        return $this->onlinepub1;
    }

    public function setOnlinepub1(?bool $onlinepub1): self
    {
        $this->onlinepub1 = $onlinepub1;

        return $this;
    }

    public function getOnlinepub2(): ?bool
    {
        return $this->onlinepub2;
    }

    public function setOnlinepub2(?bool $onlinepub2): self
    {
        $this->onlinepub2 = $onlinepub2;

        return $this;
    }

    public function getOnlinepub3(): ?bool
    {
        return $this->onlinepub3;
    }

    public function setOnlinepub3(?bool $onlinepub3): self
    {
        $this->onlinepub3 = $onlinepub3;

        return $this;
    }

    public function getPubblock4(): ?string
    {
        return $this->pubblock4;
    }

    public function setPubblock4(?string $pubblock4): self
    {
        $this->pubblock4 = $pubblock4;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile4(): ?File
    {
        return $this->imageFile4;
    }

    /**
     * @param File|null $imageFile
     * @return Publicites
     * @throws \Exception
     */
    public function setImageFile4(?File $imageFile): Publicites
    {
        $this->imageFile4 = $imageFile;
        if ($this->imageFile4 instanceof UploadedFile) {
            $this->updatedAt = new \DateTime('now');
        }
        return $this;
    }

    public function getFilenamepubblock4(): ?string
    {
        return $this->filenamepubblock4;
    }

    public function setFilenamepubblock4(?string $filenamepubblock4): self
    {
        $this->filenamepubblock4 = $filenamepubblock4;

        return $this;
    }

    public function getUrlpubblock4(): ?string
    {
        return $this->urlpubblock4;
    }

    public function setUrlpubblock4(?string $urlpubblock4): self
    {
        $this->urlpubblock4 = $urlpubblock4;

        return $this;
    }

    public function getOnlinepub4(): ?bool
    {
        return $this->onlinepub4;
    }

    public function setOnlinepub4(?bool $onlinepub4): self
    {
        $this->onlinepub4 = $onlinepub4;

        return $this;
    }

    public function getPubblock5(): ?string
    {
        return $this->pubblock5;
    }

    public function setPubblock5(?string $pubblock5): self
    {
        $this->pubblock5 = $pubblock5;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile5(): ?File
    {
        return $this->imageFile5;
    }

    /**
     * @param File|null $imageFile
     * @return Publicites
     * @throws \Exception
     */
    public function setImageFile5(?File $imageFile): Publicites
    {
        $this->imageFile5 = $imageFile;
        if ($this->imageFile5 instanceof UploadedFile) {
            $this->updatedAt = new \DateTime('now');
        }
        return $this;
    }

    public function getFilenamepubblock5(): ?string
    {
        return $this->filenamepubblock5;
    }

    public function setFilenamepubblock5(?string $filenamepubblock5): self
    {
        $this->filenamepubblock5 = $filenamepubblock5;

        return $this;
    }

    public function getUrlpubblock5(): ?string
    {
        return $this->urlpubblock5;
    }

    public function setUrlpubblock5(?string $urlpubblock5): self
    {
        $this->urlpubblock5 = $urlpubblock5;

        return $this;
    }

    public function getOnlinepub5(): ?bool
    {
        return $this->onlinepub5;
    }

    public function setOnlinepub5(?bool $onlinepub5): self
    {
        $this->onlinepub5 = $onlinepub5;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }
}
