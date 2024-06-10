<?php

namespace App\Form;

use App\Entity\Rencontre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RencontreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('daterencontre')
            ->add('adversaire')
            ->add('score')
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rencontre::class,
        ]);
    }
}