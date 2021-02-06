<?php

namespace App\Controller\Admin\Shop;

use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/products/dashboard", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminDashboardController extends AbstractController
{

    /**
     * @var FlashyNotifier
     */
    private $flashy;

    public function __construct(
        FlashyNotifier $flashy
    )
    {
        $this->flashy = $flashy;
    }

    /**
     * @Route("/", name="admin.products.dashboard", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('admin/shops/dashboard.html.twig', [
            'title' => 'E-Commerce dashboard',
            'current_page' => 'productsdashboard',
            'current_global' => 'products'
        ]);
    }
}
