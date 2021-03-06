<?php

namespace App\Form;

use App\Entity\Prestation;
use App\Entity\Pro;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrestationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('price')
            ->add('description')
            ->add('dateAvailable')
            ->add('hour')
            ->add('city')

            ->add('pro', EntityType::class, [
                'class' => Pro::class,
                'choice_label' => 'firstname'
            ])

            //->add('dispo')
            ->add('sousservice')
            ->add('Envoyer', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prestation::class,
        ]);
    }
}
