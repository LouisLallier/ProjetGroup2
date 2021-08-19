<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            -> add('name', TextType::class,[
                'required' => false,
                "label"=>false,
                'attr' => [
                    'placeholder' => 'Name'
                ]
            ])

            -> add('firstname', TextType::class,[
                'required' => false,
                "label"=>false,
                'attr' => [
                    'placeholder' => 'Prénom'
                ]
            ])


            ->add('email', EmailType::class ,[
                "required"=>false,
                "label"=>false,
                "attr"=>[
                    "placeholder"=>"Veuillez saisir votre email"
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type'=> PasswordType::class,
                'invalid_message' => "Non c'est raté !",
                'required' => true,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Met ton mdp',
                    ]
                ],
                'second_options' => [
                    'label' => ' Confirmer le Mot de passe',
                    'attr' => [
                        'placeholder' => 'Remet ton mot de passe',
                    ]
                ],
                'mapped' => false,

                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add("Valider", SubmitType::class)

            ->add('adress',TextType::class  ,[
                "required"=>false,
                "label"=>false,
                "attr"=>[
                    "placeholder"=>"Veuillez saisir votre adresse"
                ]
            ])
            ->add('cp',TextType::class  ,[
                "required"=>false,
                "label"=>false,
                "attr"=>[
                    "placeholder"=>"Veuillez saisir votre code postal"
                ]
            ])
            ->add('city',TextType::class  ,[
                "required"=>false,
                "label"=>false,
                "attr"=>[
                    "placeholder"=>"Veuillez saisir votre ville de résidence"
                ]
            ])
            ->add('phone',TextType::class  ,[
                "required"=>false,
                "label"=>false,
                "attr"=>[
                    "placeholder"=>"Veuillez saisir votre numéro de téléphone"
                ]
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,

        ]);
    }
}

