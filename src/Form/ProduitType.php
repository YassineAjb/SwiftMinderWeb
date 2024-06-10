<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomProduit', TextType::class, [
                'label' => 'Nom du produit',
            ])
            ->add('prixProduit', IntegerType::class, [
                'label' => 'Prix du produit',
            ])
            ->add('tailleProduit', TextType::class, [
                'label' => 'Taille du produit',
            ])
           
            ->add('QauntiteProduit', TextType::class, [
                'label' => 'Quantite du produit',
            ])
            ->add('category', EntityType::class, [
                'class' => 'App\Entity\Category',
                'choice_label' => 'titre',
            ])
            ->add('image', FileType::class, [
                'label' => false,
                'mapped' => false,
                'required' => false, 
            ])
            ->add('ajouter', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
            
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
