<?php

namespace App\Controller\Admin;

use App\Entity\Abonnes;
use App\Form\AbonnesType;
use App\Managers\AbonnesManagers;
use App\Repository\AbonnesRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/abonnes", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminAbonnesController extends AbstractController
{

    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var AbonnesManagers
     */
    private $abonnesManagers;

    public function __construct(FlashyNotifier $flashy, AbonnesManagers $abonnesManagers)
    {
        $this->flashy = $flashy;
        $this->abonnesManagers = $abonnesManagers;
    }

    /**
     * @Route("/", name="admin.abonnes.index", methods={"GET"})
     * @param AbonnesRepository $abonnesRepository
     * @return Response
     */
    public function index(AbonnesRepository $abonnesRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $abonne = new Abonnes();
        $form = $this->createForm(AbonnesType::class, $abonne);
        return $this->render('admin/abonnes/index.html.twig', [
            'abonnes' => $abonnesRepository->findBy([], ['id' => 'DESC']),
            'form' => $form->createView(),
            'title' => 'Liste des abonnés',
            'libelle_ajouter' => 'Nouvel  abonné',
            'current_page' => 'abonnes_nesletter',
            'current_global' => 'abonnes'
        ]);
    }

    /**
     * @Route("/activer/{id}", name="admin.abonnes.activer")
     * @param Abonnes $abonne
     * @return Response
     */
    public function activer(Abonnes $abonne): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->abonnesManagers->active($abonne);
        $this->flashy->success("Abonné modifié avec succès.");
        return $this->redirectToRoute('admin.abonnes.index');
    }

    /**
     * @Route("/new", name="admin.abonnes.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $abonne = new Abonnes();
        $form = $this->createForm(AbonnesType::class, $abonne);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->abonnesManagers->new($abonne);
            $this->flashy->success('Abonné ajouté avec succès.');
            return $this->redirectToRoute('admin.abonnes.new');
        }

        return $this->render('admin/abonnes/new.html.twig', [
            'abonne' => $abonne,
            'form' => $form->createView(),
            'title' => 'Nouvel  abonné',
            'libelle_liste' => 'Liste des abonnés',
            'current_page' => 'abonnes_nesletter',
            'current_global' => 'abonnes'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.abonnes.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Abonnes $abonne
     * @return Response
     */
    public function edit(Request $request, Abonnes $abonne): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(AbonnesType::class, $abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->abonnesManagers->edit($abonne);
            $this->flashy->success('Abonné modifié avec succès.');
            return $this->redirectToRoute('admin.abonnes.index');
        }

        return $this->render('admin/abonnes/edit.html.twig', [
            'abonne' => $abonne,
            'form' => $form->createView(),
            'title' => 'Editer catégorie',
            'libelle_ajouter' => 'Nouvel  abonné',
            'libelle_liste' => 'Liste des abonnés',
            'current_page' => 'abonnes_nesletter',
            'current_global' => 'abonnes'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.abonnes.delete", methods={"DELETE"})
     * @param Request $request
     * @param Abonnes $abonne
     * @return Response
     */
    public function delete(Request $request, Abonnes $abonne): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        if ($this->isCsrfTokenValid('delete'.$abonne->getId(), $request->request->get('_token'))) {
            $this->abonnesManagers->delete($abonne);
            $this->flashy->success('Abonné supprimé avec succès.');
        }

        return $this->redirectToRoute('admin.abonnes.index');
    }
}
