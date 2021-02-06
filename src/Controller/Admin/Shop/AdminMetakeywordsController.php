<?php

namespace App\Controller\Admin\Shop;

use App\Entity\Shop\Metakeywords;
use App\Form\Shop\MetakeywordsType;
use App\Repository\Shop\MetakeywordsRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/products/metakeywords", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminMetakeywordsController extends AbstractController
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
     * @Route("/", name="admin.metakeywords.index", methods={"GET","POST"})
     * @param Request $request
     * @param MetakeywordsRepository $metakeywordsRepository
     * @return Response
     */
    public function index(Request $request, MetakeywordsRepository $metakeywordsRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $metakeyword = new Metakeywords();
        $form = $this->createForm(MetakeywordsType::class, $metakeyword);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($metakeyword);
            $manager->flush();
            $this->flashy->success('Mot clé ajouté avec succès.');
            return $this->redirectToRoute('admin.metakeywords.index');
        }
        return $this->render('admin/shops/metakeywords/index.html.twig', [
            'metakeywords' => $metakeywordsRepository->findBy([], ['id' => 'DESC']),
            'form' => $form->createView(),
            'title' => 'Liste des mots clés',
            'libelle_ajouter' => 'Nouveau mot clé',
            'current_page' => 'metakeywords',
            'current_global' => 'products'
        ]);
    }

    /**
     * @Route("/new", name="admin.metakeywords.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $metakeyword = new Metakeywords();
        $form = $this->createForm(MetakeywordsType::class, $metakeyword);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($metakeyword);
            $manager->flush();
            $this->flashy->success('Mot clé ajouté avec succès.');
            return $this->redirectToRoute('admin.metakeywords.new');
        }

        return $this->render('admin/shops/metakeywords/new.html.twig', [
            'metakeyword' => $metakeyword,
            'form' => $form->createView(),
            'title' => 'Nouveau mot clé',
            'libelle_liste' => 'Liste des mots clés',
            'current_page' => 'metakeywordsnew',
            'current_global' => 'products'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.metakeywords.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Metakeywords $metakeyword
     * @return Response
     */
    public function edit(Request $request, Metakeywords $metakeyword): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(MetakeywordsType::class, $metakeyword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
            $this->flashy->success('Mot clé modifié avec succès.');
            return $this->redirectToRoute('admin.metakeywords.index');
        }

        return $this->render('admin/shops/metakeywords/edit.html.twig', [
            'metakeyword' => $metakeyword,
            'form' => $form->createView(),
            'title' => 'Editer catégorie',
            'libelle_ajouter' => 'Nouveau mot clé',
            'libelle_liste' => 'Liste des mots clés',
            'current_page' => 'metakeywords',
            'current_global' => 'products'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.metakeywords.delete", methods={"DELETE"})
     * @param Request $request
     * @param Metakeywords $metakeyword
     * @return Response
     */
    public function delete(Request $request, Metakeywords $metakeyword): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        if ($this->isCsrfTokenValid('delete'.$metakeyword->getId(), $request->request->get('_token'))) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($metakeyword);
            $manager->flush();
            $this->flashy->success('Mot clé supprimé avec succès.');
        }

        return $this->redirectToRoute('admin.metakeywords.index');
    }
}
