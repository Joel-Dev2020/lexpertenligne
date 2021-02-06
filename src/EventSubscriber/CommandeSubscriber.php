<?php

namespace App\EventSubscriber;

use App\Event\CommandeEvent;
use App\Services\SwiftmailerServices;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommandeSubscriber implements EventSubscriberInterface
{

    /**
     * @var SwiftmailerServices
     */
    private $mailService;

    /**
     * CommandeSubscriber constructor.
     * @param SwiftmailerServices $mailService
     */
    public function __construct(SwiftmailerServices $mailService)
    {
        $this->mailService = $mailService;
    }

    public function onSendCommande(CommandeEvent $event) {
        $commande = $event->getCommande();
        $title = 'Nouvelle commande';
        $parameters = [
            'title' => $title,
            'user' => $commande->getUser(),
            'commande' => $commande,
            'products' => $commande->getProducts(),
        ];

        $this->mailService->send(
            $title,
            ['dev@web-symphonie.com'],
            ['dev@web-symphonie.com'],
            CommandeEvent::TEMPLATE_COMMANDE,
            $parameters
        );

        $this->mailService->send(
            $title,
            ['dev@web-symphonie.com'],
            [$commande->getUser()->getEmail()],
            CommandeEvent::TEMPLATE_CLIENT,
            $parameters
        );

        //Send Aiwatch
        /*$this->mailService->mail(
            $title,
            'dev@web-symphonie.com',
            'dev@web-symphonie.com',
            CommandeEvent::TEMPLATE_COMMANDE,
            $parameters
        );*/

        //Send client
        /*$this->mailService->mail(
            $title,
            'dev@web-symphonie.com',
            $commande->getUser()->getEmail(),
            CommandeEvent::TEMPLATE_CLIENT,
            $parameters
        );*/
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            CommandeEvent::class => [
                ['onSendCommande', 1]
            ]
        ];
    }
}