<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Je m\'appelle',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('entreprise', TextType::class, [
                'label' => 'De l\'entreprise',
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Mon adresse mail',
                'constraints' => [
                    new NotBlank(),
                ]
            ])            
            ->add('service', ChoiceType::class, [
                'label' => 'Service',
                'choices' => [
                    'Audiovisuel' =>  'audiovisuel',
                    'Photographie' => 'photographie',
                    'Digital' => 'digital',
                    'Formation' => 'formation',
                    'Recrutement' => 'Recrutement',
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('objet', TextType::class, [
                'label' => 'Objet de ma demande',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            
            ->add('message', TextareaType::class, [
                'label' => 'Ma demande'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'J\'envoie',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
