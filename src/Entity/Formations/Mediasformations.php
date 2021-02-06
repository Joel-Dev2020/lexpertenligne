<?php

namespace App\Entity\Formations;

use App\Repository\Formations\MediasformationsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=MediasformationsRepository::class)
 * @Vich\Uploadable
 */
class Mediasformations
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
    private $name;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="formations_medias", fileNameProperty="filename")
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/png"},
     *     maxHeight=611,
     *     maxWidth=900,
     *     maxHeightMessage="Votre image doit faire 611 d'hauteur",
     *     maxWidthMessage="Votre image doit faire 900 de largeur"
     * )
     * @Assert\NotBlank()
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @var \DateTime $updatedAt
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Formations::class, inversedBy="mediasformations")
     */
    private $formations;

    /**
     * @var integer|null
     */
    private $formation_id;

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

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     * @return MediasFormations
     * @throws \Exception
     */
    public function setImageFile(?File $imageFile): MediasFormations
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getFormations(): ?Formations
    {
        return $this->formations;
    }

    public function setFormations(?Formations $formations): self
    {
        $this->formations = $formations;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getFormationId(): ?int
    {
        return $this->formation_id;
    }

    /**
     * @param int|null $formation_id
     * @return MediasFormations
     */
    public function setFormationId(?int $formation_id): MediasFormations
    {
        $this->formation_id = $formation_id;
        return $this;
    }
}
