<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pubavis', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Publication Avis should not be blank.']),
                ],
            ])
            ->add('titreavis', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Titre Avis should not be blank.']),
                    new Length([
                        'max' => 12,
                        'maxMessage' => 'Titre Avis should not exceed {{ limit }} characters.',
                    ]),
                ],
            ])
            // ->add('user', EntityType::class, [
            //     'class' => 'App\Entity\User',
            //     'choice_label' => 'username',
            // ])
            ->add('restaurant', EntityType::class, [
                'class' => 'App\Entity\Restaurant',
                'choice_label' => 'nom',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}
