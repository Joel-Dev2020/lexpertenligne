<?php

namespace App\Entity\Dictionnaires;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Dictionnaires\DictionnairesRepository")
 * @UniqueEntity("lexique")
 */
class Dictionnaires
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir un lexique svp!")
     */
    private $lexique;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Veuillez saisir une définition")
     */
    private $definition;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"lexique"})
     */
    private $slug;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLexique(): ?string
    {
        return $this->lexique;
    }

    public function setLexique(string $lexique): self
    {
        $this->lexique = $lexique;

        return $this;
    }

    public function getDefinition(): ?string
    {
        return $this->definition;
    }

    public function setDefinition(string $definition): self
    {
        $this->definition = $definition;

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
}
