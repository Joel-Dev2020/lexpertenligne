<?php

namespace App\Entity\Shop;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Shop\ProductsRepository")
 * @ORM\Table(name="products", indexes={@ORM\Index(columns={"name", "extrait", "description"}, flags={"fulltext"})})
 * @Vich\Uploadable
 */
class Products
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir le nom du produit")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $metadescription;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="products_images", fileNameProperty="filename")
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/png"},
     *     maxHeight=1035,
     *     maxWidth=768,
     *     maxHeightMessage="Votre image doit être <=  1035 d'hauteur",
     *     maxWidthMessage="Votre image doit être <= 768 de large"
     * )
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filename;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="products_images", fileNameProperty="filenamehover")
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/png"},
     *     maxHeight=1035,
     *     maxWidth=768,
     *     maxHeightMessage="Votre image doit être <=  1035 d'hauteur",
     *     maxWidthMessage="Votre image doit être <= 1920 de large"
     * )
     */
    private $imageFileHover;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filenamehover;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $price;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default": 0})
     */
    private $pricepromo;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $weight;

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
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $online;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     * @Assert\Length(
     *     max="500", maxMessage="L'Extrait doit comporter au plus 255 catactères"
     * )
     */
    private $extrait;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $featured;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $nouveau;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="products")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Mediasproducts::class, mappedBy="products", cascade={"remove"})
     */
    private $mediasproducts;

    /**
     * @ORM\ManyToMany(targetEntity=Metakeywords::class, inversedBy="products", cascade={"remove"})
     */
    private $metakeywords;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $vues;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $delaislivraison;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $garantie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $videourl;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $payementAt;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $sku;

    /**
     * @ORM\OneToMany(targetEntity=Commentaireproducts::class, mappedBy="products", cascade={"remove"})
     */
    private $commentaireproducts;

    /**
     * @ORM\OneToMany(targetEntity=Approvisionnements::class, mappedBy="products", orphanRemoval=true)
     */
    private $approvisionnements;

    /**
     * @ORM\OneToMany(targetEntity=Wishlists::class, mappedBy="products", cascade={"remove"})
     */
    private $wishlists;

    /**
     * @ORM\ManyToMany(targetEntity=Products::class, inversedBy="productsassociated", cascade={"remove"})
     */
    private $association;

    /**
     * @ORM\ManyToMany(targetEntity=Products::class, mappedBy="association", cascade={"remove"})
     */
    private $productsassociated;

    /**
     * @ORM\ManyToMany(targetEntity=Categories::class, inversedBy="products")
     */
    private $categories;


    public function __construct()
    {
        $this->mediasproducts = new ArrayCollection();
        $this->metakeywords = new ArrayCollection();
        $this->commentaireproducts = new ArrayCollection();
        $this->approvisionnements = new ArrayCollection();
        $this->wishlists = new ArrayCollection();
        $this->association = new ArrayCollection();
        $this->productsassociated = new ArrayCollection();
        $this->categories = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMetadescription(): ?string
    {
        return $this->metadescription;
    }

    public function setMetadescription(?string $metadescription): self
    {
        $this->metadescription = $metadescription;

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
     * @return Products
     * @throws \Exception
     */
    public function setImageFile(?File $imageFile): Products
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
    public function getImageFileHover(): ?File
    {
        return $this->imageFileHover;
    }

    /**
     * @param File|null $imageFileHover
     * @return Products
     * @throws \Exception
     */
    public function setImageFileHover(?File $imageFileHover): Products
    {
        $this->imageFileHover = $imageFileHover;
        if ($this->imageFileHover instanceof UploadedFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
        return $this;
    }

    public function getFilenamehover(): ?string
    {
        return $this->filenamehover;
    }

    public function setFilenamehover(?string $filenamehover): self
    {
        $this->filenamehover = $filenamehover;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPricepromo()
    {
        return $this->pricepromo;
    }

    /**
     * @param mixed $pricepromo
     * @return Products
     */
    public function setPricepromo($pricepromo)
    {
        $this->pricepromo = $pricepromo;
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     * @return Products
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
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

    public function getExtrait(): ?string
    {
        return $this->extrait;
    }

    public function setExtrait(?string $extrait): self
    {
        $this->extrait = $extrait;

        return $this;
    }

    public function getFeatured(): ?bool
    {
        return $this->featured;
    }

    public function setFeatured(?bool $featured): self
    {
        $this->featured = $featured;

        return $this;
    }

    public function getNouveau(): ?bool
    {
        return $this->nouveau;
    }

    public function setNouveau(?bool $nouveau): self
    {
        $this->nouveau = $nouveau;

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

    /**
     * @return Collection|Mediasproducts[]
     */
    public function getMediasproducts(): Collection
    {
        return $this->mediasproducts;
    }

    public function addMediasproduct(Mediasproducts $mediasproduct): self
    {
        if (!$this->mediasproducts->contains($mediasproduct)) {
            $this->mediasproducts[] = $mediasproduct;
            $mediasproduct->setProducts($this);
        }

        return $this;
    }

    public function removeMediasproduct(Mediasproducts $mediasproduct): self
    {
        if ($this->mediasproducts->contains($mediasproduct)) {
            $this->mediasproducts->removeElement($mediasproduct);
            // set the owning side to null (unless already changed)
            if ($mediasproduct->getProducts() === $this) {
                $mediasproduct->setProducts(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Metakeywords[]
     */
    public function getMetakeywords(): Collection
    {
        return $this->metakeywords;
    }

    public function addMetakeyword(Metakeywords $metakeyword): self
    {
        if (!$this->metakeywords->contains($metakeyword)) {
            $this->metakeywords[] = $metakeyword;
        }

        return $this;
    }

    public function removeMetakeyword(Metakeywords $metakeyword): self
    {
        if ($this->metakeywords->contains($metakeyword)) {
            $this->metakeywords->removeElement($metakeyword);
        }

        return $this;
    }

    public function getVues(): ?int
    {
        return $this->vues;
    }

    public function setVues(int $vues): self
    {
        $this->vues = $vues;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getDelaislivraison(): ?string
    {
        return $this->delaislivraison;
    }

    public function setDelaislivraison(?string $delaislivraison): self
    {
        $this->delaislivraison = $delaislivraison;

        return $this;
    }

    public function getGarantie(): ?string
    {
        return $this->garantie;
    }

    public function setGarantie(?string $garantie): self
    {
        $this->garantie = $garantie;

        return $this;
    }

    public function getVideourl(): ?string
    {
        return $this->videourl;
    }

    public function setVideourl(?string $videourl): self
    {
        $this->videourl = $videourl;

        return $this;
    }

    public function getPayementAt(): ?\DateTimeInterface
    {
        return $this->payementAt;
    }

    public function setPayementAt(?\DateTimeInterface $payementAt): self
    {
        $this->payementAt = $payementAt;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @return Collection|Commentaireproducts[]
     */
    public function getCommentaireproducts(): Collection
    {
        return $this->commentaireproducts;
    }

    public function addCommentaireproduct(Commentaireproducts $commentaireproduct): self
    {
        if (!$this->commentaireproducts->contains($commentaireproduct)) {
            $this->commentaireproducts[] = $commentaireproduct;
            $commentaireproduct->setProducts($this);
        }

        return $this;
    }

    public function removeCommentaireproduct(Commentaireproducts $commentaireproduct): self
    {
        if ($this->commentaireproducts->contains($commentaireproduct)) {
            $this->commentaireproducts->removeElement($commentaireproduct);
            // set the owning side to null (unless already changed)
            if ($commentaireproduct->getProducts() === $this) {
                $commentaireproduct->setProducts(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Approvisionnements[]
     */
    public function getApprovisionnements(): Collection
    {
        return $this->approvisionnements;
    }

    public function addApprovisionnement(Approvisionnements $approvisionnement): self
    {
        if (!$this->approvisionnements->contains($approvisionnement)) {
            $this->approvisionnements[] = $approvisionnement;
            $approvisionnement->setProducts($this);
        }

        return $this;
    }

    public function removeApprovisionnement(Approvisionnements $approvisionnement): self
    {
        if ($this->approvisionnements->contains($approvisionnement)) {
            $this->approvisionnements->removeElement($approvisionnement);
            // set the owning side to null (unless already changed)
            if ($approvisionnement->getProducts() === $this) {
                $approvisionnement->setProducts(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Wishlists[]
     */
    public function getWishlists(): Collection
    {
        return $this->wishlists;
    }

    public function addWishlist(Wishlists $wishlist): self
    {
        if (!$this->wishlists->contains($wishlist)) {
            $this->wishlists[] = $wishlist;
            $wishlist->setProducts($this);
        }

        return $this;
    }

    public function removeWishlist(Wishlists $wishlist): self
    {
        if ($this->wishlists->contains($wishlist)) {
            $this->wishlists->removeElement($wishlist);
            // set the owning side to null (unless already changed)
            if ($wishlist->getProducts() === $this) {
                $wishlist->setProducts(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getAssociation(): Collection
    {
        return $this->association;
    }

    public function addAssociation(self $association): self
    {
        if (!$this->association->contains($association)) {
            $this->association[] = $association;
        }

        return $this;
    }

    public function removeAssociation(self $association): self
    {
        if ($this->association->contains($association)) {
            $this->association->removeElement($association);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getProductsassociated(): Collection
    {
        return $this->productsassociated;
    }

    public function addProductsassociated(self $productsassociated): self
    {
        if (!$this->productsassociated->contains($productsassociated)) {
            $this->productsassociated[] = $productsassociated;
            $productsassociated->addAssociation($this);
        }

        return $this;
    }

    public function removeProductsassociated(self $productsassociated): self
    {
        if ($this->productsassociated->contains($productsassociated)) {
            $this->productsassociated->removeElement($productsassociated);
            $productsassociated->removeAssociation($this);
        }

        return $this;
    }

    /**
     * @return Collection|Categories[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Categories $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }
}
