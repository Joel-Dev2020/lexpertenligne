<?php

namespace App\Entity\Articles;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Articles\DTTitresRepository")
 * @UniqueEntity("name")
 */
class DTTitres
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Articles\DTArticles", mappedBy="dttitres")
     */
    private $dTArticles;

    public function __construct()
    {
        $this->dTArticles = new ArrayCollection();
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
        $this->name = ucfirst($name);

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|DTArticles[]
     */
    public function getDTArticles(): Collection
    {
        return $this->dTArticles;
    }

    public function addDTArticle(DTArticles $dTArticle): self
    {
        if (!$this->dTArticles->contains($dTArticle)) {
            $this->dTArticles[] = $dTArticle;
            $dTArticle->setDttitres($this);
        }

        return $this;
    }

    public function removeDTArticle(DTArticles $dTArticle): self
    {
        if ($this->dTArticles->contains($dTArticle)) {
            $this->dTArticles->removeElement($dTArticle);
            // set the owning side to null (unless already changed)
            if ($dTArticle->getDttitres() === $this) {
                $dTArticle->setDttitres(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
