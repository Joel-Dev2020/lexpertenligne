<?php

namespace App\Event;

use App\Entity\Shop\Commandes;
use Symfony\Contracts\EventDispatcher\Event;

class CommandeEvent extends Event
{
    const TEMPLATE_COMMANDE = "emails/commande.html.twig";
    const TEMPLATE_CLIENT = "emails/commande_client.html.twig";
    /**
     * @var Commandes
     */
    private $commande;

    /**
     * CommandeEvent constructor.
     * @param Commandes $commande
     */
    public function __construct(Commandes $commande)
    {
        $this->commande = $commande;
    }

    public function getCommande(): Commandes {
        return $this->commande;
    }
}