<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('Prenom')
            ->add('Mail')
            ->add('Mobile')
            ->add('Adresse')
            
            ->add('Date_prise', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd', // specify the desired format
            ])
            ->add('Date_depot', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',])
            ->add('Num_Cin')
            #->add('Prix')
            ->add('permis_conduite')
            ->add('chauffeur', CheckboxType::class, [
                'label'    => 'Besoin d\'un chauffeur ?',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}