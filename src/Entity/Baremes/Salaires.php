<?php

namespace App\Entity\Baremes;

use App\Repository\Baremes\SalairesRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=SalairesRepository::class)
 * @UniqueEntity("name", message="Ce type de salarier existe déjà, veuillez réessayer")
 */
class Salaires
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $salairehoraire;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $salairemensuel;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="salaires")
     */
    private $categories;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSalairehoraire(): ?float
    {
        return $this->salairehoraire;
    }

    public function setSalairehoraire(?float $salairehoraire): self
    {
        $this->salairehoraire = $salairehoraire;

        return $this;
    }

    public function getSalairemensuel(): ?float
    {
        return $this->salairemensuel;
    }

    public function setSalairemensuel(?float $salairemensuel): self
    {
        $this->salairemensuel = $salairemensuel;

        return $this;
    }

    public function getCategories(): ?Categories
    {
        return $this->categories;
    }

    public function setCategories(?Categories $categories): self
    {
        $this->categories = $categories;

        return $this;
    }
}
