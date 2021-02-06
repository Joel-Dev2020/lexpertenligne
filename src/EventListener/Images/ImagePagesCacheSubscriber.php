<?php

namespace App\EventListener\Images;

use App\Entity\Pages;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImagePagesCacheSubscriber implements EventSubscriber
{

    /**
     * @var CacheManager
     */
    private $cacheManager;
    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;

    public function __construct(CacheManager $cacheManager, UploaderHelper $uploaderHelper)
    {
        $this->cacheManager = $cacheManager;
        $this->uploaderHelper = $uploaderHelper;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public function getSubscribedEvents()
    {
        return [
            'preRemove',
            'preUpdate',
        ];
    }
    public function preRemove(LifecycleEventArgs $args){
        $entity = $args->getEntity();
        if(!$entity instanceof Pages){
            return ;
        }
        $this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile'));
        $this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile2'));
    }

    public function preUpdate(PreUpdateEventArgs $args){
        $entity = $args->getEntity();
        if(!$entity instanceof Pages){
            return ;
        }
        if($entity->getImageFile() instanceof UploadedFile){
            $this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile'));
        }
        if($entity->getImageFile2() instanceof UploadedFile){
            $this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile2'));
        }
    }
}