<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('accept_form', TextType::class, [
                'label' => 'accept_form',
                'attr' => ['class' => 'no-bot-box'
                ],
                'required' => false,
                'mapped' => false
            ])
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
            ->add('num_tel', TextType::class, [
                'label' => 'Téléphone',
                'attr' => ['placeholder' => 'Numéro de téléphone', 'class' => 'form-control'
                ],
                'required' => false,
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom complet',
                'attr' => ['placeholder' => 'Nom/Prénom', 'class' => 'form-control'
                ],
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'attr' => ['placeholder' => 'Votre pseudonyme', 'class' => 'form-control'
                ],
            ])
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array('label' => 'Mot de passe', 'attr' => ['class' =>'form-control myPassword', 'placeholder' => 'Votre mot de passe', 'type' => 'password']),
                'second_options' => array('label' => 'Confirmer le mot de passe', 'attr' => ['class' =>'form-control', 'placeholder' => 'Ressaisissez le mot de passe']),
                'invalid_message' => 'Les 2 mots de passe ne sont pas identiques.',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez votre mots de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ))
            ->add('rgpd_cochee', CheckboxType::class, [
                'label' => 'En m\'inscrivant à ce site, j\'accepte le stockage de mes données personnelles sans limite de durée jusqu\'à désinscription volontaire. Ces données sont destinées au traitement de l\'inscription et ne seront pas transmises à des tiers.',
                'label_attr' => ['class' => 'custom-control-label'
                ],
                'attr' => ['class' => 'custom-control-input'
                ],
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devriez accepter nos conditions.',
                    ]),
                ],
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
