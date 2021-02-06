<?php

namespace App\Form\Pages;

use App\Entity\Pages\Categories;
use App\Entity\Pages\Pages;
use App\Entity\Tags;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PagesType extends AbstractType
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
            ->add('name', null, ['label' => 'Nom de la page', 'attr' => [
                'placeholder' => 'Nom de la page',
                'class' => 'form-control-lg'
            ]])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image',
                'help' => 'Votre logo doit faire 1920 x 1280 au maximum',
                'required' => false,
                'asset_helper' => true,
                'image_uri' => static function (Pages $pages, $resolvedUri) use($cacheManager) {
                    // $cacheManager is LiipImagine cache manager
                    if($resolvedUri){
                        return $cacheManager->getBrowserPath(
                            $resolvedUri,
                            'logo',
                            ['thumbnail' => ['size' => [200, 200]]]
                        );
                    }
                },
                'attr' => [
                    'class' => 'form-control-lg custom-file-input'
                ],
            ])
            ->add('extrait', CKEditorType::class, [
                'label' => 'Description ou Extrait',
                'attr' => [
                    'class' => 'form-control-lg',
                    'placeholder' => 'Description ou extrait de la page'
                ]
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'Contenu',
                'attr' => [
                    'class' => 'form-control-lg',
                    'placeholder' => 'Contenu de la page'
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'label' => 'Catégories',
                'placeholder' => 'Aucune catégorie',
                'required' => true,
                'multiple' => true,
                'choice_label' => function($categorie){
                    return $categorie->getName();
                },
                'attr' => [
                    'data-live-search' => 'true',
                ],
            ])
            ->add('tags', EntityType::class, [
                'class' => Tags::class,
                'label' => 'Mots clé',
                'placeholder' => 'Sélectionner un ou plusieurs mots clés',
                'required' => false,
                'multiple' => true,
                'choice_label' => function($categorie){
                    return $categorie->getName();
                },
                'attr' => [
                    'data-live-search' => 'true',
                ],
            ])
            ->add('online', null, ['label' => 'Publier ?', 'attr' => ['class' => 'switcher-input']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pages::class,
        ]);
    }
}
