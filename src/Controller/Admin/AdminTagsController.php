<?php

namespace App\Controller\Admin;

use App\Entity\Tags;
use App\Form\TagsType;
use App\Managers\TagsManagers;
use App\Repository\TagsRepository;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/tags", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminTagsController extends AbstractController
{

    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var TagsManagers
     */
    private $tagsManagers;
    /**
     * @var PaginatorInterface
     */
    private $pagination;

    /**
     * AdminTagsController constructor.
     * @param FlashyNotifier $flashy
     * @param PaginatorInterface $pagination
     * @param TagsManagers $tagsManagers
     */
    public function __construct(
        FlashyNotifier $flashy,
        PaginatorInterface $pagination,
        TagsManagers $tagsManagers
    )
    {
        $this->flashy = $flashy;
        $this->tagsManagers = $tagsManagers;
        $this->pagination = $pagination;
    }

    /**
     * @Route("/", name="admin.tags.index", methods={"GET","POST"})
     * @param Request $request
     * @param TagsRepository $tagsRepository
     * @return Response
     */
    public function index(Request $request, TagsRepository $tagsRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $tag = new Tags();
        $form = $this->createForm(TagsType::class, $tag);
        $tags = $this->pagination->paginate(
            $tagsRepository->findBy([], ['id' => 'DESC']), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 10)/*limit per page*/
        );
        return $this->render('admin/tags/index.html.twig', [
            'tags' => $tags,
            'form' => $form->createView(),
            'title' => 'Liste des tags',
            'libelle_ajouter' => 'Nouveau tag',
            'current_page' => 'tags_list',
            'current_global' => 'tags'
        ]);
    }

    /**
     * @Route("/new", name="admin.tags.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $tag = new Tags();
        $form = $this->createForm(TagsType::class, $tag);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagsManagers->new($tag);
            $this->flashy->success('Tag ajouté avec succès.');
            return $this->redirectToRoute('admin.tags.index');
        }

        return $this->render('admin/tags/new.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
            'title' => 'Nouveau tag',
            'libelle_liste' => 'Liste des tags',
            'current_page' => 'tags_new',
            'current_global' => 'tags'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.tags.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Tags $tag
     * @return Response
     */
    public function edit(Request $request, Tags $tag): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(TagsType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagsManagers->edit($tag);
            $this->flashy->success('Tag modifié avec succès.');
            return $this->redirectToRoute('admin.tags.index');
        }

        return $this->render('admin/tags/edit.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
            'title' => 'Editer le tag',
            'libelle_ajouter' => 'Nouveau  tag',
            'libelle_liste' => 'Liste des tags',
            'current_page' => 'tags_list',
            'current_global' => 'tags'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.tags.delete", methods={"DELETE"})
     * @param Request $request
     * @param Tags $tag
     * @return Response
     */
    public function delete(Request $request, Tags $tag): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        if ($this->isCsrfTokenValid('delete'.$tag->getId(), $request->request->get('_token'))) {
            $this->tagsManagers->delete($tag);
            $this->flashy->success('Tag supprimé avec succès.');
        }

        return $this->redirectToRoute('admin.tags.index');
    }
}
