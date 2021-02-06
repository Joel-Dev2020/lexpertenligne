<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Blogs\Blogs;
use App\Entity\Blogs\Commentairesblogs;
use App\Entity\Blogs\Votesblogs;
use App\Entity\Dossiers\Commentairesdossiers;
use App\Entity\Dossiers\Dossiers;
use App\Entity\Formations\Commentairesformations;
use App\Entity\Formations\Formations;
use App\Entity\Pages\Pages;
use App\Entity\Shop\Commentaireproducts;
use App\Entity\Shop\Wishlists;
use App\Entity\Shop\Approvisionnements;
use App\Entity\Shop\Products;
use App\Entity\Shop\Adresses;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Shop\Commandes;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 * * @ApiResource(
 *     normalizationContext={"groups"={"user:read"}},
 *     denormalizationContext={"groups"={"blogs:write"}},
 *     collectionOperations={
 *          "get"={},
 *     },
 *     itemOperations={
 *          "get"={},
 *          "put"={},
 *          "delete"={},
 *     }
 * )
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_8D93D649E7927C74", columns={"email"})})
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Entity
 * @Vich\Uploadable
 * @UniqueEntity("email", message="Cet utilisateur existe déjà, veuillez réessayer")
 */
class User implements UserInterface
{
    const ROLES = [
        'ROLE_SUPER_ADMIN' => 'Super administrateur',
        'ROLE_ADMIN' => 'Administrateur',
        'ROLE_USER' => 'Utilisateur'
    ];

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"comments:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Veuillez saisir une adresse email")
     * @Assert\Email(message="Veuillez saisir un email valide")
     * @Groups({"comments:read", "comments:write"})
     */
    private $email;

    /**
     * @ORM\Column(type="array")
     * @Groups({"comments:read", "comments:write"})
     */
    private $roles;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Veuillez saisir un mot de passe")
     * @Assert\Length(min="6", minMessage="Votre mot de passe doit comporter au minimum 6 cataères")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Veuillez saisir un nom d'utilisateur")
     * @Groups({"comments:read", "comments:write"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"username"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Veuillez saisir votre nom de famille")
     * @Assert\Length(
     *     min="3", minMessage="Le nom doit comporter au minimum 3 cataères",
     *     max="15", maxMessage="Le nom doit comporter au plus 15 cataères",
     * )
     * @Groups({"comments:read", "comments:write"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Veuillez saisir votre prenom")
     * @Assert\Length(
     *     min="3", minMessage="Le prenom doit comporter au minimum 3 cataères",
     *     max="100", maxMessage="Le prenom doit comporter au plus 100 cataères",
     * )
     * @Groups({"comments:read", "comments:write"})
     */
    private $prenoms;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\NotBlank(message="Veuillez saisir un numéro de téléphone")
     * @Groups({"comments:read", "comments:write"})
     */
    private $contacts;

    /**
     * @var string|null
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     * @Groups({"comments:read", "comments:write"})
     */
    private $address;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="photos", fileNameProperty="filename")
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/png"},
     *     maxHeight=215,
     *     maxWidth=215,
     * )
     * @Groups({"comments:read", "comments:write"})
     */
    private $imageFile;

    /**
     * @var string|null
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=true)
     * @Groups({"comments:read", "comments:write"})
     */
    private $filename;

    /**
     * @ORM\Column(type="boolean", options={"default": 0})
     * @Groups({"comments:read", "comments:write"})
     */
    private $enabled;

    /**
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $terms;

    /**
     * @Groups({"comments:read", "comments:write"})
     */
    private $fullname;

    /**
     * @var \DateTime $createdAt
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
     * @Groups({"comments:read", "comments:write"})
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastActivity;

    /**
     * @ORM\OneToMany(targetEntity=Commandes::class, mappedBy="user")
     */
    private $commandes;

    /**
     * @ORM\OneToMany(targetEntity=Products::class, mappedBy="user")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity=Commentaireproducts::class, mappedBy="user")
     * @Groups({"user:read", "user:write"})
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity=Approvisionnements::class, mappedBy="user")
     */
    private $approvisionnements;

    /**
     * @ORM\OneToMany(targetEntity=Wishlists::class, mappedBy="user")
     */
    private $wishlists;

    /**
     * @ORM\OneToMany(targetEntity=Notifications::class, mappedBy="user")
     */
    private $notifications;

    /**
     * @ORM\OneToMany(targetEntity=Adresses::class, mappedBy="user")
     */
    private $adresses;

    /**
     * @ORM\OneToMany(targetEntity=Pages::class, mappedBy="user")
     */
    private $pages;

    /**
     * @ORM\OneToMany(targetEntity=Blogs::class, mappedBy="user")
     */
    private $blogs;

    /**
     * @ORM\OneToMany(targetEntity=Dossiers::class, mappedBy="user")
     */
    private $dossiers;

    /**
     * @ORM\OneToMany(targetEntity=Formations::class, mappedBy="user")
     */
    private $formations;

    /**
     * @ORM\OneToMany(targetEntity=Commentairesblogs::class, mappedBy="user")
     */
    private $commentairesblogs;

    /**
     * @ORM\OneToMany(targetEntity=Commentairesformations::class, mappedBy="user")
     */
    private $commentairesformations;

    /**
     * @ORM\OneToMany(targetEntity=Commentairesdossiers::class, mappedBy="user")
     */
    private $commentairesdossiers;

    /**
     * @ORM\OneToMany(targetEntity=Votesblogs::class, mappedBy="user")
     */
    private $votesblogs;

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
        $this->pages = new ArrayCollection();
        $this->blogs= new ArrayCollection();
        $this->dossiers= new ArrayCollection();
        $this->formations= new ArrayCollection();
        $this->commentairesblogs= new ArrayCollection();
        $this->commentairesformations= new ArrayCollection();
        $this->commentairesdossiers= new ArrayCollection();
        $this->votesblogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->nom . ' ' . $this->prenoms;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getContacts(): ?string
    {
        return $this->contacts;
    }

    public function setContacts(string $contacts): self
    {
        $this->contacts = $contacts;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

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
     * @return User
     * @throws \Exception
     */
    public function setImageFile(?File $imageFile): User
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

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getTerms(): ?bool
    {
        return $this->terms;
    }

    public function setTerms(bool $terms): self
    {
        $this->terms = $terms;

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

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenoms(): ?string
    {
        return $this->prenoms;
    }

    public function setPrenoms(?string $prenoms): self
    {
        $this->prenoms = $prenoms;

        return $this;
    }


    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getLastActivity(): ?\DateTimeInterface
    {
        return $this->lastActivity;
    }

    public function setLastActivity(?\DateTimeInterface $lastActivity): self
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

    /**
     * Is online (if the last activity was within the last 5 minutes)
     *
     * @return boolean
     */
    public function isOnline()
    {
        $now = new \DateTime();
        $now->modify('-5 minutes');
        return $this->getLastActivity() > $now;
    }

    /**
     * @return Collection|Commandes[]
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commandes $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setUser($this);
        }

        return $this;
    }

    public function removeCommande(Commandes $commande): self
    {
        if ($this->commandes->contains($commande)) {
            $this->commandes->removeElement($commande);
            // set the owning side to null (unless already changed)
            if ($commande->getUser() === $this) {
                $commande->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Products[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Products $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setUser($this);
        }

        return $this;
    }

    public function removeProduct(Products $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getUser() === $this) {
                $product->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentaireproducts[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaireproducts $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaireproducts $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
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
            $approvisionnement->setUser($this);
        }

        return $this;
    }

    public function removeApprovisionnement(Approvisionnements $approvisionnement): self
    {
        if ($this->approvisionnements->contains($approvisionnement)) {
            $this->approvisionnements->removeElement($approvisionnement);
            // set the owning side to null (unless already changed)
            if ($approvisionnement->getUser() === $this) {
                $approvisionnement->setUser(null);
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
            $wishlist->setUser($this);
        }

        return $this;
    }

    public function removeWishlist(Wishlists $wishlist): self
    {
        if ($this->wishlists->contains($wishlist)) {
            $this->wishlists->removeElement($wishlist);
            // set the owning side to null (unless already changed)
            if ($wishlist->getUser() === $this) {
                $wishlist->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Notifications[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notifications $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notifications $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Adresses[]
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addNAdresse(Adresses $adresse): self
    {
        if (!$this->adresses->contains($adresse)) {
            $this->adresses[] = $adresse;
            $adresse->setUser($this);
        }

        return $this;
    }

    public function removeAdresse(Adresses $adresse): self
    {
        if ($this->adresses->contains($adresse)) {
            $this->adresses->removeElement($adresse);
            // set the owning side to null (unless already changed)
            if ($adresse->getUser() === $this) {
                $adresse->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Pages[]
     */
    public function getPages(): Collection
    {
        return $this->pages;
    }

    public function addPage(Pages $page): self
    {
        if (!$this->pages->contains($page)) {
            $this->pages[] = $page;
            $page->setUser($this);
        }

        return $this;
    }

    public function removePage(Pages $page): self
    {
        if ($this->pages->contains($page)) {
            $this->pages->removeElement($page);
            // set the owning side to null (unless already changed)
            if ($page->getUser() === $this) {
                $page->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Blogs[]
     */
    public function getBlog(): Collection
    {
        return $this->blogs;
    }

    public function addBlog(Blogs $blog): self
    {
        if (!$this->blogs->contains($blog)) {
            $this->blogs[] = $blog;
            $blog->setUser($this);
        }

        return $this;
    }

    public function removeBlog(Blogs $blog): self
    {
        if ($this->blogs->contains($blog)) {
            $this->blogs->removeElement($blog);
            // set the owning side to null (unless already changed)
            if ($blog->getUser() === $this) {
                $blog->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Dossiers[]
     */
    public function getDossier(): Collection
    {
        return $this->dossiers;
    }

    public function addDossier(Dossiers $dossier): self
    {
        if (!$this->dossiers->contains($dossier)) {
            $this->dossiers[] = $dossier;
            $dossier->setUser($this);
        }

        return $this;
    }

    public function removeDossier(Dossiers $dossier): self
    {
        if ($this->dossiers->contains($dossier)) {
            $this->dossiers->removeElement($dossier);
            // set the owning side to null (unless already changed)
            if ($dossier->getUser() === $this) {
                $dossier->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Formations[]
     */
    public function getFormation(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formations $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations[] = $formation;
            $formation->setUser($this);
        }

        return $this;
    }

    public function removeFormation(Formations $formation): self
    {
        if ($this->formations->contains($formation)) {
            $this->formations->removeElement($formation);
            // set the owning side to null (unless already changed)
            if ($formation->getUser() === $this) {
                $formation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentairesblogs[]
     */
    public function getCommentairesblog(): Collection
    {
        return $this->commentairesblogs;
    }

    public function addCommentairesblog(Commentairesblogs $commentairesblog): self
    {
        if (!$this->commentairesblogs->contains($commentairesblog)) {
            $this->commentairesblogs[] = $commentairesblog;
            $commentairesblog->setUser($this);
        }

        return $this;
    }

    public function removeCommentairesblog(Commentairesblogs $commentairesblog): self
    {
        if ($this->commentairesblogs->contains($commentairesblog)) {
            $this->commentairesblogs->removeElement($commentairesblog);
            // set the owning side to null (unless already changed)
            if ($commentairesblog->getUser() === $this) {
                $commentairesblog->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentairesformations[]
     */
    public function getCommentairesformation(): Collection
    {
        return $this->commentairesformations;
    }

    public function addCommentairesformation(Commentairesformations $commentairesformation): self
    {
        if (!$this->commentairesformations->contains($commentairesformation)) {
            $this->commentairesformations[] = $commentairesformation;
            $commentairesformation->setUser($this);
        }

        return $this;
    }

    public function removeCommentairesformation(Commentairesformations $commentairesformation): self
    {
        if ($this->commentairesformations->contains($commentairesformation)) {
            $this->commentairesformations->removeElement($commentairesformation);
            // set the owning side to null (unless already changed)
            if ($commentairesformation->getUser() === $this) {
                $commentairesformation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentairesdossiers[]
     */
    public function getCommentairesdossier(): Collection
    {
        return $this->commentairesdossiers;
    }

    public function addCommentairesdossier(Commentairesdossiers $commentairesdossier): self
    {
        if (!$this->commentairesdossiers->contains($commentairesdossier)) {
            $this->commentairesdossiers[] = $commentairesdossier;
            $commentairesdossier->setUser($this);
        }

        return $this;
    }

    public function removeCommentairesdossier(Commentairesdossiers $commentairesdossier): self
    {
        if ($this->commentairesdossiers->contains($commentairesdossier)) {
            $this->commentairesdossiers->removeElement($commentairesdossier);
            // set the owning side to null (unless already changed)
            if ($commentairesdossier->getUser() === $this) {
                $commentairesdossier->setUser(null);
            }
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
            $votesblog->setUser($this);
        }

        return $this;
    }

    public function removeVotesblog(Votesblogs $votesblog): self
    {
        if ($this->votesblogs->contains($votesblog)) {
            $this->votesblogs->removeElement($votesblog);
            // set the owning side to null (unless already changed)
            if ($votesblog->getUser() === $this) {
                $votesblog->setUser(null);
            }
        }

        return $this;
    }
}
