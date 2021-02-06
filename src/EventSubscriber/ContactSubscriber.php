<?php


namespace App\EventSubscriber;


use App\Entity\Parametres;
use App\Event\ContactEvent;
use App\Repository\ParametresRepository;
use App\Services\SwiftmailerServices;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContactSubscriber implements EventSubscriberInterface
{

    /**
     * @var SwiftmailerServices
     */
    private $mailService;
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var ParametresRepository
     */
    private $parametresRepository;

    /**
     * ContactSubscriber constructor.
     * @param SwiftmailerServices $mailService
     * @param ContainerInterface $container
     * @param ParametresRepository $parametresRepository
     */
    public function __construct(
        SwiftmailerServices $mailService,
        ContainerInterface $container,
        ParametresRepository $parametresRepository
    )
    {
        $this->mailService = $mailService;
        $this->container = $container;
        $this->parametresRepository = $parametresRepository;
    }

    public function onSendContact(ContactEvent $event) {
        $contact = $event->getContact();
        $title = 'Nouveau contact';
        /**
         * @var $expertenligne Parametres
         */
        $expertenligne = $this->parametresRepository->find($this->container->getParameter('company_id'));
        $parameters = [
            'title' => $title,
            'email' => $contact->getEmail(),
            'name' => $contact->getNomprenoms(),
            'phone' => $contact->getTelephone(),
            'message' => $contact->getMessage()
        ];

        $this->mailService->send(
            $title,
            [$expertenligne->getEmail()],
            [$contact->getEmail()],
            ContactEvent::TEMPLATE_CONTACT,
            $parameters
        );
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ContactEvent::class => [
                ['onSendContact', 1]
            ]
        ];
    }
}