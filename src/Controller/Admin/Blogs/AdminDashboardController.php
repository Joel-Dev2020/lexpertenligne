<?php

namespace App\Controller\Admin\Blogs;

use App\Repository\Blogs\BlogsRepository;
use App\Repository\Blogs\CommentairesblogsRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security;

/**
 * @Route("/admins/blogs/dashboard", schemes={"https"})
 * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')")
 */
class AdminDashboardController extends AbstractController
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
     * @Route("/", name="admin.blogs.dashboard", methods={"GET"})
     * @param BlogsRepository $blogsRepository
     * @param CommentairesblogsRepository $commentairesblogsRepository
     * @return Response
     */
    public function index(BlogsRepository $blogsRepository, CommentairesblogsRepository $commentairesblogsRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $lastBlogs = $blogsRepository->findBy([], ['id' => 'DESC'], 4);
        $lastComments = $commentairesblogsRepository->findBy([], ['id' => 'DESC'], 2);
        return $this->render('admin/blogs/dashboard.html.twig', [
            'title' => 'Blogs dashboard',
            'lastBlogs' => $lastBlogs,
            'lastComments' => $lastComments,
            'current_page' => 'blogsdashboard',
            'current_global' => 'blogs'
        ]);
    }
}
