<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Services;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
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
            ->add('image_service', FileType::class, [
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
                    'placeholder' => 'Fichier image inférieur à 5 Mo'
                    ]
            ])
            ->add('text', TextareaType::class, [
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
            ->add('image_service_alt', TextType::class, [
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
            ->add('categorie', EntityType::class, [
                'label' => 'Catégorie',
                'attr' => [],
                'placeholder' => '-- Choisir une catégorie --',
                'class' => Categorie::class,
                'choice_label' => function (Categorie $categorie) {
                    return strtoupper($categorie->getTitle());
                },
                'constraints' => [
                    new NotBlank(),
                ]
 
            ])
            ->add('brochure', FileType::class, [
                'label' => 'brochure',
                'data_class' => null,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'application/pdf',
                        ]
                    
                    ])
                ],
                'attr' => [
                    'class' => 'form-field',
                    'placeholder' => 'Fichier pdf inférieur à 5 Mo'
                    ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter un service',
                'attr' => [
                    'class' => 'btn-success'
                    ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Services::class,
        ]);
    }
}
