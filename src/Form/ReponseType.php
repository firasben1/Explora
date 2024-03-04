<?php

namespace App\Form;

use App\Entity\Reponse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType; // Correct import statement
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            
           
            ->add('reponse', TextareaType::class)
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'En cours' => 'En cours',
                    'Approuvée' => 'Approuvée',
                    'Refusée' => 'Refusée',
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-primary btn-rounded'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,
            'idReclamation' => null,
        ]);
    }
}