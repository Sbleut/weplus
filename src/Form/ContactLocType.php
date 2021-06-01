<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
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
                    new Length(),
                ]
            ])
            ->add('adresse', TextType::class)
            ->add('start', DateType::class, [
                'widget' => 'choice',
                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'start'],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('hstart', ChoiceType::class, [
                'matin' => 'matin',
                'après-midi' => 'après-midi',
            ])
            ->add('end', DateType::class, [
                'widget' => 'choice',
                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'end'],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('hend', ChoiceType::class, [
                'matin' => 'matin',
                'après-midi' => 'après-midi',
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
