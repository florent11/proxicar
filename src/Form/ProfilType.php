<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'Votre adresse email', 'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez saisir une adresse email.'
                    ]),
                    new Email([
                        'message'=> 'Veuillez saisir une adresse email valide.'
                    ])
                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom complet',
                'attr' => ['placeholder' => 'Nom/Prénom', 'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez écrire votre nom complet.',
                    ]),
                ]
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'attr' => ['placeholder' => 'Votre pseudonyme', 'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un pseudonyme.',
                    ]),
                ]
            ])
            ->add('num_tel', TextType::class, [
                'label' => 'Téléphone',
                'attr' => ['placeholder' => 'Numéro de téléphone', 'class' => 'form-control'
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}