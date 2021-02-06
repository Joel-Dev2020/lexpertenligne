<?php

namespace App\Controller\Admin;

use App\Repository\Dossiers\DossiersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminHomeController extends AbstractController
{

    /**
     * @var DossiersRepository
     */
    private $dossiersRepository;

    /**
     * HomeController constructor.
     * @param DossiersRepository $dossiersRepository
     */
    public function __construct(DossiersRepository $dossiersRepository)
    {
        $this->dossiersRepository = $dossiersRepository;
    }

    /**
     * @Route("/", name="admin.home")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $last_dossiers = $this->dossiersRepository->findBy([], ['id' => 'DESC'], 3);
        return $this->render('admin/index.html.twig', [
            'title' => 'Dashboard administration',
            'last_dossiers' => $last_dossiers,
            'current_page' => 'admin.home',
            'current_global' => 'home'
        ]);
    }
}