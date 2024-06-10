<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class UserType extends AbstractType
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
               
               
            ],
        ])
        ->add('motdepasse', PasswordType::class, [
            'attr' => ['class' => 'form-control'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir un mot de passe.',
                ]),
                
            ],
        ])
        ->add('password_error_message', HiddenType::class, [
            'mapped' => false,
            'attr' => [
                'id' => 'password-error-message',
                'style' => 'display: none; color: red;'
            ],
            'required' => false,
        ])
        ->add('role', ChoiceType::class, [
            'choices' => [
                'Membre' => 'membre',
                'Membre_Plus' => 'membre_Plus',
                'Journaliste' => 'journaliste',
                'Moderateur' => 'moderateur',
                'Administrateur' => 'Admin'
               
            ],
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'attr' => ['class' => 'form-control'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez sélectionner un rôle.',
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
            'attr' => ['class' => 'form-control', 'maxlength' => 8], // Ajout de l'attribut maxlength
            'constraints' => [
                new Length([
                    'max' => 8, // Limite à 8 caractères
                    'maxMessage' => 'Le numéro de téléphone ne doit pas dépasser {{ limit }} caractères.',
                ]),
            ],
            'required' => false,
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
