<?php

namespace App\Entity\Articles;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Articles\DTArticlesRepository")
 * @UniqueEntity("numero_article")
 */
class DTArticles
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $numero_article;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $contenu_article;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"numero_article"})
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Articles\DTCategories", inversedBy="dTArticles")
     */
    private $dtcategories;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Articles\DTParties", inversedBy="dTArticles")
     */
    private $dtparties;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Articles\DTTitres", inversedBy="dTArticles")
     */
    private $dttitres;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Articles\DTChapitres", inversedBy="dTArticles")
     */
    private $dtchapitres;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Articles\DTSections", inversedBy="dTArticles")
     */
    private $dtsections;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $online;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $keywords;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroArticle(): ?string
    {
        return $this->numero_article;
    }

    public function setNumeroArticle(string $numero_article): self
    {
        $this->numero_article = $numero_article;

        return $this;
    }

    public function getContenuArticle(): ?string
    {
        return $this->contenu_article;
    }

    public function setContenuArticle(string $contenu_article): self
    {
        $this->contenu_article = $contenu_article;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

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

    public function getDtcategories(): ?DTCategories
    {
        return $this->dtcategories;
    }

    public function setDtcategories(?DTCategories $dtcategories): self
    {
        $this->dtcategories = $dtcategories;

        return $this;
    }

    public function getDtparties(): ?DTParties
    {
        return $this->dtparties;
    }

    public function setDtparties(?DTParties $dtparties): self
    {
        $this->dtparties = $dtparties;

        return $this;
    }

    public function getDttitres(): ?DTTitres
    {
        return $this->dttitres;
    }

    public function setDttitres(?DTTitres $dttitres): self
    {
        $this->dttitres = $dttitres;

        return $this;
    }

    public function getDtchapitres(): ?DTChapitres
    {
        return $this->dtchapitres;
    }

    public function setDtchapitres(?DTChapitres $dtchapitres): self
    {
        $this->dtchapitres = $dtchapitres;

        return $this;
    }

    public function getDtsections(): ?DTSections
    {
        return $this->dtsections;
    }

    public function setDtsections(?DTSections $dtsections): self
    {
        $this->dtsections = $dtsections;

        return $this;
    }

    public function __toString()
    {
        return $this->numero_article.' | '.$this->getDtchapitres();
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

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }
}
