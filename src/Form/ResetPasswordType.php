<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email',EmailType::class, [
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
                ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
