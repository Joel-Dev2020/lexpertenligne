<?php

namespace App\Entity\Documents;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Documents\DocumentsRepository")
 * @UniqueEntity("name")
 * @Vich\Uploadable
 */
class Documents
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="documents_pdf", fileNameProperty="filename")
     * @var File|null
     * @Assert\Image(
     *     maxSize="1024k",
     *     mimeTypes={"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage="Veuillez sélectionner un fichifer pdf valide"
     * )
     */
    private $documentFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="documents_image", fileNameProperty="imagename")
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/png", "image/jpeg"},
     *     maxHeight=300,
     *     maxWidth=600,
     *     maxHeightMessage="Votre image doit faire 300 d'hauteur",
     *     maxWidthMessage="Votre image doit faire 600 de largeur"
     * )
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imagename;

    /**
     * @var \DateTime $createdAt
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $online;

    /**
     * @ORM\ManyToOne(targetEntity=Categoriesdocuments::class, inversedBy="documents")
     */
    private $categories;

    public function __construct()
    {

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @return File|null
     */
    public function getDocumentFile(): ?File
    {
        return $this->documentFile;
    }

    /**
     * @param File|null $documentFile
     * @return Documents
     * @throws \Exception
     */
    public function setDocumentFile(?File $documentFile): Documents
    {
        $this->documentFile = $documentFile;
        if ($this->documentFile instanceof UploadedFile) {
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

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     * @return Documents
     * @throws \Exception
     */
    public function setImageFile(?File $imageFile): Documents
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
        return $this;
    }

    public function getImagename(): ?string
    {
        return $this->imagename;
    }

    public function setImagename(string $imagename): self
    {
        $this->imagename = $imagename;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(?bool $online): self
    {
        $this->online = $online;

        return $this;
    }

    public function getCategories(): ?Categoriesdocuments
    {
        return $this->categories;
    }

    public function setCategories(?Categoriesdocuments $categories): self
    {
        $this->categories = $categories;

        return $this;
    }
}
