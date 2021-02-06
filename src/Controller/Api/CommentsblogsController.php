<?php

namespace App\Controller\Api;

use App\Managers\Blogs\CommentairesblogsManagers;

class CommentsblogsController
{
    /**
     * @var CommentairesblogsManagers
     */
    private $commentairesblogsManagers;

    /**
     * CreateProductsController constructor.
     * @param CommentairesblogsManagers $commentairesblogsManagers
     */
    public function __construct(CommentairesblogsManagers $commentairesblogsManagers)
    {
        $this->commentairesblogsManagers = $commentairesblogsManagers;
    }

    public function __invoke($data)
    {
        return $this->commentairesblogsManagers->getComment($data);
    }
}