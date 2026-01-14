<?php

namespace App\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Type;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;

use App\Validator\Constraints\NoBadWords;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('date', DateType::class, [
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'constraints' => [
                new NotBlank(['message' => 'La date est obligatoire']),
                new Type([
                    'type' => \DateTimeInterface::class,
                    'message' => 'Veuillez saisir une date valide',
                ]),
            ],
        ])
        ->add('description', TextType::class, [
            'attr' => [
                'placeholder' => 'Description',
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir une description. '
                ]), new NoBadWords([
                    'message' => 'La description contient des mots inappropriés : "{{ value }}"',
                ]),
            ],
        ])
            ->add('typerec',ChoiceType::class,[
                'choices'=>[ 
                'facturation'=>'facturation',
                'qualite_nourriture'=>'qualite_nourriture',
                'service'=>'service'
                   ],
                   'placeholder' => 'Séléctionner type',
                   'constraints' => [
                       new NotBlank([
                           'message' => 'Veuiller choisir le type'
                       ])

                   ]] )
            ->add('etatrec',ChoiceType::class,[
                'choices'=>[ 
                'en_attente'=>'en_attente',
                'en_cours'=>'en_cours',
                'resolue'=>'resolue'
                   ],
                   'placeholder' => 'Séléctionner type',
                   'constraints' => [
                       new NotBlank([
                           'message' => 'Veuiller choisir l etat'
                       ])
                   ]] )
            
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
