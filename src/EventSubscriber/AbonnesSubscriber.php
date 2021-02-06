<?php


namespace App\EventSubscriber;


use App\Event\AbonnesEvent;
use App\Services\SwiftmailerServices;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AbonnesSubscriber implements EventSubscriberInterface
{

    /**
     * @var SwiftmailerServices
     */
    private $mailService;

    /**
     * AbonnesSubscriber constructor.
     * @param SwiftmailerServices $mailService
     */
    public function __construct(SwiftmailerServices $mailService)
    {
        $this->mailService = $mailService;
    }

    public function onSendContact(AbonnesEvent $event) {
        $abonne = $event->getAbonne();
        $parameters = [
            'title' => 'Nouvel abonné',
            'email' => $abonne->getEmail(),
        ];

        $this->mailService->send(
            'Nouvel abonné',
            ['dev@web-symphonie.com'],
            [$abonne->getEmail()],
            AbonnesEvent::TEMPLATE_CONTACT,
            $parameters
        );
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            AbonnesEvent::class => [
                ['onSendContact', 1]
            ]
        ];
    }
}