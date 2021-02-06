<?php

namespace App\Controller\Admin\Dictionnaires;

use App\Entity\Dictionnaires\Dictionnaires;
use App\Form\Dictionnaires\DictionnairesType;
use App\Managers\Dictionnaires\DictionnairesManagers;
use App\Repository\Dictionnaires\DictionnairesRepository;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/dictionnaires", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminDictionnairesController extends AbstractController
{

    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var DictionnairesManagers
     */
    private $dictionnairesManagers;
    /**
     * @var PaginatorInterface
     */
    private $pagination;

    /**
     * AdminDictionnairesController constructor.
     * @param FlashyNotifier $flashy
     * @param PaginatorInterface $pagination
     * @param DictionnairesManagers $dictionnairesManagers
     */
    public function __construct(
        FlashyNotifier $flashy,
        PaginatorInterface $pagination,
        DictionnairesManagers $dictionnairesManagers
    )
    {
        $this->flashy = $flashy;
        $this->dictionnairesManagers = $dictionnairesManagers;
        $this->pagination = $pagination;
    }

    /**
     * @Route("/", name="admin.dictionnaires.index", methods={"GET","POST"})
     * @param Request $request
     * @param DictionnairesRepository $dictionnairesRepository
     * @return Response
     */
    public function index(Request $request, DictionnairesRepository $dictionnairesRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $dictionnaire = new Dictionnaires();
        $form = $this->createForm(DictionnairesType::class, $dictionnaire);
        $dictionnaires = $this->pagination->paginate(
            $dictionnairesRepository->findBy([], ['id' => 'DESC']), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 10)/*limit per page*/
        );
        return $this->render('admin/dictionnaires/index.html.twig', [
            'dictionnaires' => $dictionnaires,
            'form' => $form->createView(),
            'title' => 'Liste des lexiques',
            'libelle_ajouter' => 'Nouveau lexique',
            'current_page' => 'dictionnairess_list',
            'current_global' => 'dictionnaires'
        ]);
    }

    /**
     * @Route("/new", name="admin.dictionnaires.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $dictionnaire = new Dictionnaires();
        $form = $this->createForm(DictionnairesType::class, $dictionnaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->dictionnairesManagers->new($dictionnaire);
            $this->flashy->success('Dictionnaire ajouté avec succès.');
            return $this->redirectToRoute('admin.dictionnaires.index');
        }

        return $this->render('admin/dictionnaires/new.html.twig', [
            'dictionnaire' => $dictionnaire,
            'form' => $form->createView(),
            'title' => 'Nouveau lexique',
            'libelle_liste' => 'Liste des lexiques',
            'current_page' => 'dictionnaires_new',
            'current_global' => 'dictionnaires'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.dictionnaires.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Dictionnaires $dictionnaire
     * @return Response
     */
    public function edit(Request $request, Dictionnaires $dictionnaire): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(DictionnairesType::class, $dictionnaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dictionnairesManagers->edit($dictionnaire);
            $this->flashy->success('Lexique modifié avec succès.');
            return $this->redirectToRoute('admin.dictionnaires.index');
        }

        return $this->render('admin/dictionnaires/edit.html.twig', [
            'dictionnaire' => $dictionnaire,
            'form' => $form->createView(),
            'title' => 'Editer le lexique',
            'libelle_ajouter' => 'Nouveau lexique',
            'libelle_liste' => 'Liste des lexiques',
            'current_page' => 'dictionnaires_list',
            'current_global' => 'dictionnaires'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.dictionnaires.delete", methods={"DELETE"})
     * @param Request $request
     * @param Dictionnaires $dictionnaire
     * @return Response
     */
    public function delete(Request $request, Dictionnaires $dictionnaire): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        if ($this->isCsrfTokenValid('delete'.$dictionnaire->getId(), $request->request->get('_token'))) {
            $this->dictionnairesManagers->delete($dictionnaire);
            $this->flashy->success('Lexique supprimé avec succès.');
        }

        return $this->redirectToRoute('admin.dictionnaires.index');
    }
}
