<?php

namespace App\Entity\Dossiers;

use App\Repository\Dossiers\MediasdossiersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=MediasdossiersRepository::class)
 * @Vich\Uploadable
 */
class Mediasdossiers
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
     * @Vich\UploadableField(mapping="dossiers_medias", fileNameProperty="filename")
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
     * @ORM\ManyToOne(targetEntity=Dossiers::class, inversedBy="mediasdossiers")
     */
    private $dossiers;

    /**
     * @var integer|null
     */
    private $dossier_id;

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
     * @return MediasDossiers
     * @throws \Exception
     */
    public function setImageFile(?File $imageFile): MediasDossiers
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

    public function getDossiers(): ?Dossiers
    {
        return $this->dossiers;
    }

    public function setDossiers(?Dossiers $dossiers): self
    {
        $this->dossiers = $dossiers;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDossierId(): ?int
    {
        return $this->dossier_id;
    }

    /**
     * @param int|null $dossier_id
     * @return MediasDossiers
     */
    public function setDossierId(?int $dossier_id): MediasDossiers
    {
        $this->dossier_id = $dossier_id;
        return $this;
    }
}
