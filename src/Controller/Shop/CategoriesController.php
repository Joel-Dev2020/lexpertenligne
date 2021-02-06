<?php

namespace App\Controller\Shop;

use App\Repository\Shop\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/shop/categories", schemes={"https"})
 */
class CategoriesController extends AbstractController
{
    /**
     * @var CategoriesRepository
     */
    private $categoriesRepository;

    /**
     * MarquesController constructor.
     * @param CategoriesRepository $categoriesRepository
     */
    public function __construct(CategoriesRepository $categoriesRepository)
    {
        $this->categoriesRepository = $categoriesRepository;
    }

    /**
     * @Route("/", name="categories.index")
     * @return Response
     */
    public function index()
    {
        return $this->render('web/shops/categories/index.html.twig', [
            'title' => 'Liste des catégories',
            'categories' => $this->categoriesRepository->findAll(),
            'current_page' => 'categories',
            'current_global' => 'categories',
        ]);
    }
}