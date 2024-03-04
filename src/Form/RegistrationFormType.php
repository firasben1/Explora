<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Regex;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\FileType;





class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'constraints' => [
                 new NotBlank([
                    'message' => 'Please enter your email address',
                ]),
                new Email([
                    'message' => 'Please enter a valid email address',
                ]),
            ],
        ])
        ->add('nom', TextType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter your name',
                ]),
                new Length([
                    'min' => 3,
                    'minMessage' => 'Your name should be at least {{ limit }} characters',
                    'max' => 50,
                    'maxMessage' => 'Your name cannot be longer than {{ limit }} characters',
                ]),
            ],
        ])
        ->add('prenom', TextType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter your first name',
                ]),
                new Length([
                    'min' => 3,
                    'minMessage' => 'Your first name should be at least {{ limit }} characters',
                    'max' => 50,
                    'maxMessage' => 'Your first name cannot be longer than {{ limit }} characters',
                ]),
            ],
        ])
        ->add('agreeTerms', CheckboxType::class, [
            'mapped' => false,
            'constraints' => [
                new IsTrue([
                    'message' => 'You should agree to our terms.',
                ]),
            ],
        ])
        ->add('plainPassword', PasswordType::class, [
            'mapped' => false,
            'attr' => ['autocomplete' => 'new-password', 'class' => 'password-field'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Your password should be at least {{ limit }} characters',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
                new Regex([
                    'pattern' => '/[\W_]+/', // Ensures at least one symbol is included
                    'message' => 'Your password must contain at least one symbol',
                ]),
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
            'data_class' => User::class,
        ]);
    }

    
}