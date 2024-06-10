<?php

namespace App\Form;

use App\Entity\Candidat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;

class CandidatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomc', null, [
                'constraints' => [
                    new Assert\Type(['type' => 'alpha', 'message' => 'Le nom ne peut contenir que des lettres.']),
                ],
            ])
            ->add('prenomc', null, [
                'constraints' => [
                    new Assert\Type(['type' => 'alpha', 'message' => 'Le prénom ne peut contenir que des lettres.']),
                ],
            ])
            ->add('agec', null, [
                'constraints' => [
                    new Assert\Type(['type' => 'integer', 'message' => 'L\'âge doit être un nombre.']),
                    new Assert\Range(['min' => 25, 'max' => 99, 'minMessage' => 'L\'âge doit être supérieur ou égal à 25.', 'maxMessage' => 'L\'âge doit être inférieur ou égal à 99.']),
                ],
            ])
            ->add('imgcpath', FileType::class, [
                'label' => 'Profile Picture',
                'mapped' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidat::class,
        ]);
    }
}