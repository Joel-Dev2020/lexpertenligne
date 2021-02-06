<?php

namespace App\Entity\Baremes;

use App\Repository\Baremes\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CategoriesRepository::class)
 * @ORM\Table(name="baremescategories")
 * @UniqueEntity("name", message="Cette catégorie existe déjà, veuillez réessayer")
 */
class Categories
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir un nom pour la catégorie")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $definition;

    /**
     * @ORM\OneToMany(targetEntity=Salaires::class, mappedBy="categories")
     */
    private $salaires;

    public function __construct()
    {
        $this->salaires = new ArrayCollection();
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
     * @return mixed
     */
    public function getDefinition(): ?string
    {
        return $this->definition;
    }

    /**
     * @param mixed $definition
     * @return Categories
     */
    public function setDefinition(?string $definition)
    {
        $this->definition = $definition;
        return $this;
    }

    /**
     * @return Collection|Salaires[]
     */
    public function getSalaires(): Collection
    {
        return $this->salaires;
    }

    public function addSalaire(Salaires $salaire): self
    {
        if (!$this->salaires->contains($salaire)) {
            $this->salaires[] = $salaire;
            $salaire->setCategories($this);
        }

        return $this;
    }

    public function removeSalaire(Salaires $salaire): self
    {
        if ($this->salaires->contains($salaire)) {
            $this->salaires->removeElement($salaire);
            // set the owning side to null (unless already changed)
            if ($salaire->getCategories() === $this) {
                $salaire->setCategories(null);
            }
        }

        return $this;
    }
}
