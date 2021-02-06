<?php

namespace App\Form\Blogs;

use App\Entity\Blogs\Categoriesblogs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoriesblogsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Nom de la catégorie', 'attr' => [
                'placeholder' => 'Nom de la catégorie',
                'class' => ''
            ]])
            ->add('ordre', null, ['label' => 'Numéro d\'ordre', 'attr' => [
                'placeholder' => 'Numéro d\'ordre',
                'class' => '',
                'min' => '0',
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Categoriesblogs::class,
        ]);
    }
}
