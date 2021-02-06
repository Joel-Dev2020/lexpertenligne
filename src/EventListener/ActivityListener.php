<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ActivityListener
{
    /**
     * @var Security
     */
    protected $context;

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * ActivityListener constructor.
     * @param EntityManagerInterface $em
     * @param TokenStorageInterface $context
     * @param RouterInterface $router
     */
    public function __construct(EntityManagerInterface $em, TokenStorageInterface $context, RouterInterface $router)
    {
        $this->em = $em;
        $this->context = $context;
        $this->router = $router;
    }

    public function onCoreController(ControllerEvent $event)
    {
        $token = $this->context->getToken();

        if(isset($token)){
            /**
             * @var $user User
             */
            $user = $token->getUser();

            $now = new \DateTime('now');
            $now->modify('-5 minutes');
            if (isset($user) && $user instanceof UserInterface && $user->getLastActivity() > $now){
                /*$url = $this->router->generate('security_lockscreen');
                return new RedirectResponse($url);*/
            }else{
                if(isset($user) && $user instanceof UserInterface) {
                    $user->setLastActivity(new \DateTime());
                    $this->em->persist($user);
                    $this->em->flush();
                }
            }
        }
    }
}