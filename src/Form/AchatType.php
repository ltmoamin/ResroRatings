<?php

namespace App\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Achat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class AchatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montanttotal')
            ->add('quantite')
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('type',ChoiceType::class,[
                'choices'=>[ 
                'livraison'=>'livraison',
                'surplace'=>'surplace'
                   ]] )
            ->add('user', EntityType::class, [
                'class' => 'App\Entity\User',
                'choice_label' => 'username',
            ])
        
            ->add('plat',EntityType::class, [
                'class' => 'App\Entity\Plat',
                'choice_label' => 'nom',
            ])
            ;
    }
   

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Achat::class,
        ]);
    }
}
