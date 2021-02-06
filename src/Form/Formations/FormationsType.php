<?php

namespace App\Form\Formations;

use App\Entity\Formations\Categoriesformations;
use App\Entity\Formations\Formations;
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

class FormationsType extends AbstractType
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
            ->add('name', null, ['label' => 'Nom de la formation', 'attr' => [
                'placeholder' => 'Nom de la formation',
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
                    'placeholder' => 'Bréve resumé de la formation'
                ]
            ])
            ->add('objectif', CKEditorType::class, [
                'label' => 'Objectif',
                'required' => false,
                'attr' => [
                    'class' => 'form-control-lg',
                    'placeholder' => 'Objectif de la formation'
                ]
            ])
            ->add('niveau', ChoiceType::class, [
                'label' => 'Niveau de dificulté',
                'expanded' => true,
                'multiple' => false,
                'placeholder' => 'Niveau de dificulté',
                'choices' => $this->getNiveau(),
                'attr' => [
                    'class' => ''
                ]
            ])
            ->add('duree', TextType::class, [
                'label' => 'Durée de la formation',
                'required' => false,
                'attr' => [
                    'class' => 'form-control-lg',
                    'placeholder' => 'Durée de la formation'
                ]
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'Contenu',
                'required' => false,
                'attr' => [
                    'class' => 'form-control-lg',
                    'placeholder' => 'Contenu de la formation'
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Couverture',
                'help' => 'Votre image doit faire 900 x 611 au maximum',
                'required' => false,
                'asset_helper' => true,
                'image_uri' => static function (Formations $formations, $resolvedUri) use($cacheManager) {
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
                'image_uri' => static function (Formations $formations, $resolvedUri) use($cacheManager) {
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
                'class' => Categoriesformations::class,
                'label' => 'Catégories',
                'placeholder' => 'Sélectionner une catégorie',
                'required' => false,
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
            'data_class' => Formations::class,
        ]);
    }

    private function getNiveau(){
        $choices = Formations::NIVEAU;
        $output = [];
        foreach ($choices as $k => $v){
            $output[$v] = $k;
        }
        return $output;
    }
}
