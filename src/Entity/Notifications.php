<?php

namespace App\Entity;

use App\Entity\Shop\Commandes;
use App\Repository\NotificationsRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=NotificationsRepository::class)
 */
class Notifications
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $action;

    /**
     * @var \DateTime $createdAt
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="notifications")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $icon;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $color;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reading;

    /**
     * @ORM\ManyToOne(targetEntity=Contacts::class, inversedBy="notifications")
     */
    private $contacts;

    /**
     * @ORM\ManyToOne(targetEntity=Abonnes::class, inversedBy="notifications")
     */
    private $abonnes;

    /**
     * @ORM\ManyToOne(targetEntity=Commandes::class, inversedBy="notifications")
     */
    private $commandes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getReading(): ?bool
    {
        return $this->reading;
    }

    public function setReading(bool $reading): self
    {
        $this->reading = $reading;

        return $this;
    }

    public function getContacts(): ?Contacts
    {
        return $this->contacts;
    }

    public function setContacts(?Contacts $contacts): self
    {
        $this->contacts = $contacts;

        return $this;
    }

    public function getAbonnes(): ?Abonnes
    {
        return $this->abonnes;
    }

    public function setAbonnes(?Abonnes $abonnes): self
    {
        $this->abonnes = $abonnes;

        return $this;
    }

    public function getCommandes(): ?Commandes
    {
        return $this->commandes;
    }

    public function setCommandes(?Commandes $commandes): self
    {
        $this->commandes = $commandes;

        return $this;
    }
}
