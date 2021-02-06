<?php

namespace App\Form;

use App\Entity\User;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserComptesType extends AbstractType
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
            ->add('username', null, ['label' => 'Nom utilisateur', 'attr' => [
                'placeholder' => 'Nom utilisateur',
                'class' => 'form-control'
            ]])
            ->add('nom', null, ['label' => 'Nom','help' => 'Le nom saisi doit comporter au minimum 3 et maximum 15 cataères', 'attr' => [
                'placeholder' => 'Nom',
                'class' => 'form-control'
            ]])
            ->add('prenoms', null, ['label' => 'Prenoms', 'help' => 'Le prenom saisi doit comporter au minimum 3 et maximum 100 cataères', 'attr' => [
                'placeholder' => 'Prenoms',
                'class' => 'form-control'
            ]])
            ->add('email', null, ['label' => 'Email de l\'utilisateur', 'attr' => [
                'placeholder' => ' @ .',
                'class' => 'form-control'
            ]])
            ->add('address', null, ['label' => 'Adresse', 'attr' => [
                'placeholder' => 'Adresse',
                'class' => 'form-control'
            ]])
            ->add('contacts', null, ['label' => 'Numéro de téléphone', 'attr' => [
                'placeholder' => 'Numéro de téléphone',
                'class' => 'form-control'
            ]])
            ->add('roles', ChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'choices' => $this->getChoices(),
                'label' => 'Rôle',
                'required' => false,
                'attr' => ['class' => ''],
            ])
            ->add('enabled', null, ['label' => 'Activer le compte?', 'attr' => [
                'class' => ''
            ]])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image',
                'required' => false,
                'asset_helper' => true,
                'image_uri' => static function (User $user, $resolvedUri) use($cacheManager) {
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
                    'class' => 'custom-file-input'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    private function getChoices(){
        $choices = User::ROLES;
        $output = [];
        foreach ($choices as $k => $v){
            $output[$v] = $k;
        }
        return $output;
    }
}
