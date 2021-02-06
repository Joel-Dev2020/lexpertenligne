<?php

namespace App\Form\Formations;

use App\Entity\Formations\Programformations;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgramformationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Titre du programme', 'attr' => [
                'placeholder' => 'Titre du programme',
                'class' => ''
            ]])
            ->add('ordre', null, ['label' => 'Ordre du programme', 'attr' => [
                'placeholder' => 'Ordre du programme',
                'class' => '',
                'min' => '0',
            ]])
            ->add('duree', TextareaType::class, [
                'label' => 'Durée du programme',
                'required' => false,
                'attr' => [
                    'class' => '',
                    'placeholder' => 'Durée du programme'
                ]
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'Contenu du programme',
                'required' => false,
                'attr' => [
                    'rows' => '4',
                    'placeholder' => 'Contenu du programme'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Programformations::class,
        ]);
    }
}
