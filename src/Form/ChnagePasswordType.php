<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChnagePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
        ])        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
