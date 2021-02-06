<?php

namespace App\Form\Articles;

use App\Entity\Articles\DTTitres;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DTTitresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Ajouter un titre', 'attr' => [
                'placeholder' => 'Ajouter un titre'
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DTTitres::class,
        ]);
    }
}
