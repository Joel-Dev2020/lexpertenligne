<?php

namespace App\Entity\Blogs;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * Commentairesblogs
 * * @ApiResource(
 *     attributes={
 *      "order"={"createdAt": "DESC"}
 *     },
 *     paginationItemsPerPage=2,
 *     normalizationContext={"groups"={"comments:read"}},
 *     denormalizationContext={"groups"={"comments:write"}},
 * )
 * @ApiFilter(SearchFilter::class, properties={"blogs": "exact"})
 *
 * @ORM\Entity(repositoryClass="App\Repository\blogs\CommentairesblogsRepository")
 * @ORM\Entity
 */
class Commentairesblogs
{
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
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=false)
     * @Groups({"comments:read", "comments:write"})
     */
    private $enabled;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", length=0, nullable=false)
     * @Assert\NotBlank(message="Veuillez saisir un commentaire svp!")
     * @Assert\Length(
     *     min=5,
     *     minMessage="Veuillez saisir un message de plus de 5 catactères",
     *     max=10000,
     *     minMessage="Veuillez saisir un message de moins de 10000 catactères"
     *     )
     * @Groups({"comments:read", "comments:write"})
     */
    private $message;

    /**
     * @var \DateTime $createdAt
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
     * @Groups({"comments:read", "comments:write"})
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Blogs::class, inversedBy="commentaire")
     * @Groups({"comments:read", "comments:write"})
     */
    private $blogs;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commentairesblogs")
     * @Groups({"comments:read", "comments:write"})
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

    public function getBlogs(): ?Blogs
    {
        return $this->blogs;
    }

    public function setBlogs(?Blogs $blogs): self
    {
        $this->blogs = $blogs;

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
