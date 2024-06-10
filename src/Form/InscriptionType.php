<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\EqualTo;
class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'required' => true, // Il ne doit pas être vide
            'attr' => [
                'class' => 'form-control',
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir une adresse e-mail.',
                ]),
                new Email([
                    'message' => 'Veuillez saisir une adresse e-mail valide.',
                ]),
              
            ],
        ])
        ->add('motdepasse', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les mots de passe doivent correspondre.',
            'options' => ['attr' => ['class' => 'form-control']],
            'required' => true,
            'first_options'  => ['label' => 'Mot de passe'],
            'second_options' => ['label' => 'Confirmez le mot de passe'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir un mot de passe.',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Le mot de passe doit comporter au moins {{ limit }} caractères.',
                ]),
            ],
        ])
        ->add('image', FileType::class, [
            'label' => 'Chargez ici une photo',
            'required' => false,
            'mapped' => false,
            'attr' => ['class' => 'form-control-file'],
            'label_attr' => ['class' => 'form-label'],
        ])
        ->add('numtel', null, [
            'attr' => ['class' => 'form-control',],
            'constraints' => [
                new Length([
                    'min' => 8,
                    'minMessage' => 'Le numéro de téléphone doit comporter au moins {{ limit }} caractères.',
                    // Optionnel : ajoutez une contrainte de longueur maximale si nécessaire
                    'max' => 8,
                    'maxMessage' => 'Le numéro de téléphone ne peut pas dépasser {{ limit }} caractères.'
                ]),
              
                ],
            
        ]);
        ;
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
