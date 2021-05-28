<?php

namespace App\Form;

use App\Entity\Associations;
use App\Entity\Causes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AssoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameAsso', TextType::class, [
                'attr' => [ 
                    'maxlength' => '255'
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 255
                    ])
                ]
            ])
            ->add('lienAsso', TextType::class, [
                'attr' => [ 
                    'maxlength' => '255'
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 255
                    ])
                ]
            ])
            ->add('resoAsso', TextType::class, [
                'attr' => [ 
                    'maxlength' => '255'
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 255
                    ])
                ]
            ])
            ->add('textAsso', TextareaType::class, [
                'attr' => [ 
                    'maxlength' => '2048'
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 2048
                    ])
                ]
            ])
            ->add('asso_image', FileType::class, [
                'label' => 'image_service',
                'data_class' => null,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ]
                    
                    ])
                ],
                'attr' => [
                    'class' => 'form-field',
                    ]
            ])
            ->add('asso_image_alt', TextType::class, [
                'attr' => [ 
                    'maxlength' => '255'
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 255
                    ])
                ]
            ])
            ->add('causes', EntityType::class, [
                'label' => 'Causes',
                'attr' => [],
                'placeholder' => '-- Choisir une catégorie --',
                'class' => Causes::class,
                'choice_label' => function (Causes $causes) {
                    return $causes->getNomCause();
                },
                'multiple' => true,                
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter une catégorie',
                'attr' => [
                    'class' => 'btn-success'
                    ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Associations::class,
        ]);
    }
}
