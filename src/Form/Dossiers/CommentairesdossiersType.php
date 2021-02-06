<?php

namespace App\Form\Dossiers;

use App\Entity\Dossiers\Commentairesdossiers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentairesdossiersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message', TextareaType::class, [
                'attr' => [
                    'rows' => '3',
                    'placeholder' => 'Laisser votre commentaire ...',
                ],
                'help' => '',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commentairesdossiers::class,
        ]);
    }
}
