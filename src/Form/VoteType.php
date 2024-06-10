<?php
namespace App\Form;

use App\Entity\Vote;
use App\Entity\Candidat;
use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class VoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vote::class,
        ]);
    }
}

