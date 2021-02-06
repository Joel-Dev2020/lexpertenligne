<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Parametres;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ParametresType extends AbstractType
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
            ->add('name', null, ['label' => 'Dénomination', 'attr' => [
                'placeholder' => 'Nom de la structure',
            ]])
            ->add('telephone', null, ['label' => 'Téléphone', 'attr' => [
                'placeholder' => 'Numéro de téléphone',
                'class' => 'form-control mb-1'
            ]])
            ->add('cellulaire', null, ['label' => 'Cellulaire', 'attr' => [
                'placeholder' => 'Numéro de cellulaire',
                'class' => 'form-control mb-1'
            ]])
            ->add('email', TextType::class, ['label' => 'Email', 'attr' => [
                'placeholder' => ' @ .',
                'class' => 'mb-1 text-mask-email',
            ]])
            ->add('activite', null, ['label' => 'Activité', 'attr' => [
                'placeholder' => 'Domaines d\'activité',
            ]])
            ->add('slogan', null, ['label' => 'Slogan', 'attr' => [
                'placeholder' => 'Slogan',
            ]])
            ->add('facebook', null, [
                'label' => 'Facebook',
                'help' => 'Lien de la page facebook (Facultatif)',
                'attr' => [
                'placeholder' => 'Lien facebook',
            ]])
            ->add('instagram', null, [
                'label' => 'Instagram',
                'help' => 'Lien de la page instagram (Facultatif)',
                'attr' => [
                'placeholder' => 'Lien instagram',
            ]])
            ->add('twitter', null, [
                'label' => 'Twitter',
                'help' => 'Lien de la page twitter (Facultatif)',
                'attr' => [
                    'placeholder' => 'Lien twitter',
                ]])
            ->add('tva', null, [
                'label' => 'Valeur de la tva (%)',
                'help' => 'Global sur tous les produits du site',
                'attr' => [
                    'placeholder' => 'Valeur de la tva',
                ]])
            ->add('seuilproduct', null, [
                'label' => 'Seuil quantité produit',
                'help' => 'La quantité seuil minimum des produits',
                'attr' => [
                    'placeholder' => 'Seuil quantité produit',
                ]])
            ->add('description', CKEditorType::class, [
                'label' => 'Aprops de la structure',
                'required' => false,
                'attr' => ['rows' => 8]
            ])
            ->add('keywords', TextareaType::class, [
                'label' => 'Mots clés',
                'required' => false,
                'attr' => ['rows' => 5]
            ])
            ->add('adresses', CKEditorType::class, [
                'label' => 'Adresses',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Adresses et situation géographique',
                    'rows' => 5
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Favicon',
                'help' => 'Votre logo doit faire 100 x 100 au maximum',
                'required' => false,
                'asset_helper' => true,
                'image_uri' => static function (Parametres $parametres, $resolvedUri) use($cacheManager) {
                    // $cacheManager is LiipImagine cache manager
                    if($resolvedUri){
                        return $cacheManager->getBrowserPath(
                            $resolvedUri,
                            'favicon',
                            ['thumbnail' => ['size' => [100, 100]]]
                        );
                    }
                },
                'attr' => [
                    'class' => 'form-control file-styled'
                ],
            ])
            ->add('imageFile2', VichImageType::class, [
                'label' => 'Logo noir',
                'help' => 'Votre logo doit faire 100 x 50 au maximum',
                'required' => false,
                'asset_helper' => true,
                'image_uri' => static function (Parametres $parametres, $resolvedUri) use($cacheManager) {
                    // $cacheManager is LiipImagine cache manager
                    if($resolvedUri){
                        return $cacheManager->getBrowserPath(
                            $resolvedUri,
                            'logo',
                            ['thumbnail' => ['size' => [300, 110]]]
                        );
                    }
                },
                'attr' => [
                    'class' => 'form-control file-styled'
                ],
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Parametres::class,
        ]);
    }
}
