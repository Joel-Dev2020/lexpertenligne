<?php

namespace App\Entity\Dossiers;

use App\Repository\Dossiers\CategoriesdossiersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CategoriesdossiersRepository::class)
 * @UniqueEntity("name", message="Cette catégorie existe déjà, veuillez réessayer")
 */
class Categoriesdossiers
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=190)
     * @Assert\NotBlank(message="Veuillez saisir un nom pour la catégorie")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=225)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Dossiers::class, mappedBy="categories")
     */
    private $dossiers;

    /**
     * @ORM\Column(type="integer")
     */
    private $ordre;

    public function __construct()
    {
        $this->dossiers = new ArrayCollection();
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
            $dossier->setCategories($this);
        }

        return $this;
    }

    public function removeDossier(Dossiers $dossier): self
    {
        if ($this->dossiers->contains($dossier)) {
            $this->dossiers->removeElement($dossier);
            // set the owning side to null (unless already changed)
            if ($dossier->getCategories() === $this) {
                $dossier->setCategories(null);
            }
        }

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }
}
