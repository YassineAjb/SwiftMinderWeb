<?php

namespace App\Form;

use App\Entity\Contrat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

class ContratType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('joueur', EntityType::class, [
                'class' => 'App\Entity\Joueur', 
                'choice_label' => 'nom', 
                'required' => false,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('date_debut', DateType::class,[
                'required' => false,
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('date_fin', DateType::class,[
                'required' => false,
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('salaire', MoneyType::class,[
                'required' => false,
                'currency' => 'EUR', 
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contrat::class,
            'validation_groups' => ['Default', 'contrat'],
        ]);
    }
}
