<?php

namespace App\Entity;

use App\Entity\Shop\Categories;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Parametres
 *
 * @ORM\Table(name="parametres")
 * @ORM\Entity(repositoryClass="App\Repository\ParametresRepository")
 * @Vich\Uploadable
 * @UniqueEntity("name")
 * @ORM\Entity
 */
class Parametres
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="keywords", type="text", nullable=true)
     */
    private $keywords;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telephone", type="string", length=30, nullable=true)
     */
    private $telephone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cellulaire", type="string", length=30, nullable=true)
     */
    private $cellulaire;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="structures_logo", fileNameProperty="filename")
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/png"},
     *     maxHeight=110,
     *     maxWidth=300,
     * )
     */
    private $imageFile;

    /**
     * @var string|null
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=true)
     */
    private $filename;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="structures_logo", fileNameProperty="filename2")
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/png"},
     *     maxHeight=110,
     *     maxWidth=300,
     * )
     */
    private $imageFile2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="filename2", type="string", length=255, nullable=true)
     */
    private $filename2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adresses", type="text", length=0, nullable=true)
     */
    private $adresses;

    /**
     * @var \DateTime $updatedAt
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="activite", type="string", length=255, nullable=true)
     */
    private $activite;

    /**
     * @var string|null
     *
     * @ORM\Column(name="slogan", type="string", length=255, nullable=true)
     */
    private $slogan;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $instagram;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $twitter;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $tva;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $seuilproduct;
    

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getCellulaire(): ?string
    {
        return $this->cellulaire;
    }

    public function setCellulaire(?string $cellulaire): self
    {
        $this->cellulaire = $cellulaire;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

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
     * @return Parametres
     * @throws \Exception
     */
    public function setImageFile(?File $imageFile): Parametres
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

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile2(): ?File
    {
        return $this->imageFile2;
    }

    /**
     * @param File|null $imageFile2
     * @return Parametres
     * @throws \Exception
     */
    public function setImageFile2(?File $imageFile2): Parametres
    {
        $this->imageFile2 = $imageFile2;
        if ($this->imageFile2 instanceof UploadedFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
        return $this;
    }

    public function getFilename2(): ?string
    {
        return $this->filename2;
    }

    public function setFilename2(?string $filename2): self
    {
        $this->filename2 = $filename2;

        return $this;
    }

    public function getAdresses(): ?string
    {
        return $this->adresses;
    }

    public function setAdresses(?string $adresses): self
    {
        $this->adresses = $adresses;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getActivite(): ?string
    {
        return $this->activite;
    }

    public function setActivite(?string $activite): self
    {
        $this->activite = $activite;

        return $this;
    }

    public function getSlogan(): ?string
    {
        return $this->slogan;
    }

    public function setSlogan(?string $slogan): self
    {
        $this->slogan = $slogan;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @param mixed $twitter
     * @return Parametres
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    /**
     * @param string|null $keywords
     * @return Parametres
     */
    public function setKeywords(?string $keywords): Parametres
    {
        $this->keywords = $keywords;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTva()
    {
        return $this->tva;
    }

    /**
     * @param mixed $tva
     * @return Parametres
     */
    public function setTva($tva)
    {
        $this->tva = $tva;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSeuilproduct()
    {
        return $this->seuilproduct;
    }

    /**
     * @param mixed $seuilproduct
     * @return Parametres
     */
    public function setSeuilproduct($seuilproduct)
    {
        $this->seuilproduct = $seuilproduct;
        return $this;
    }
}
