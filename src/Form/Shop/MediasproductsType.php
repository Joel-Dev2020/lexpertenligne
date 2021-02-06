<?php

namespace App\Form\Shop;

use App\Entity\Agencements\Mediasagencements;
use App\Entity\Shop\Mediasproducts;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class MediasproductsType extends AbstractType
{
    /**
     * @var CacheManager
     */
    private $cacheManager;

    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $cacheManager = $this->cacheManager;
        $builder
            ->add('product_id', HiddenType::class)
            ->add('name', null, ['label' => 'Titre de la photo', 'attr' => [
                'placeholder' => 'Titre de la photo',
                'class' => 'form-control mb-1'
            ]])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image',
                'required' => true,
                'image_uri' => static function (Mediasproducts $mediasproducts, $resolvedUri) use($cacheManager) {
                    // $cacheManager is LiipImagine cache manager
                    if($resolvedUri){
                        return $cacheManager->getBrowserPath(
                            $resolvedUri,
                            'mini',
                            ['thumbnail' => ['size' => [200, 100]]]
                        );
                    }
                },
                'attr' => [
                    'class' => 'form-control file-styled  mb-1'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mediasproducts::class,
        ]);
    }
}
