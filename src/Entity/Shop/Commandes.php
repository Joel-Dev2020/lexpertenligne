<?php

namespace App\Entity\Shop;

use App\Entity\Notifications;
use App\Entity\Status;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Shop\CommandesRepository")
 */
class Commandes
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $reference;

    /**
     * @var \DateTime $createdAt
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var \DateTime $date
     * @ORM\Column(type="date")
     *
     * @Gedmo\Timestampable(on="create")
     */
    private $date;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $totalht;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $totaltva;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $totalttc;

    /**
     * @ORM\Column(type="boolean")
     */
    private $valider;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="commandes")
     */
    private $user;

    /**
     * @ORM\Column(type="array")
     */
    private $products = [];

    /**
     * @ORM\Column(type="array")
     */
    private $adresses = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $modelivraison;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pointrelais;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $chiffreenlettre;

    /**
     * @ORM\Column(type="boolean")
     */
    private $notification;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $modepaiment;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="commandes")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $motifs;

    /**
     * @ORM\OneToMany(targetEntity=Notifications::class, mappedBy="commandes")
     */
    private $notifications;

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?int
    {
        return $this->reference;
    }

    public function setReference(int $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function getTotalht(): ?int
    {
        return $this->totalht;
    }

    public function setTotalht(int $totalht): self
    {
        $this->totalht = $totalht;

        return $this;
    }

    public function getTotaltva(): ?int
    {
        return $this->totaltva;
    }

    public function setTotaltva(int $totaltva): self
    {
        $this->totaltva = $totaltva;

        return $this;
    }

    public function getTotalttc(): ?int
    {
        return $this->totalttc;
    }

    public function setTotalttc(?int $totalttc): self
    {
        $this->totalttc = $totalttc;

        return $this;
    }

    public function getValider(): ?bool
    {
        return $this->valider;
    }

    public function setValider(bool $valider): self
    {
        $this->valider = $valider;

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

    public function getProducts(): ?array
    {
        return $this->products;
    }

    public function setProducts(array $products): self
    {
        $this->products = $products;

        return $this;
    }

    public function getAdresses(): ?array
    {
        return $this->adresses;
    }

    public function setAdresses(array $adresses): self
    {
        $this->adresses = $adresses;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getModelivraison()
    {
        return $this->modelivraison;
    }

    /**
     * @param mixed $modelivraison
     * @return Commandes
     */
    public function setModelivraison($modelivraison)
    {
        $this->modelivraison = $modelivraison;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPointrelais()
    {
        return $this->pointrelais;
    }

    /**
     * @param mixed $pointrelais
     * @return Commandes
     */
    public function setPointrelais($pointrelais)
    {
        $this->pointrelais = $pointrelais;
        return $this;
    }

    public function getChiffreenlettre(): ?string
    {
        return $this->chiffreenlettre;
    }

    public function setChiffreenlettre(string $chiffreenlettre): self
    {
        $this->chiffreenlettre = $chiffreenlettre;

        return $this;
    }

    public function getNotification(): ?bool
    {
        return $this->notification;
    }

    public function setNotification(bool $notification): self
    {
        $this->notification = $notification;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getModepaiment()
    {
        return $this->modepaiment;
    }

    /**
     * @param mixed $modepaiment
     * @return Commandes
     */
    public function setModepaiment($modepaiment)
    {
        $this->modepaiment = $modepaiment;
        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getMotifs(): ?string
    {
        return $this->motifs;
    }

    public function setMotifs(?string $motifs): self
    {
        $this->motifs = $motifs;

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
            $notification->setCommandes($this);
        }

        return $this;
    }

    public function removeNotification(Notifications $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getCommandes() === $this) {
                $notification->setCommandes(null);
            }
        }

        return $this;
    }
}
