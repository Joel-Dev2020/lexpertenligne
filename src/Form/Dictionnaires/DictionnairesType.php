<?php

namespace App\Form\Dictionnaires;

use App\Entity\Dictionnaires\Dictionnaires;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DictionnairesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lexique', null, [
                'label' => 'Lexique ou mot clé',
                'attr' => [
                    'placeholder' => 'Lexique ou mot clé',
                ]
            ])
            ->add('definition', TextareaType::class, [
                'label' => 'Définition',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Définition ou description du lexique',
                    'class' => 'ckeditor', 'rows' => 7
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dictionnaires::class,
        ]);
    }
}
