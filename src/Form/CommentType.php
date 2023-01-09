<?php

namespace App\Form;

use App\Entity\Comment;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add(
            'body',
            TextareaType::class
        )->add('list_files', FileType::class, [
            'multiple' => true,
            'mapped' => false,
            'required' => false,
            'attr'     => [
                'accept' => 'files/*',
                'multiple' => 'multiple',
                'data_class' => null
            ]
        ])->add('save', SubmitType::class, ['label' => 'Comment']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
