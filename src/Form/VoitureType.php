<?php

namespace App\Form;

use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
            ->add('Categories',null,['empty_data' =>''])
            ->add('Marque',null,['empty_data' =>''])
            ->add('Couleur',null,['empty_data' =>''])
            ->add('prix',null,['empty_data' =>''])
            ->add('imageName',FileType::class, [
                            'label' => 'Image',
                            'mapped' => false, // This tells Symfony not to try to map this field to any entity property
                            'constraints' => [
                                new File([
                                    'maxSize' => '5M',
                                    'mimeTypes' => [
                                        'image/jpeg',
                                        'image/png',
                                    ],
                                    'mimeTypesMessage' => 'Please upload a valid image file (JPEG, PNG)',
                                ]),
                            ],
                        ])
   
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
    public function buildForm1(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Autres champs...
            ->add('rating', HiddenType::class, [
                'label' => false,
            ]);
    }
    
}

