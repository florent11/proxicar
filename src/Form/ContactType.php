<?php

namespace App\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom complet',
                'attr' => ['placeholder' => 'Nom/Prénom', 'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez écrire votre message.',
                    ]),
                ]
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
            ->add('message', CKEditorType::class, [
                'label' => 'Contenu',
                'attr' => ['placeholder' => 'Votre Message', 'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez écrire votre message.',
                    ]),
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Accepter les conditions de confidentialité',
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
            // Configure your form options here
        ]);
    }
}
