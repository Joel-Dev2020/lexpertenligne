<?php

namespace App\Form\Shop;

use App\Entity\Shop\Categories;
use App\Entity\Shop\Metakeywords;
use App\Entity\Shop\Products;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductsType extends AbstractType
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
            ->add('name', null, ['label' => 'Nom du produit (*)',
                'help' => 'Le nom du produit est obligatoire (*)',
                'attr' => [
                    'placeholder' => 'Nom du produit',
                    'class' => ''
                ]
            ])
            ->add('sku', null, ['label' => 'SKU (*)',
                'help' => 'Ce champ est obligatoire',
                'attr' => [
                    'placeholder' => 'SKU (*)',
                    'class' => ''
                ]
            ])
            ->add('extrait', TextareaType::class, [
                'label' => 'Petit extrait du produit',
                'help' => 'L\'Extrait doit comporter au plus 255 catactères',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Petit extrait du produit',
                    'class' => ''
                ]
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'Description du produit',
                'required' => false,
                'attr' => [
                    'class' => ''
                ]
            ])
            ->add('metadescription', TextareaType::class, [
                'label' => 'Méta description du produit',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Méta description du produit',
                    'class' => ''
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image',
                'help' => 'Votre photo doit faire 768 x 1035 au maximum',
                'required' => false,
                'asset_helper' => true,
                'image_uri' => static function (Products $products, $resolvedUri) use($cacheManager) {
                    // $cacheManager is LiipImagine cache manager
                    if($resolvedUri){
                        return $cacheManager->getBrowserPath(
                            $resolvedUri,
                            'avatar',
                            ['thumbnail' => ['size' => [215, 215]]]
                        );
                    }
                },
                'attr' => [
                    'class' => 'custom-file-input'
                ],
            ])
            ->add('imageFileHover', VichImageType::class, [
                'label' => 'Image au survol',
                'help' => 'Votre photo doit faire 768 x 1035 au maximum',
                'required' => false,
                'asset_helper' => true,
                'image_uri' => static function (Products $products, $resolvedUri) use($cacheManager) {
                    // $cacheManager is LiipImagine cache manager
                    if($resolvedUri){
                        return $cacheManager->getBrowserPath(
                            $resolvedUri,
                            'avatar',
                            ['thumbnail' => ['size' => [215, 215]]]
                        );
                    }
                },
                'attr' => [
                    'class' => 'custom-file-input'
                ],
            ])
            ->add('price', null, ['label' => 'Prix du produit (*)',
                'help' => 'Le prix du produit est obligatoire (*)',
                'attr' => [
                    'placeholder' => 'Prix du produit',
                    'class' => ''
                ]
            ])
            ->add('pricepromo', null, ['label' => 'Prix promo',
                'help' => 'Le prix promo du produit',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Prix du promo du produit',
                    'class' => ''
                ]
            ])
            ->add('quantity', null, ['label' => 'Quantité du produit (*)',
                'help' => 'La quantité du produit est obligatoire (*)',
                'attr' => [
                    'placeholder' => 'Quantité du produit',
                    'class' => ''
                ]
            ])
            ->add('weight', null, ['label' => 'Poids du produit (en Kg)',
                'help' => 'Ce champ est facultatif',
                'attr' => [
                    'placeholder' => 'Poids du produit (en Kg)',
                    'class' => ''
                ]
            ])
            ->add('delaislivraison', null, ['label' => 'Délais de livraison',
                'help' => 'Ce champ est facultatif',
                'attr' => [
                    'placeholder' => 'Délais de livraison',
                    'class' => ''
                ]
            ])
            ->add('garantie', null, ['label' => 'Garantie',
                'help' => 'Ce champ est facultatif',
                'attr' => [
                    'placeholder' => 'Garantie du produit',
                    'class' => ''
                ]
            ])
            ->add('videourl', null, ['label' => 'Lien vidéo YOUTUBE',
                'help' => 'Ce champ est facultatif',
                'attr' => [
                    'placeholder' => 'Lien vidéo YOUTUBE',
                    'class' => ''
                ]
            ])
            ->add('publishedAt', ChoiceType::class, [
                'label' => 'Date de publication',
                'help' => 'Ce champ est facultatif',
                'choices' => [
                    'Aujourd\'hui' => new \DateTime('now'),
                    'Demain' => new \DateTime('+1 day'),
                    'Dans 1 semaine' => new \DateTime('+1 week'),
                    'Dans un mois' => new \DateTime('+1 month'),
                ],
                'preferred_choices' => function ($choice, $key, $value) {
                    // prefer options within 3 days
                    return $choice <= new \DateTime('+3 days');
                },
            ])
            ->add('online', null, ['label' => 'Mettre en ligne?', 'attr' => ['class' => 'switcher-input']])
            ->add('featured', null, ['label' => 'Mettre en vedette?', 'attr' => ['class' => 'switcher-input']])
            ->add('nouveau', null, ['label' => 'Nouveau?', 'attr' => ['class' => 'switcher-input']])
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'label' => 'Catégories',
                'required' => false,
                'placeholder' => 'Aucun parent',
                'multiple' => true,
                'group_by' => 'parent',
                'choice_label' => function($categorie){
                    return $categorie->getName();
                },
                'attr' => [
                    'data-live-search' => 'true',
                ],
            ])
            ->add('metakeywords', EntityType::class, [
                'class' => Metakeywords::class,
                'help' => 'Sélectionnez une ou plusieurs mots clés',
                'label' => 'Mots clés',
                'required' => false,
                'multiple' => true,
                'choice_label' => 'name',
                'attr' => [
                    'data-live-search' => 'true',
                ],
            ])
            ->add('association', EntityType::class, [
                'class' => Products::class,
                'help' => 'Sélectionnez une ou plusieurs produits associés',
                'label' => 'Produits associés',
                'required' => false,
                'placeholder' => 'Aucun produit',
                'multiple' => true,
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
            'data_class' => Products::class,
        ]);
    }
}
