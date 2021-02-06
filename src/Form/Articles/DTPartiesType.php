<?php

namespace App\Form\Articles;

use App\Entity\Articles\DTParties;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DTPartiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Ajouter une partie', 'attr' => [
                'placeholder' => 'Ajouter une partie'
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DTParties::class,
        ]);
    }
}
