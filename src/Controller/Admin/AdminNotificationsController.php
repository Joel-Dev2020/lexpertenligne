<?php

namespace App\Controller\Admin;

use App\Entity\Notifications;
use App\Interfaces\NotificationsInterface;
use App\Managers\NotificationsManagers;
use App\Repository\NotificationsRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/notifications", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminNotificationsController extends AbstractController
{

    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var NotificationsRepository
     */
    private $notificationsRepository;
    /**
     * @var NotificationsManagers
     */
    private $notificationsManagers;

    /**
     * AdminNotificationsController constructor.
     * @param FlashyNotifier $flashy
     * @param NotificationsRepository $notificationsRepository
     * @param NotificationsManagers $notificationsManagers
     */
    public function __construct(
        FlashyNotifier $flashy,
        NotificationsRepository $notificationsRepository,
        NotificationsManagers $notificationsManagers
    )
    {
        $this->flashy = $flashy;
        $this->notificationsRepository = $notificationsRepository;
        $this->notificationsManagers = $notificationsManagers;
    }

    /**
     * @Route("/", name="admin.notifications.index")
     * @param NotificationsRepository $notificationsRepository
     * @return Response
     */
    public function index(NotificationsRepository $notificationsRepository)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('admin/notifications/index.html.twig', [
            'title' => 'Liste de notifications',
            'notifications' => $notificationsRepository->findBy([], ['id' => 'DESC']),
            'current_page' => 'notifications',
            'current_global' => 'notifications'
        ]);
    }

    /**
     * @Route("/{id}/show", name="admin.notifications.show")
     * @param Notifications $notification
     * @param NotificationsInterface $notifs
     * @return Response
     */
    public function show(Notifications $notification, NotificationsInterface $notifs)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $notifs->view($notification);
        $title = '';
        if ($notification->getContacts()){
            $title = $notification->getContacts()->getNomprenoms();
        }elseif ($notification->getAbonnes()){
            $title = $notification->getAbonnes()->getNomprenoms();
        }
        return $this->render('admin/notifications/show.html.twig', [
            'title' => $title,
            'notification' => $notification,
            'current_page' => 'notifications',
            'current_global' => 'notifications'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.notifications.delete", methods={"DELETE"})
     * @param Request $request
     * @param Notifications $notifications
     * @return Response
     */
    public function delete(Request $request, Notifications $notifications): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete'.$notifications->getId(), $request->request->get('_token'))) {
            $this->notificationsManagers->delete($notifications);
            $this->addFlash('success', 'Notification suprimés avec succès');
            $this->flashy->success("Suppression éffectuée.");
        }

        return $this->redirectToRoute('admin.notifications.index');
    }
}