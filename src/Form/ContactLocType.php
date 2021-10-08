<?php

namespace App\Form;

use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactLocType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('message', TextareaType::class)
            ->add('ville', TextType::class)
            ->add('cp', NumberType::class, [
                'constraints' => [
                    new Length([
                        'max' => 5
                    ]),
                ]
            ])
            ->add('adresse', TextType::class)
            ->add('start', DateType::class, [
                'widget' => 'single_text',
                // adds a class that can be selected in JavaScript
                'attr' => [
                    'class' => 'start',
                    'min' => (new \DateTime())->format('d M Y'),
                ],           
                
                'constraints' => [                               
                    new NotBlank(),
                ]
            ])
            ->add('hstart', ChoiceType::class, [
                'label' => 'heure de retrait du matériel',
                'choices' => [
                    'matin 10h 12h' => 'matin',
                    'après-midi 16h 18h' => 'après-midi',
                ]
            ])
            ->add('end', DateType::class, [
                'widget' => 'single_text',
                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'end'],

                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('hend', ChoiceType::class, [
                'label' => 'heure de retour du matériel',
                'choices' => [
                    'matin 10h 12h' => 'matin',
                    'après-midi 16h 18h' => 'après-midi',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
