<?php

namespace App\Controller\Admin\Blogs;

use App\Entity\Blogs\Blogs;
use App\Entity\Blogs\Mediasblogs;
use App\Form\Blogs\MediasblogsType;
use App\Managers\Blogs\MediasblogsManagers;
use App\Repository\Blogs\BlogsRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/admins/blogs/medias", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminMediasblogsController extends AbstractController
{
    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var MediasblogsManagers
     */
    private $mediasblogsManagers;
    /**
     * @var BlogsRepository
     */
    private $blogsRepository;

    public function __construct(FlashyNotifier $flashy, MediasblogsManagers $mediasblogsManagers, BlogsRepository $blogsRepository)
    {
        $this->flashy = $flashy;
        $this->mediasblogsManagers = $mediasblogsManagers;
        $this->blogsRepository = $blogsRepository;
    }

    /**
     * @Route("/new", name="admin.mediasblogs.new", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function new(Request $request, ValidatorInterface $validator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $mediasblogs = new Mediasblogs();
        $form = $this->createForm(MediasblogsType::class, $mediasblogs);
        $form->handleRequest($request);
        $blog_id = intval($form->get('blog_id')->getData());
        $blog = $this->blogsRepository->find($blog_id);
        $errors = $validator->validate($mediasblogs);
        if (count($errors) > 0) {
            $this->flashy->error("Votre image est bien trop grande.");
            return $this->redirectToRoute('admin.blogs.show', ['id' => $blog->getId()]);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->mediasblogsManagers->new($mediasblogs, $blog);
            $this->flashy->success("Photo ajoutée avec succès.");
            return $this->redirectToRoute('admin.blogs.show', ['id' => $blog->getId()]);
        }
        return $this->render('admin/blogs/show.html.twig', [
            'mediasblogs' => $mediasblogs,
            'blog' => $blog,
            'title' => 'Détails de la blog',
            'form' => $form->createView(),
            'libelle_liste' => 'Liste des blogs',
            'libelle_ajouter' => 'Nouveau blog',
            'current_page' => 'mediasblogsposts',
            'current_global' => 'mediasblogs'
        ]);
    }

    /**
     * @Route("/{id}/{blog_id}", name="admin.mediasblogs.delete", methods={"DELETE"})
     * @param Request $request
     * @param Mediasblogs $mediasblogs
     * @param $blog_id
     * @return Response
     */
    public function delete(Request $request, Mediasblogs $mediasblogs, $blog_id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete'.$mediasblogs->getId(), $request->request->get('_token'))) {
            $blog = $this->blogsRepository->find($blog_id);
            $this->mediasblogsManagers->delete($mediasblogs, $blog);
            $this->flashy->success("Photo supprimée avec succès.");
        }

        return $this->redirectToRoute('admin.blogs.show', ['id' => $blog_id]);
    }
}
