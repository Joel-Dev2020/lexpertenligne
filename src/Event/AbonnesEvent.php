<?php

namespace App\Event;

use App\Entity\Abonnes;
use Symfony\Contracts\EventDispatcher\Event;

class AbonnesEvent extends Event
{
    const TEMPLATE_CONTACT = "emails/abonnee.html.twig";
    /**
     * @var Abonnes
     */
    private $abonne;

    /**
     * AbonnesEvent constructor.
     * @param Abonnes $abonne
     */
    public function __construct(Abonnes $abonne)
    {
        $this->abonne = $abonne;
    }

    public function getAbonne(): Abonnes {
        return $this->abonne;
    }
}