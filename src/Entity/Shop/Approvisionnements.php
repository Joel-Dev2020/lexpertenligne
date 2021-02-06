<?php

namespace App\Entity\Shop;

use App\Entity\User;
use App\Repository\Shop\ApprovisionnementsRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ApprovisionnementsRepository::class)
 */
class Approvisionnements
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
    private $oldqty;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Veuillez saisir la nouvelle quantité à ajouter")
     */
    private $newqty;

    /**
     * @var \DateTime $createdAt
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Products::class, inversedBy="approvisionnements")
     */
    private $products;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="approvisionnements")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $remarque;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOldqty(): ?int
    {
        return $this->oldqty;
    }

    public function setOldqty(int $oldqty): self
    {
        $this->oldqty = $oldqty;

        return $this;
    }

    public function getNewqty(): ?int
    {
        return $this->newqty;
    }

    public function setNewqty(int $newqty): self
    {
        $this->newqty = $newqty;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getProducts(): ?Products
    {
        return $this->products;
    }

    public function setProducts(?Products $products): self
    {
        $this->products = $products;

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

    public function getRemarque(): ?string
    {
        return $this->remarque;
    }

    public function setRemarque(?string $remarque): self
    {
        $this->remarque = $remarque;

        return $this;
    }
}
