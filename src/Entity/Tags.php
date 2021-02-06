<?php

namespace App\Entity;

use App\Entity\Blogs\Blogs;
use App\Entity\Dossiers\Dossiers;
use App\Entity\Formations\Formations;
use App\Entity\Pages\Pages;
use App\Entity\Shop\Products;
use App\Repository\TagsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=TagsRepository::class)
 * @UniqueEntity("name", message="Ce mot clé existe déjà, veuillez réessayer")
 */
class Tags
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Veuillez saisir un nom")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity=Pages::class, mappedBy="tags")
     */
    private $pages;

    /**
     * @ORM\ManyToMany(targetEntity=Dossiers::class, mappedBy="tags")
     */
    private $dossiers;

    /**
     * @ORM\ManyToMany(targetEntity=Blogs::class, mappedBy="tags")
     */
    private $blogs;

    /**
     * @ORM\ManyToMany(targetEntity=Formations::class, mappedBy="tags")
     */
    private $formations;

    public function __construct()
    {
        $this->pages = new ArrayCollection();
        $this->dossiers = new ArrayCollection();
        $this->blogs = new ArrayCollection();
        $this->formations = new ArrayCollection();
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
            $page->addTag($this);
        }

        return $this;
    }

    public function removePage(Pages $page): self
    {
        if ($this->pages->contains($page)) {
            $this->pages->removeElement($page);
            $page->removeTag($this);
        }

        return $this;
    }

    /**
     * @return Collection|Dossiers[]
     */
    public function getDossiers(): Collection
    {
        return $this->dossiers;
    }

    public function addDossier(Dossiers $dossier): self
    {
        if (!$this->dossiers->contains($dossier)) {
            $this->dossiers[] = $dossier;
            $dossier->addTag($this);
        }

        return $this;
    }

    public function removeDossier(Dossiers $dossier): self
    {
        if ($this->dossiers->contains($dossier)) {
            $this->dossiers->removeElement($dossier);
            $dossier->removeTag($this);
        }

        return $this;
    }

    /**
     * @return Collection|Blogs[]
     */
    public function getBlogs(): Collection
    {
        return $this->blogs;
    }

    public function addBlog(Blogs $blog): self
    {
        if (!$this->blogs->contains($blog)) {
            $this->blogs[] = $blog;
            $blog->addTag($this);
        }

        return $this;
    }

    public function removeBlog(Blogs $blog): self
    {
        if ($this->blogs->contains($blog)) {
            $this->blogs->removeElement($blog);
            $blog->removeTag($this);
        }

        return $this;
    }

    /**
     * @return Collection|Formations[]
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formations $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations[] = $formation;
            $formation->addTag($this);
        }

        return $this;
    }

    public function removeFormation(Formations $formation): self
    {
        if ($this->formations->contains($formation)) {
            $this->formations->removeElement($formation);
            $formation->removeTag($this);
        }

        return $this;
    }
}
