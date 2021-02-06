<?php

namespace App\Controller\Admin\Shop;

use App\Entity\Shop\Products;
use App\Form\Shop\FacturesType;
use App\PDF\PdfServices;
use App\Repository\Shop\CommandesRepository;
use App\Repository\Shop\ProductsRepository;
use DateTime;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/products/factures", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminFacturesController extends AbstractController
{

    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var CommandesRepository
     */
    private $commandesRepository;
    /**
     * @var PdfServices
     */
    private $pdfServices;


    /**
     * AdminFacturesController constructor.
     * @param FlashyNotifier $flashy
     * @param CommandesRepository $commandesRepository
     * @param PdfServices $pdfServices
     */
    public function __construct(FlashyNotifier $flashy, CommandesRepository $commandesRepository, PdfServices $pdfServices)
    {
        $this->flashy = $flashy;
        $this->commandesRepository = $commandesRepository;
        $this->pdfServices = $pdfServices;
    }

    /**
     * @Route("/", name="admin.factures.index", methods={"GET","POST"})
     * @param Request $request
     * @param ProductsRepository $productsColorsRepository
     * @return Response
     */
    public function index(Request $request, ProductsRepository $productsColorsRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(FacturesType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Manupulation de la date selectionnée par l'utilisateur
            $datefacture = $form->get('datefacture')->getData();
            $explodedates = explode('-', $datefacture);
            $datedebut = DateTime::createFromFormat('d/m/Y', trim($explodedates[0]))->format('Y-m-d');
            $datefin = DateTime::createFromFormat('d/m/Y', trim($explodedates[1]))->format('Y-m-d');
            $commandes = $this->commandesRepository->getCommandeBetweenDates($datedebut, $datefin);

            if (!$commandes){
                $this->flashy->warning('Aucun resultat trouvé pour la période sélectionnée.');
                return $this->redirectToRoute('admin.factures.index');
            }

            $products = [];
            foreach ($commandes as $commande){
                foreach ($commande->getProducts() as $product){
                    $products[$commande->getId()] = [
                        'product' => $this->getDoctrine()->getRepository(Products::class)->find($product['product']->getId()),
                        'quantity' => $product['qte'],
                    ];
                }
            }
            //return $this->pdfServices->generateWithDates($commandes, $products, $datedebut , $datefin);
        }
        return $this->render('admin/shops/factures/index.html.twig', [
            'form' => $form->createView(),
            'title' => 'Générer une facture',
            'current_page' => 'factures',
            'current_global' => 'commandes'
        ]);
    }
}
