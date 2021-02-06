<?php

namespace App\Form\Blogs;

use App\Entity\Blogs\Commentairesblogs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentairesblogsType extends AbstractType
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
            'data_class' => Commentairesblogs::class,
        ]);
    }
}
