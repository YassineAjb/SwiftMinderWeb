<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;

class EvenementType extends AbstractType
{
   
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nome', null, [
                'constraints' => [
                    new Assert\Type(['type' => 'alpha']),
                ],
            ])
            ->add('datee', null, [
                'constraints' => [
                    new Assert\GreaterThanOrEqual(['value' => 'today']),
                ],
            ])
            ->add('postee', null, [
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z]+$/',
                        'message' => 'Le postee doit contenir uniquement des lettres.',
                    ]),
                ],
            ])
            ->add('periodep', null, [
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^\d+ ans$/',
                        'message' => 'La periodep doit être un nombre suivi de "ans".',
                    ]),
                ],
            ])
            ->add('imgepath', FileType::class, [
                'label' => 'Profile Picture',
                'mapped' => false,
                'required' => false,
            ]);
    }
    
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
