<?php

namespace App\Entity\Articles;


use Symfony\Component\Validator\Constraints as Assert;

class DTSearch
{
    /**
     * @var string|null
     * @Assert\NotBlank()
     */
    private $search;

    /**
     * @Assert\NotBlank()
     */
    private $dtcategories;

    public function __construct()
    {

    }

    /**
     * @return string|null
     */
    public function getSearch(): ?string
    {
        return $this->search;
    }

    /**
     * @param string|null $search
     * @return DTSearch
     */
    public function setSearch(string $search): DTSearch
    {
        $this->search = $search;
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
}
