<?php

namespace App\Form;

use App\Entity\Causes;
use App\Entity\Entreprises;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameEntreprise', TextType::class, [
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
            ->add('lienEntreprise', TextType::class, [
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
            ->add('logo', FileType::class, [
                'label' => 'logo de l\'entreprise',
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
            ->add('logo_alt', TextType::class, [
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
            'data_class' => Entreprises::class,
        ]);
    }
}
