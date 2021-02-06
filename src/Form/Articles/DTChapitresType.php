<?php

namespace App\Form\Articles;

use App\Entity\Articles\DTChapitres;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DTChapitresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Ajouter un chapitre', 'attr' => [
                'placeholder' => 'Ajouter un chapitre'
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DTChapitres::class,
        ]);
    }
}
