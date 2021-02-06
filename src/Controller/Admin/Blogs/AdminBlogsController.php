<?php

namespace App\Controller\Admin\Blogs;

use App\Entity\Blogs\Blogs;
use App\Form\Blogs\BlogsType;
use App\Managers\Blogs\BlogsManagers;
use App\Repository\Blogs\BlogsRepository;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/blogs", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminBlogsController extends AbstractController
{
    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var BlogsManagers
     */
    private $blogManagers;
    /**
     * @var PaginatorInterface
     */
    private $pagination;

    public function __construct(
        FlashyNotifier $flashy,
        PaginatorInterface $pagination,
        BlogsManagers $blogManagers
    )
    {
        $this->flashy = $flashy;
        $this->blogManagers = $blogManagers;
        $this->pagination = $pagination;
    }

    /**
     * @Route("/", name="admin.blogs.index", methods={"GET"})
     * @param Request $request
     * @param BlogsRepository $blogsRepository
     * @return Response
     */
    public function index(Request $request, BlogsRepository $blogsRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $blogs = $this->pagination->paginate(
            $blogsRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 10)/*limit per page*/
        );

        return $this->render('admin/blogs/index.html.twig', [
            'blogs' => $blogs,
            'title' => 'Liste des blogs',
            'libelle_ajouter' => 'Nouveau blog',
            'current_page' => 'blogs',
            'current_global' => 'blogs'
        ]);
    }

    /**
     * @Route("/activer/{id}", name="admin.blogs.activer")
     * @param Request $request
     * @param Blogs $blog
     * @return Response
     */
    public function activer(Request $request, Blogs $blog): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->blogManagers->active($blog);
        $this->flashy->success("Blog modifié avec succès.");
        $this->addFlash('success', 'Blog modifié avec succès.');
        return $this->redirectToRoute('admin.blogs.index');
    }

    /**
     * @Route("/new", name="admin.blogs.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     *
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $blog = new Blogs();
        $form = $this->createForm(BlogsType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->blogManagers->new($blog);
            $this->flashy->success("Blog ajouté avec succès.");
            $this->addFlash('success', 'Blog ajouté avec succès.');
            return $this->redirectToRoute('admin.blogs.new');
        }

        return $this->render('admin/blogs/new.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
            'title' => 'Ajouter un nouvel blog',
            'libelle_liste' => 'Liste des blogs',
            'current_page' => 'blogs_new',
            'current_global' => 'blogs'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.blogs.show", methods={"GET"})
     * @param Blogs $blog
     * @return Response
     */
    public function show(Blogs $blog): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('admin/blogs/show.html.twig', [
            'blog' => $blog,
            'title' => 'Détails blog',
            'libelle_liste' => 'Liste des blogs',
            'libelle_ajouter' => 'Nouveau blog',
            'current_page' => 'blogs',
            'current_global' => 'blogs'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.blogs.edit", methods={"GET","POST"})
     * @param Request $request
     * @param Blogs $blog
     * @return Response
     * @throws Exception
     */
    public function edit(Request $request, Blogs $blog): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(BlogsType::class, $blog);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->blogManagers->edit($blog);
            $this->flashy->success("Modification éffectuée.");
            $this->addFlash('success', 'Blog modifié avec succès.');
            return $this->redirectToRoute('admin.blogs.edit', [
                'id' => $blog->getId(),
            ]);
        }

        return $this->render('admin/blogs/edit.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
            'title' => 'Editer un blog',
            'libelle_ajouter' => 'Nouveau blog',
            'libelle_liste' => 'Liste des blogs',
            'current_page' => 'blogs',
            'current_global' => 'blogs'
        ]);
    }

    /**
     * @Route("/{id}", name="admin.blogs.delete", methods={"DELETE"})
     * @param Request $request
     * @param Blogs $blog
     * @return Response
     * @throws Exception
     */
    public function delete(Request $request, Blogs $blog): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete'.$blog->getId(), $request->request->get('_token'))) {
            $this->blogManagers->delete($blog);
            $this->flashy->success("Suppression éffectuée.");
            $this->addFlash('success', 'Blog supprimé avec succès.');
        }

        return $this->redirectToRoute('admin.blogs.index');
    }
}
