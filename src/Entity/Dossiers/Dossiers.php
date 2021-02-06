<?php

namespace App\Entity\Dossiers;

use App\Entity\Tags;
use App\Entity\User;
use App\Repository\Dossiers\DossiersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=DossiersRepository::class)
 * @ORM\Table(name="Dossiers", indexes={@ORM\Index(columns={"name", "extrait", "content"}, flags={"fulltext"})})
 * @Vich\Uploadable
 * @UniqueEntity("name", message="Cette actualité existe déjà, veuillez réessayer")
 */
class Dossiers
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir le nom de l'actualité")
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
     * @Vich\UploadableField(mapping="dossiers_images", fileNameProperty="cover")
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/png"},
     *
     *     maxHeight=248,
     *     maxWidth=1600,
     *     maxHeightMessage="Votre image doit faire 248 d'hauteur",
     *     maxWidthMessage="Votre image doit faire 1600 de largeur"
     * )
     */
    private $imageCoverFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cover;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="dossiers_images", fileNameProperty="filename")
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/png"},
     *
     *     maxHeight=611,
     *     maxWidth=900,
     *     maxHeightMessage="Votre image doit faire 611 d'hauteur",
     *     maxWidthMessage="Votre image doit faire 900 de largeur"
     * )
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filename;

    /**
     * @ORM\Column(type="boolean")
     */
    private $online;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $extrait;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @var \DateTime $createdAt
     * @ORM\Column(type="datetime", nullable=true)
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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $view;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $publeshedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $featured;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="dossiers")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Categoriesdossiers::class, inversedBy="dossiers")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=Mediasdossiers::class, mappedBy="dossiers")
     */
    private $mediasdossiers;

    /**
     * @ORM\OneToMany(targetEntity=Commentairesdossiers::class, mappedBy="dossiers")
     */
    private $commentaire;

    /**
     * @ORM\ManyToMany(targetEntity=Tags::class, inversedBy="dossiers")
     */
    private $tags;

    public function __construct()
    {
        $this->mediasdossiers = new ArrayCollection();
        $this->commentaire= new ArrayCollection();
        $this->tags = new ArrayCollection();
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
    public function getImageCoverFile(): ?File
    {
        return $this->imageCoverFile;
    }

    /**
     * @param File|null $imageCoverFile
     * @return Dossiers
     * @throws \Exception
     */
    public function setImageCoverFile(?File $imageCoverFile): Dossiers
    {
        $this->imageCoverFile = $imageCoverFile;
        if ($this->imageCoverFile instanceof UploadedFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): self
    {
        $this->cover = $cover;

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
     * @return Dossiers
     * @throws \Exception
     */
    public function setImageFile(?File $imageFile): Dossiers
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

    public function getOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(bool $online): self
    {
        $this->online = $online;

        return $this;
    }

    public function getExtrait(): ?string
    {
        return $this->extrait;
    }

    public function setExtrait(?string $extrait): self
    {
        $this->extrait = $extrait;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

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

    public function getView(): ?int
    {
        return $this->view;
    }

    public function setView(?int $view): self
    {
        $this->view = $view;

        return $this;
    }

    public function getPubleshedAt(): ?string
    {
        return $this->publeshedAt;
    }

    public function setPubleshedAt(?string $publeshedAt): self
    {
        $this->publeshedAt = $publeshedAt;

        return $this;
    }

    public function getFeatured(): ?bool
    {
        return $this->featured;
    }

    public function setFeatured(bool $featured): self
    {
        $this->featured = $featured;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCategories(): ?Categoriesdossiers
    {
        return $this->categories;
    }

    public function setCategories(?Categoriesdossiers $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return Collection|Mediasdossiers[]
     */
    public function getMediasdossiers(): Collection
    {
        return $this->mediasdossiers;
    }

    public function addMediasdossier(Mediasdossiers $mediasdossier): self
    {
        if (!$this->mediasdossiers->contains($mediasdossier)) {
            $this->mediasdossiers[] = $mediasdossier;
            $mediasdossier->setDossiers($this);
        }

        return $this;
    }

    public function removeMediasdossier(Mediasdossiers $mediasdossier): self
    {
        if ($this->mediasdossiers->contains($mediasdossier)) {
            $this->mediasdossiers->removeElement($mediasdossier);
            // set the owning side to null (unless already changed)
            if ($mediasdossier->getDossiers() === $this) {
                $mediasdossier->setDossiers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentairesdossiers[]
     */
    public function getCommentaire(): Collection
    {
        return $this->commentaire;
    }

    public function addCommentaire(Commentairesdossiers $commentaire): self
    {
        if (!$this->commentaire->contains($commentaire)) {
            $this->commentaire[] = $commentaire;
            $commentaire->setDossiers($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentairesdossiers $commentaire): self
    {
        if ($this->commentaire->contains($commentaire)) {
            $this->commentaire->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getDossiers() === $this) {
                $commentaire->setDossiers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tags[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tags $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }
}
