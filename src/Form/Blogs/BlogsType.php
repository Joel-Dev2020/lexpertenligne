<?php

namespace App\Form\Blogs;

use App\Entity\Blogs\Categoriesblogs;
use App\Entity\Blogs\Blogs;
use App\Entity\Tags;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class BlogsType extends AbstractType
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
            ->add('name', null, ['label' => 'Nom du blog', 'attr' => [
                'placeholder' => 'Nom du blog',
                'class' => 'form-control-lg'
            ]])
            ->add('publeshedAt', TextType::class, [
                'label' => 'Date de publication',
                'attr' => [
                    'class' => 'form-control-lg flatpickr-input',
                    'data-toggle' => 'flatpickr',
                    'placeholder' => 'Date de publication'
                ]
            ])
            ->add('extrait', TextareaType::class, [
                'label' => 'Resumé',
                'required' => false,
                'attr' => [
                    'class' => 'form-control-lg',
                    'rows' => '3',
                    'placeholder' => 'Bréve resumé du blog'
                ]
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'Contenu',
                'required' => false,
                'attr' => [
                    'class' => 'form-control-lg',
                    'placeholder' => 'Contenu du blog'
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Couverture',
                'help' => 'Votre image doit faire 900 x 611 au maximum',
                'required' => false,
                'asset_helper' => true,
                'image_uri' => static function (Blogs $blogs, $resolvedUri) use($cacheManager) {
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
            ->add('imageCoverFile', VichImageType::class, [
                'label' => 'Bandeau',
                'help' => 'Votre image doit faire 1600 x 248 au maximum',
                'required' => false,
                'asset_helper' => true,
                'image_uri' => static function (Blogs $blogs, $resolvedUri) use($cacheManager) {
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
            ->add('online', null, ['label' => 'Publier ?', 'attr' => ['class' => 'switcher-input']])
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
            ->add('featured', null, ['label' => 'Mettre en vedette', 'attr' => ['class' => 'switcher-input']])
            ->add('categories', EntityType::class, [
                'class' => Categoriesblogs::class,
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
            'data_class' => Blogs::class,
        ]);
    }
}
