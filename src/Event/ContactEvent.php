<?php

namespace App\Event;

use App\Entity\Contacts;
use Symfony\Contracts\EventDispatcher\Event;

class ContactEvent extends Event
{
    const TEMPLATE_CONTACT = "emails/contact.html.twig";
    /**
     * @var Contacts
     */
    private $contact;

    /**
     * ConatctEvent constructor.
     * @param Contacts $contact
     */
    public function __construct(Contacts $contact)
    {
        $this->contact = $contact;
    }

    public function getContact(): Contacts {
        return $this->contact;
    }
}