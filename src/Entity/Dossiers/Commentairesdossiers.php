<?php

namespace App\Entity\Dossiers;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Commentairesdossiers
 *
 * @ORM\Entity(repositoryClass="App\Repository\dossiers\CommentairesdossiersRepository")
 * @ORM\Entity
 */
class Commentairesdossiers
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
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=false)
     */
    private $enabled;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=false)
     * @Assert\NotBlank(message="Veuillez saisir un commentaire svp!")
     */
    private $message;

    /**
     * @var \DateTime $createdAt
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Dossiers::class, inversedBy="commentaire")
     */
    private $dossiers;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commentairesdossiers")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
