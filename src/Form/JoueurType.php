<?php

namespace App\Form;

use App\Entity\Joueur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Url;

class JoueurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'required' => false,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Âge',
                'required' => false,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('position', ChoiceType::class, [
                'label' => 'Position',
                'choices' => [
                    'GK' => 'GK',
                    'DC' => 'DC',
                    'AL' => 'AL',
                    'MD' => 'MD',
                    'MC' => 'MC',
                    'MO' => 'MO',
                    'AD' => 'AD',
                    'AG' => 'AG',
                    'AP' => 'AP',
                    'SA' => 'SA',
                ],
                'placeholder' => 'Select Position', 
                'expanded' => false, 
                'multiple' => false, 
                'required' => false,
                'constraints' => [
                    new NotBlank(),
                    new Choice([
                        'choices' => ['GK', 'DC', 'AL', 'MD', 'MC', 'MO', 'AD', 'AG', 'AP', 'SA'],
                        'message' => 'Veuillez sélectionner une position valide.',
                    ]),
                ],
            ])
            ->add('hauteur', IntegerType::class, [
                'label' => 'Hauteur',
                'required' => false,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('poids', IntegerType::class, [
                'label' => 'Poids',
                'required' => false,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('piedfort', ChoiceType::class, [
                'label' => 'Pied fort',
                'expanded' => true,
                'choices' => [
                    'Pied Gauche' => 'Gauche',
                    'Pied Droite' => 'Droite',
                ],
                'required' => false,
                'placeholder' => false,
                'constraints' => [
                    new NotBlank(),
                    new Choice([
                        'choices' => ['Gauche', 'Droite'],
                        'message' => 'Veuillez sélectionner un pied fort valide.',
                    ]),
                ],
            ])
            ->add('imagepath', FileType::class, [
                'label' => false,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (jpeg, png).',
                    ]),
                ],
            ])
             ->add('link', TextType::class, [
                'label' => 'Lien',
                'required' => false,
                'constraints' => [
                    new Url([
                        'message' => 'Veuillez entrer une URL valide.',
                    ]),
                ],
            ])
            ->add('shirtnum', IntegerType::class, [
                'label' => 'Shirt number',
                'required' => false,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Joueur::class,
            'validation_groups' => ['Default', 'joueur'], // Add validation group
        ]);
    }
}
