<?php

namespace App\Controller\Blogs;

use App\Entity\Blogs\Blogs;
use App\Entity\Blogs\Votesblogs;
use App\Repository\Blogs\BlogsRepository;
use App\Repository\Blogs\VotesblogsRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/likes", schemes={"https"})
 */
class LikesController extends AbstractController
{
    /**
     * @var BlogsRepository
     */
    private $repository;
    /**
     * @var FlashyNotifier
     */
    private $flashy;
    /**
     * @var VotesblogsRepository
     */
    private $votesblogsRepository;

    /**
     * BlogsController constructor.
     * @param BlogsRepository $repository
     * @param VotesblogsRepository $votesblogsRepository
     * @param FlashyNotifier $flashy
     */
    public function __construct(
        BlogsRepository $repository,
        VotesblogsRepository $votesblogsRepository,
        FlashyNotifier $flashy)
    {
        $this->repository = $repository;
        $this->flashy = $flashy;
        $this->votesblogsRepository = $votesblogsRepository;
    }

    /**
     * @Route("/blog/{id}", name="likes.blog")
     * @param Blogs $blog
     * @return RedirectResponse
     */
    public function like(Blogs $blog)
    {
        $vote = $this->votesblogsRepository->findOneBy(['user' => $this->getUser(), 'blogs' => $blog]);
        $em = $this->getDoctrine()->getManager();
        if (!$vote){
            $vote = new Votesblogs();
            $vote->setUser($this->getUser());
            $vote->setBlogs($blog);
            $vote->setVote(true);
            $em->persist($vote);
            $em->flush();
            $this->flashy->success('Vous aimé cette actualité');
            $this->addFlash('success','Vous aimé cette actualité');
            return $this->redirectToRoute('blogs.show', [
                'id' => $blog->getId(),
                'slug' => $blog->getSlug(),
            ]);
        }
        $vote->setVote(($vote->getVote()) ? false:true);
        dd($blog);
        return $this->redirectToRoute('blogs.show', [
            'id' => $blog->getId(),
            'slug' => $blog->getSlug(),
        ]);
    }
}