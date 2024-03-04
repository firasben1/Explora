<?php

namespace App\Form;

use App\Entity\Evenement;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Nom', null, [
            'constraints' => [
                new NotBlank(),
            ],
        ])
        ->add('Description', null, [
            'constraints' => [
                new NotBlank(),
            ],
        ])
        
        ->add('Date', DateType::class, [
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd', // specify the desired format
        ])

        
        ->add('Lieu', null, [
            'constraints' => [
                new NotBlank(),
            ],
        ])
        ->add('NbrParticipant', null, [
            'constraints' => [
                new NotBlank(),
                new PositiveOrZero(),
            ],
        ])
        ->add('Prix', null, [
            'constraints' => [
                new NotBlank(),
                new PositiveOrZero(),
            ],
        ])
        ->add('imageFile', FileType::class)
        ->add('imageFile', vichImageType::class, [
            'label' => 'Image (JPG or PNG)',
            'required' => true,
            'allow_delete' => true,
            'download_uri' => false,

        ])  
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}