<?php

namespace App\Controller\Admin\Shop;

use App\Entity\Shop\Products;
use App\Form\Shop\ProductsType;
use App\Managers\Products\ProductsManagers;
use App\Repository\ParametresRepository;
use App\Repository\Shop\ProductsRepository;
use App\Services\LinksServices;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/products", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminProductsController extends AbstractController
{

    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var LinksServices
     */
    private $linksServices;
    /**
     * @var ProductsManagers
     */
    private $productsManagers;
    /**
     * @var PaginatorInterface
     */
    private $pagination;

    /**
     * AdminProductsController constructor.
     * @param FlashyNotifier $flashy
     * @param ProductsManagers $productsManagers
     * @param PaginatorInterface $pagination
     * @param LinksServices $linksServices
     */
    public function __construct(
        FlashyNotifier $flashy,
        ProductsManagers $productsManagers,
        PaginatorInterface $pagination,
        LinksServices $linksServices
    )
    {
        $this->flashy = $flashy;
        $this->linksServices = $linksServices;
        $this->productsManagers = $productsManagers;
        $this->pagination = $pagination;
    }

    /**
     * @Route("/", name="admin.products.index", methods={"GET"})
     * @param Request $request
     * @param ProductsRepository $productsRepository
     * @return Response
     */
    public function index(Request $request, ProductsRepository $productsRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $produit = new Products();
        $form = $this->createForm(ProductsType::class, $produit);
        $products = $this->pagination->paginate(
            $productsRepository->findBy([], ['id' => 'DESC']), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 10)/*limit per page*/
        );
        return $this->render('admin/shops/products/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
            'title' => 'Liste des produits',
            'libelle_ajouter' => 'Nouveau produit',
            'current_page' => 'products',
            'current_global' => 'products'
        ]);
    }

    /**
     * @Route("/list/suppression", name="admin.productslist.suppression", methods={"GET","POST"})
     * @param Request $request
     * @param ProductsRepository $productsRepository
     * @return Response
     */
    public function listsuppressionproducts(Request $request, ProductsRepository $productsRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $produit = new Products();
        $form = $this->createForm(ProductsType::class, $produit);
        return $this->render('admin/shops/products/suppression.html.twig', [
            'products' => $productsRepository->findBy([], ['id' => 'DESC']),
            'form' => $form->createView(),
            'title' => 'Liste des produits',
            'libelle_ajouter' => 'Nouveau produit',
            'current_page' => 'products',
            'current_global' => 'products'
        ]);
    }

    /**
     * @Route("/stock-minimum-atteint", name="admin.products.qteseuil", methods={"GET","POST"})
     * @param Request $request
     * @param ProductsRepository $productsRepository
     * @param ParametresRepository $parametresRepository
     * @param ContainerInterface $container
     * @return Response
     */
    public function productsQteseuil(
        Request $request,
        ProductsRepository $productsRepository,
        ParametresRepository $parametresRepository,
        ContainerInterface $container
    ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $qteSeuilProducts = $parametresRepository->find($container->getParameter('company_id'))->getSeuilproduct();
        $produit = new Products();
        $form = $this->createForm(ProductsType::class, $produit);
        return $this->render('admin/shops/products/index.html.twig', [
            'products' => $productsRepository->findProductsSeuilQte($qteSeuilProducts),
            'form' => $form->createView(),
            'title' => 'Liste des produits en rupture de stock',
            'libelle_ajouter' => 'Nouveau produit',
            'current_page' => 'products',
            'current_global' => 'products'
        ]);
    }

    /**
     * @Route("/new", name="admin.products.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $product = new Products();
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->productsManagers->register($product);
            $this->linksServices->setLink(
                'Voir le nouveau produit ajouté',
                'success',
                'shop.products.show',
                ['id' => $product->getId(), 'slug' => $product->getSlug()]
            );
            $this->flashy->success('Produit ajouté avec succès.');
            return $this->redirectToRoute('admin.products.index');
        }

        return $this->render('admin/shops/products/new.html.twig', [
            'products' => $product,
            'form' => $form->createView(),
            'title' => 'Nouveau produit',
            'libelle_liste' => 'Liste des produits',
            'current_page' => 'products',
            'current_global' => 'products'
        ]);
    }

    /**
     * @Route("/show/{id}", name="admin.products.show", methods={"GET"})
     * @param Products $product
     * @return Response
     */
    public function show(Products $product): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('admin/shops/products/show.html.twig', [
            'product' => $product,
            'title' => 'Détails produit',
            'libelle_liste' => 'Liste des produits',
            'libelle_ajouter' => 'Nouveau produit',
            'current_page' => 'products',
            'current_global' => 'products'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.products.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Products $product
     * @return Response
     */
    public function edit(Request $request, Products $product): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->productsManagers->edit($product);
            $this->linksServices->setLink(
                'Voir le produit modifié',
                'success',
                'shop.products.show',
                ['id' => $product->getId(), 'slug' => $product->getSlug()]
            );
            $this->flashy->success('Produit modifié avec succès.');
            return $this->redirectToRoute('admin.products.index');
        }

        return $this->render('admin/shops/products/edit.html.twig', [
            'products' => $product,
            'form' => $form->createView(),
            'title' => 'Editer produit',
            'libelle_ajouter' => 'Nouveau produit',
            'libelle_liste' => 'Liste des produits',
            'current_page' => 'products',
            'current_global' => 'products'
        ]);
    }

    /**
     * @Route("/delete-more", name="admin.products.delete_more", methods={"POST"})
     * @param Request $request
     * @param ProductsRepository $repository
     * @return Response
     */
    public function deleteMore(Request $request, ProductsRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $action = intval($request->request->get('action'));
        if ($action === 1 && $request->getMethod() === 'POST'){
            $datas = $request->request->get('checkproduct');
            $products = $repository->getProducts($datas);
            foreach ($products as $product){
                $this->productsManagers->delete($product);
            }
            $this->flashy->success('Les produits ont été supprimés avec succès.');
            $this->addFlash('success', 'Les produits ont été supprimés avec succès.');
            return $this->redirectToRoute('admin.productslist.suppression');
        }
        return $this->redirectToRoute('admin.productslist.suppression');
    }

    /**
     * @Route("/{id}", name="admin.products.delete", methods={"DELETE"})
     * @param Request $request
     * @param Products $product
     * @return Response
     */
    public function delete(Request $request, Products $product): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $this->productsManagers->delete($product);
            $this->flashy->success('Produit supprimé avec succès.');
            $this->addFlash('success', 'Produit supprimé avec succès.');
        }

        return $this->redirectToRoute('admin.products.index');
    }
}
