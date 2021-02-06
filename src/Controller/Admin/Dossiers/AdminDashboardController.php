<?php

namespace App\Controller\Admin\Dossiers;

use App\Repository\Dossiers\CommentairesdossiersRepository;
use App\Repository\Dossiers\DossiersRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/dossiers/dashboard", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminDashboardController extends AbstractController
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
     * @Route("/", name="admin.dossiers.dashboard", methods={"GET"})
     * @param DossiersRepository $dossiersRepository
     * @param CommentairesdossiersRepository $commentairesdossiersRepository
     * @return Response
     */
    public function index(DossiersRepository $dossiersRepository, CommentairesdossiersRepository $commentairesdossiersRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $lastBlogs = $dossiersRepository->findBy([], ['id' => 'DESC'], 4);
        $lastComments = $commentairesdossiersRepository->findBy([], ['id' => 'DESC'], 2);
        return $this->render('admin/dossiers/dashboard.html.twig', [
            'title' => 'Dossiers dashboard',
            'lastDossiers' => $lastBlogs,
            'lastComments' => $lastComments,
            'current_page' => 'dossiersdashboard',
            'current_global' => 'dossiers'
        ]);
    }
}
