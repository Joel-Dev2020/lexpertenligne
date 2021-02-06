<?php

namespace App\Controller;

use App\Entity\Pages;
use App\Entity\Shop\Categories;
use App\Entity\Shop\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    /**
     * @Route("/sitemap.xml", name="sitemap", defaults={"_format": "xml"}, schemes={"https"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        //On réccupère le nom d'hote depuis l'url
        $hostname = $request->getSchemeAndHttpHost();

        //On initialise un tableau pour lister les urls
        $urls = [];

        //On ajoute les urls statiques
        $urls[] = ['loc' => $this->generateUrl('home')];
        $urls[] = ['loc' => $this->generateUrl('security_login')];
        $urls[] = ['loc' => $this->generateUrl('security_register')];
        $urls[] = ['loc' => $this->generateUrl('contacts')];
        $urls[] = ['loc' => $this->generateUrl('shop.index')];

        $pages = $this->getDoctrine()->getRepository(Pages::class)->findAll();
        $products = $this->getDoctrine()->getRepository(Products::class)->findAll();
        $categoriesproducts = $this->getDoctrine()->getRepository(Categories::class)->findAll();

        foreach ($categoriesproducts as $categoriesproduct){
            $images = [
                'loc' => '/uploads/products/categories/'.$categoriesproduct->getFilename(),
                'title' => $categoriesproduct->getName(),
            ];
            $urls[] = [
                'loc' => $this->generateUrl('shop.products.categories', ['id' => $categoriesproduct->getId(), 'slug' => $categoriesproduct->getSlug()]),
                'image' => $images,
                'lastmod' => $categoriesproduct->getUpdatedAt()->format('Y-m-d')
            ];
        }

        foreach ($products as $product){
            $images = [
                'loc' => '/uploads/products/'.$product->getFilename(),
                'title' => $product->getName(),
            ];
            $urls[] = [
                'loc' => $this->generateUrl('shop.products.show', ['id' => $product->getId(), 'slug' => $product->getSlug()]),
                'image' => $images,
                'lastmod' => $product->getUpdatedAt()->format('Y-m-d')
            ];
        }

        foreach ($pages as $page){
            $images = [
                'loc' => '/uploads/pages/'.$page->getFilename(),
                'title' => $page->getName(),
            ];
            $urls[] = [
                'loc' => $this->generateUrl('pages.about.show', ['id' => $page->getId(), 'slug' => $page->getSlug()]),
                'image' => $images,
                'lastmod' => $page->getUpdatedAt()->format('Y-m-d')
            ];
            $urls[] = [
                'loc' => $this->generateUrl('pages.policy.show', ['id' => $page->getId(), 'slug' => $page->getSlug()]),
                'image' => $images,
                'lastmod' => $page->getUpdatedAt()->format('Y-m-d')
            ];
        }

        // Fabrication de la réponse XML
        $response = new Response(
            $this->renderView('web/sitemap/index.html.twig', ['urls' => $urls,
                'hostname' => $hostname]),
            200
        );
        // Ajout des entêtes
        $response->headers->set('Content-Type', 'text/xml');

        // On envoie la réponse
        return $response;

    }
}
