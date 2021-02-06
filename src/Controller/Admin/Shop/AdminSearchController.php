<?php

namespace App\Controller\Admin\Shop;

use App\Data\SearchData;
use App\Form\Shop\AdminSearchFormType;
use App\Repository\Shop\ProductsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/products/search", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminSearchController extends AbstractController
{
    /**
     * @Route("/products", name="admin.search.products")
     * @param Request $request
     * @param ProductsRepository $productsRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, ProductsRepository $productsRepository, PaginatorInterface $paginator)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(AdminSearchFormType::class, $data);
        $form->handleRequest($request);
        $products = [];
        if($form->isSubmitted() && $form->isValid()){
            $products = $productsRepository->findSearch($data);
        }
        return $this->render('admin/shops/products/search.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
            'title' => 'Produit(s) trouvé(s)',
            'libelle_ajouter' => 'Nouveau produit',
            'current_page' => 'productsproduits',
            'current_global' => 'products'
        ]);
    }
}
