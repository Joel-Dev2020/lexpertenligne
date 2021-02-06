<?php

namespace App\Form;

use App\Entity\Publicites;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PublicitesType extends AbstractType
{
    /**
     * @var CacheManager
     */
    private $cacheManager;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * PublicitesType constructor.
     * @param CacheManager $cacheManager
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(CacheManager $cacheManager, UrlGeneratorInterface $urlGenerator)
    {
        $this->cacheManager = $cacheManager;
        $this->urlGenerator = $urlGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $cacheManager = $this->cacheManager;
        $builder
            ->add('pubblock1', null, ['label' => 'Nom de la pub block 1', 'attr' => [
                'placeholder' => 'Nom de la pub block 1',
            ]])
            ->add('urlpubblock1', null, ['label' => 'Url de la pub block 1', 'attr' => [
                'placeholder' => 'Url de la pub block 1',
                'class' => 'form-control mb-1'
            ]])
            ->add('generateurl1', ChoiceType::class, [
                'choices' => $this->getUrls(),
                'label' => 'Liste des liens',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => ''],
            ])
            ->add('imageFile1', VichImageType::class, [
                'label' => 'Image pu block 1',
                'help' => 'Votre image doit faire 644 x 150 au maximum',
                'required' => false,
                'asset_helper' => true,
                'image_uri' => static function (Publicites $publicites, $resolvedUri) use($cacheManager) {
                    // $cacheManager is LiipImagine cache manager
                    if($resolvedUri){
                        return $cacheManager->getBrowserPath(
                            $resolvedUri,
                            'logo',
                            ['thumbnail' => ['size' => [747, 226]]]
                        );
                    }
                },
                'attr' => [
                    'class' => 'form-control file-styled'
                ],
            ])
            ->add('onlinepub1', null, ['label' => 'Mettre en ligne?', 'attr' => [
                'class' => 'switcher-input'
            ]])
            ->add('pubblock2', null, ['label' => 'Nom de la pub block 2', 'attr' => [
                'placeholder' => 'Nom de la pub block 2',
            ]])
            ->add('urlpubblock2', null, ['label' => 'Url de la pub block 2', 'attr' => [
                'placeholder' => 'Url de la pub block 2',
                'class' => 'form-control mb-1'
            ]])
            ->add('generateurl2', ChoiceType::class, [
                'choices' => $this->getUrls(),
                'label' => 'Liste des liens',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => ''],
            ])
            ->add('imageFile2', VichImageType::class, [
                'label' => 'Image pu block 2',
                'help' => 'Votre image doit faire 644 x 150 au maximum',
                'required' => false,
                'asset_helper' => true,
                'image_uri' => static function (Publicites $publicites, $resolvedUri) use($cacheManager) {
                    // $cacheManager is LiipImagine cache manager
                    if($resolvedUri){
                        return $cacheManager->getBrowserPath(
                            $resolvedUri,
                            'logo',
                            ['thumbnail' => ['size' => [747, 226]]]
                        );
                    }
                },
                'attr' => [
                    'class' => 'form-control file-styled'
                ],
            ])
            ->add('onlinepub2', null, ['label' => 'Mettre en ligne?', 'attr' => [
                'class' => 'switcher-input'
            ]])
            ->add('pubblock3', null, ['label' => 'Nom de la pub banière 3', 'attr' => [
                'placeholder' => 'Nom de la pub banière 3',
            ]])
            ->add('urlpubblock3', null, ['label' => 'Url de la pub banière 1', 'attr' => [
                'placeholder' => 'Url de la pub banière 1',
                'class' => 'form-control mb-1'
            ]])
            ->add('generateurl3', ChoiceType::class, [
                'choices' => $this->getUrls(),
                'label' => 'Liste des liens',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => ''],
            ])
            ->add('imageFile3', VichImageType::class, [
                'label' => 'Image pu block 3',
                'help' => 'Votre image doit faire 1170 x 210 au maximum',
                'required' => false,
                'asset_helper' => true,
                'image_uri' => static function (Publicites $publicites, $resolvedUri) use($cacheManager) {
                    // $cacheManager is LiipImagine cache manager
                    if($resolvedUri){
                        return $cacheManager->getBrowserPath(
                            $resolvedUri,
                            'logo',
                            ['thumbnail' => ['size' => [1170, 210]]]
                        );
                    }
                },
                'attr' => [
                    'class' => 'form-control file-styled'
                ],
            ])
            ->add('onlinepub3', null, ['label' => 'Mettre en ligne?', 'attr' => [
                'class' => 'switcher-input'
            ]])
            ->add('pubblock4', null, ['label' => 'Nom de la pub banière 2', 'attr' => [
                'placeholder' => 'Nom de la pub banière 2',
            ]])
            ->add('urlpubblock4', null, ['label' => 'Url de la pub banière 2', 'attr' => [
                'placeholder' => 'Url de la pub banière 2',
                'class' => 'form-control mb-1'
            ]])
            ->add('generateurl4', ChoiceType::class, [
                'choices' => $this->getUrls(),
                'label' => 'Liste des liens',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => ''],
            ])
            ->add('imageFile4', VichImageType::class, [
                'label' => 'Image pub banière 2',
                'help' => 'Votre image doit faire 403 x 577 au maximum',
                'required' => false,
                'asset_helper' => true,
                'image_uri' => static function (Publicites $publicites, $resolvedUri) use($cacheManager) {
                    // $cacheManager is LiipImagine cache manager
                    if($resolvedUri){
                        return $cacheManager->getBrowserPath(
                            $resolvedUri,
                            'logo',
                            ['thumbnail' => ['size' => [403, 577]]]
                        );
                    }
                },
                'attr' => [
                    'class' => 'form-control file-styled'
                ],
            ])
            ->add('onlinepub4', null, ['label' => 'Mettre en ligne?', 'attr' => [
                'class' => 'switcher-input'
            ]])
            ->add('pubblock5', null, ['label' => 'Nom de la pub banière 3', 'attr' => [
                'placeholder' => 'Nom de la pub banière 3',
            ]])
            ->add('urlpubblock5', null, ['label' => 'Url de la pub banière 3', 'attr' => [
                'placeholder' => 'Url de la pub banière 3',
                'class' => 'form-control mb-1'
            ]])
            ->add('generateurl5', ChoiceType::class, [
                'choices' => $this->getUrls(),
                'label' => 'Liste des liens',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => ''],
            ])
            ->add('imageFile5', VichImageType::class, [
                'label' => 'Image pub banière 3',
                'help' => 'Votre image doit faire 265 x 141 au maximum',
                'required' => false,
                'asset_helper' => true,
                'image_uri' => static function (Publicites $publicites, $resolvedUri) use($cacheManager) {
                    // $cacheManager is LiipImagine cache manager
                    if($resolvedUri){
                        return $cacheManager->getBrowserPath(
                            $resolvedUri,
                            'logo',
                            ['thumbnail' => ['size' => [403, 577]]]
                        );
                    }
                },
                'attr' => [
                    'class' => 'form-control file-styled'
                ],
            ])
            ->add('onlinepub5', null, ['label' => 'Mettre en ligne?', 'attr' => [
                'class' => 'switcher-input'
            ]])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Publicites::class,
        ]);
    }

    private function getUrls(){
        $choices = [
            'Boutique' => $this->urlGenerator->generate('shop.index',[], UrlGenerator::ABSOLUTE_URL),
            'Actualités' => $this->urlGenerator->generate('blogs.index',[], UrlGenerator::ABSOLUTE_URL),
            'Formations' => $this->urlGenerator->generate('formations.index',[], UrlGenerator::ABSOLUTE_URL),
            'Contact' => $this->urlGenerator->generate('contacts',[], UrlGenerator::ABSOLUTE_URL),
        ];
        $output = [];
        foreach ($choices as $k => $v){
            $output[$k] = $v;
        }
        return $output;
    }
}
