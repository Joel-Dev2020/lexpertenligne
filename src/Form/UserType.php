<?php

namespace App\Form;

use App\Entity\Typessuivis;
use App\Entity\User;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
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
            ->add('nom', null, ['label' => 'Nom', 'required' => false, 'help' => 'Le nom saisi doit comporter au minimum 3 et maximum 15 cataères', 'attr' => [
                'placeholder' => 'Nom',
                'class' => 'form-control'
            ]])
            ->add('prenoms', null, ['label' => 'Prenoms', 'required' => false, 'help' => 'Le prenom saisi doit comporter au minimum 3 et maximum 100 cataères', 'attr' => [
                'placeholder' => 'Prenoms',
                'class' => 'form-control'
            ]])
            ->add('email', null, ['label' => 'Email de l\'utilisateur',
                'help' => 'Rassurez vous que l\'email renseigné soit valide', 'attr' => [
                'placeholder' => 'Email de l\'utilisateur',
                'class' => 'form-control'
            ]])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passes ne correspondent pas.',
                'help' => 'Le mot de passe doit comporter au minimum 6 cataères',
                'options' => ['attr' => ['class' => 'form-control']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe', 'attr' => ['placeholder' => 'Mot de passe']],
                'second_options' => ['label' => 'Confirmer le mot de passe', 'attr' => ['placeholder' => 'Confirmer le mot de passe']],
            ])
            ->add('address', TextareaType::class, ['label' => 'Adresse', 'required' => false, 'attr' => [
                'placeholder' => 'Adresse',
                'class' => 'form-control'
            ]])
            ->add('contacts', null, ['label' => 'Numéro de téléphone', 'required' => false, 'attr' => [
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
            ->add('enabled', null, ['label' => 'Activer le compte?', 'attr' => ['class' => '']])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image',
                'help' => 'Votre photo doit faire 215 x 215 au maximum',
                'required' => false,
                'asset_helper' => true,
                'image_uri' => static function (User $user, $resolvedUri) use($cacheManager) {
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
