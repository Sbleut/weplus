<?php

namespace App\Form;


use App\Entity\Matos;
use App\Entity\MatosCatego;
use App\Repository\MatosCategoRepository;
use App\Repository\MatosRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class MatosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name_matos', TextType::class, [
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
            ->add('stock', IntegerType::class, [
                'attr' => [
                    'max' => 255
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('prix_ht', IntegerType::class, [
                'attr' => [
                    'max' => 255
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('caution', IntegerType::class, [
                'attr' => [
                    'max' => 255
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('matos_catego', EntityType::class, [
                'label' => 'Catégorie',
                'attr' => [],
                'placeholder' => '-- Choisir une catégorie --',
                'class' => MatosCatego::class,
                'choice_label' => function (MatosCatego $categorie) {
                    return strtoupper($categorie->getName());
                },
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('matos_image', FileType::class, [
                'label' => 'matos_catego_image',
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
                    'placeholder' => 'Fichier justificatif'
                ]
            ])
            ->add('matos_image_alt', TextType::class, [
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
            ->add('accessoires', EntityType::class, [
                'label' => 'accessoires',
                'attr' => [],
                'placeholder' => '-- Choisir un accessoire --',
                'class' => Matos::class,
                'query_builder' => function (EntityRepository $er) {
                     
                    return $er->createQueryBuilder('m')
                    ->where('m.matos_catego = 2');
                     
                }, 
                'choice_label' => function (Matos $matos) {
                    $objectCatego = $matos->getMatosCatego();
                    $categoId = $objectCatego->getId();
                    if($categoId == 2){
                    return strtoupper($matos->getNameMatos());
                    }
                    die;
                },
                'multiple' => true,
                'constraints' => [
                    new NotBlank(),
                ]

            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter un Matos à louer',
                'attr' => [
                    'class' => 'btn-success'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Matos::class,
        ]);
    }
}
