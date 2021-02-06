<?php

namespace App\Services;

use App\Entity\Shop\Products;
use Doctrine\ORM\EntityManagerInterface;

class ViewsServices
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * LogsServices constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param object $object
     * @return bool
     */
    public function setViews(object $object): bool {
        if ($object){
            $views = (int) $object->getView();
            $object->setView($views + 1);
            $this->manager->flush();
            return true;
        }
        return true;
    }
}