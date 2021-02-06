<?php

namespace App\Controller\Admin;

use App\Entity\Logs;
use App\Repository\LogsRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/logs", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminLogsController extends AbstractController
{

    /**
     * @var FlashyNotifier
     */
    private $flashy;

    public function __construct(FlashyNotifier $flashy)
    {
        $this->flashy = $flashy;
    }

    /**
     * @Route("/", name="admin.logs.index", methods={"GET"})
     * @param LogsRepository $repository
     * @return Response
     */
    public function index(LogsRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('admin/logs/index.html.twig', [
            'title' => 'Liste des activités',
            'logs' => $repository->findBy([], ['id' => 'DESC']),
            'current_page' => 'logs',
            'current_global' => 'parametres',
        ]);
    }

    /**
     * @Route("/logs-clear", name="admin.logs.clear", methods={"GET"})
     * @param LogsRepository $repository
     * @return Response
     */
    public function clearLogs(LogsRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $em = $this->getDoctrine();
        $connection = $em->getConnection();
        $platform   = $connection->getDatabasePlatform();
        $connection->executeUpdate($platform->getTruncateTableSQL('logs', true));
        $this->flashy->success("Activité vidées avec succès.");
        return $this->redirectToRoute('admin.logs.index');
    }

    /**
     * @Route("/{id}", name="admin.logs.delete", methods={"DELETE"})
     * @param Request $request
     * @param Logs $logs
     * @return Response
     */
    public function delete(Request $request, Logs $logs): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete'.$logs->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($logs);
            $entityManager->flush();
            $this->flashy->success("Activité supprimée avec succès.");
        }

        return $this->redirectToRoute('admin.logs.index');
    }
}
