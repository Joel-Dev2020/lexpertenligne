<?php

namespace App\Entity\Blogs;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Tags;
use App\Entity\User;
use App\Repository\Blogs\BlogsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * * @ApiResource(
 *     normalizationContext={"groups"={"comments:read"}},
 *     denormalizationContext={"groups"={"comments:write"}},
 *     collectionOperations={
 *          "get"={},
 *     },
 *     itemOperations={
 *          "get"={},
 *          "put"={},
 *          "delete"={},
 *     }
 * )
 * @ORM\Entity(repositoryClass=BlogsRepository::class)
 * @ORM\Table(name="blogs", indexes={@ORM\Index(columns={"name", "extrait", "content"}, flags={"fulltext"})})
 * @Vich\Uploadable
 * @UniqueEntity("name", message="Cette actualité existe déjà, veuillez réessayer")
 */
class Blogs
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"comments:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir le nom de l'actualité")
     * @Groups({"comments:read", "comments:write"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"name"})
     * @Groups({"comments:read", "comments:write"})
     */
    private $slug;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="blogs_images", fileNameProperty="cover")
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/png"},
     *
     *     maxHeight=248,
     *     maxWidth=1600,
     *     maxHeightMessage="Votre image doit faire 248 d'hauteur",
     *     maxWidthMessage="Votre image doit faire 1600 de largeur"
     * )
     * @Groups({"comments:read", "comments:write"})
     */
    private $imageCoverFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"comments:read", "comments:write"})
     */
    private $cover;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="blogs_images", fileNameProperty="filename")
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/png"},
     *
     *     maxHeight=611,
     *     maxWidth=900,
     *     maxHeightMessage="Votre image doit faire 611 d'hauteur",
     *     maxWidthMessage="Votre image doit faire 900 de largeur"
     * )
     * @Groups({"comments:read", "comments:write"})
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"comments:read", "comments:write"})
     */
    private $filename;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"comments:read", "comments:write"})
     */
    private $online;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"comments:read", "comments:write"})
     */
    private $extrait;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"comments:read", "comments:write"})
     */
    private $content;

    /**
     * @var \DateTime $createdAt
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"comments:read", "comments:write"})
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
     * @Groups({"comments:read", "comments:write"})
     */
    private $view;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Groups({"blogs:read", "blogs:write"})
     */
    private $publeshedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $featured;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="blogs")
     * @Groups({"comments:read", "comments:write"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Categoriesblogs::class, inversedBy="blogs")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=Mediasblogs::class, mappedBy="blogs")
     */
    private $mediasblogs;

    /**
     * @ORM\OneToMany(targetEntity=Commentairesblogs::class, mappedBy="blogs")
     */
    private $commentaire;

    /**
     * @ORM\ManyToMany(targetEntity=Tags::class, inversedBy="blogs")
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=Votesblogs::class, mappedBy="blogs")
     */
    private $votesblogs;

    public function __construct()
    {
        $this->mediasblogs = new ArrayCollection();
        $this->commentaire= new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->votesblogs = new ArrayCollection();
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
     * @return Blogs
     * @throws \Exception
     */
    public function setImageCoverFile(?File $imageCoverFile): Blogs
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
     * @return Blogs
     * @throws \Exception
     */
    public function setImageFile(?File $imageFile): Blogs
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

    public function getCategories(): ?Categoriesblogs
    {
        return $this->categories;
    }

    public function setCategories(?Categoriesblogs $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return Collection|Mediasblogs[]
     */
    public function getMediasblogs(): Collection
    {
        return $this->mediasblogs;
    }

    public function addMediasblog(Mediasblogs $mediasblog): self
    {
        if (!$this->mediasblogs->contains($mediasblog)) {
            $this->mediasblogs[] = $mediasblog;
            $mediasblog->setBlogs($this);
        }

        return $this;
    }

    public function removeMediasblog(Mediasblogs $mediasblog): self
    {
        if ($this->mediasblogs->contains($mediasblog)) {
            $this->mediasblogs->removeElement($mediasblog);
            // set the owning side to null (unless already changed)
            if ($mediasblog->getBlogs() === $this) {
                $mediasblog->setBlogs(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentairesblogs[]
     */
    public function getCommentaire(): Collection
    {
        return $this->commentaire;
    }

    public function addCommentaire(Commentairesblogs $commentaire): self
    {
        if (!$this->commentaire->contains($commentaire)) {
            $this->commentaire[] = $commentaire;
            $commentaire->setBlogs($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentairesblogs $commentaire): self
    {
        if ($this->commentaire->contains($commentaire)) {
            $this->commentaire->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getBlogs() === $this) {
                $commentaire->setBlogs(null);
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

    /**
     * @return Collection|Votesblogs[]
     */
    public function getVotesblogs(): Collection
    {
        return $this->votesblogs;
    }

    public function addVotesblog(Votesblogs $votesblog): self
    {
        if (!$this->votesblogs->contains($votesblog)) {
            $this->votesblogs[] = $votesblog;
            $votesblog->setBlogs($this);
        }

        return $this;
    }

    public function removeVotesblog(Votesblogs $votesblog): self
    {
        if ($this->votesblogs->contains($votesblog)) {
            $this->votesblogs->removeElement($votesblog);
            // set the owning side to null (unless already changed)
            if ($votesblog->getBlogs() === $this) {
                $votesblog->setBlogs(null);
            }
        }

        return $this;
    }
}
