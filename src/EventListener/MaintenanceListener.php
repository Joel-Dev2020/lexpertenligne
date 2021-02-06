<?php


namespace App\EventListener;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Twig\Environment;

class MaintenanceListener
{
    private $lockPath;
    /**
     * @var Environment
     */
    private $twig;

    public function __construct($lockPath, Environment $twig)
    {
        $this->lockPath = $lockPath;
        $this->twig = $twig;
    }

    public function onKernelRequest(ResponseEvent $event){

        if (!$this->lockPath){
            return;
        }
        $page = $this->twig->render('pages/home/coming.html.twig', ['title' => 'Site en maintenance']);
        $event->setResponse(new Response($page, Response::HTTP_SERVICE_UNAVAILABLE));
        $event->stopPropagation();
    }
}