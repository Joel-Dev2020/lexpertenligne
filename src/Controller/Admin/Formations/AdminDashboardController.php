<?php

namespace App\Controller\Admin\Formations;

use App\Repository\Formations\CommentairesformationsRepository;
use App\Repository\Formations\FormationsRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/formations/dashboard", schemes={"https"})
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
     * @Route("/", name="admin.formations.dashboard", methods={"GET"})
     * @param FormationsRepository $formationsRepository
     * @param CommentairesformationsRepository $commentairesformationsRepository
     * @return Response
     */
    public function index(FormationsRepository $formationsRepository, CommentairesformationsRepository $commentairesformationsRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $lastBlogs = $formationsRepository->findBy([], ['id' => 'DESC'], 4);
        $lastComments = $commentairesformationsRepository->findBy([], ['id' => 'DESC'], 2);
        return $this->render('admin/formations/dashboard.html.twig', [
            'title' => 'Formations dashboard',
            'lastFormations' => $lastBlogs,
            'lastComments' => $lastComments,
            'current_page' => 'formationsdashboard',
            'current_global' => 'formations'
        ]);
    }
}
