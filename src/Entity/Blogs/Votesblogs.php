<?php

namespace App\Entity\Blogs;

use App\Entity\User;
use App\Repository\Blogs\VotesblogsRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=VotesblogsRepository::class)
 */
class Votesblogs
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Blogs::class, inversedBy="votesblogs")
     */
    private $blogs;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="votesblogs")
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $vote;

    /**
     * @var \DateTime $createdAt
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getVote(): ?bool
    {
        return $this->vote;
    }

    public function setVote(bool $vote): self
    {
        $this->vote = $vote;

        return $this;
    }
}
