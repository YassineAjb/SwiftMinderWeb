<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre',null,['attr' => [
                'class' => 'form-control',
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir une adresse e-mail.',
                ]),
                new Length([
                    'min' => 8,
                    'minMessage' => 'Le titre doit comporter au moins {{ limit }} caractères.',
                    // Optionnel : ajoutez une contrainte de longueur maximale si nécessaire
                    'max' => 130,
                    'maxMessage' => 'Le titre  ne peut pas dépasser {{ limit }} caractères.'
                ]),
              
            ],
            ])
            ->add('description',null,['attr' => [
                'class' => 'form-control',
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir une adresse e-mail.',
                ]),
                new Length([
                    'min' => 10,
                    'minMessage' => 'Le titre doit comporter au moins {{ limit }} caractères.',
                    // Optionnel : ajoutez une contrainte de longueur maximale si nécessaire
                    'max' => 130,
                    'maxMessage' => 'Le titre  ne peut pas dépasser {{ limit }} caractères.'
                ]),
              
            ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
