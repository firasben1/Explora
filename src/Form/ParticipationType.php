<?php

namespace App\Form;

use App\Entity\Participation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ParticipationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'constraints' => [
                new NotBlank(),
                new Email(),
            ],
        ])
        ->add('numero', IntegerType::class, [
            'constraints' => [
                new NotBlank(),
            ],
        ])
        ->add('nom', TextType::class, [
            'constraints' => [
                new NotBlank(),
                new Length(['min' => 2, 'max' => 255]),
            ],
        ])
        ->add('prenom', TextType::class, [
            'constraints' => [
                new NotBlank(),
                new Length(['min' => 2, 'max' => 255]),
            ],
        ])
            ->add('submit',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participation::class,
        ]);
    }
}
