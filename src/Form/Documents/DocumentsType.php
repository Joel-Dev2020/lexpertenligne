<?php

namespace App\Form\Documents;

use App\Entity\Documents\Categoriesdocuments;
use App\Entity\Documents\Documents;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class DocumentsType extends AbstractType
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
            ->add('name', null, ['label' => 'Nom du document'])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Couverture',
                'help' => 'Votre image doit faire 600 x 300 au maximum',
                'required' => false,
                'asset_helper' => true,
                'image_uri' => static function (Documents $documents, $resolvedUri) use($cacheManager) {
                    // $cacheManager is LiipImagine cache manager
                    if($resolvedUri){
                        return $cacheManager->getBrowserPath(
                            $resolvedUri,
                            'mini',
                            ['thumbnail' => ['size' => [200, 200]]]
                        );
                    }
                },
                'attr' => [
                    'class' => 'form-control-lg custom-file-input'
                ],
            ])
            ->add('documentFile', VichFileType::class, [
                'required' => false,
                'label' => 'Sélectionner le document (pdf)',
            ])
            ->add('online', null, ['label' => 'Mettre en ligne?'])
            ->add('categories', EntityType::class, [
                'class' => Categoriesdocuments::class,
                'label' => 'Catégories',
                'required' => false,
                'placeholder' => 'Sélectionner une catégorie',
                'choice_label' => 'name',
                'attr' => [
                    'data-live-search' => 'true',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Documents::class,
        ]);
    }
}
