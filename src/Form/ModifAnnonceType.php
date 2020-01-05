<?php

namespace App\Form;

use App\Entity\Annonces;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ModifAnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ann_titre', TextType::class, [
                'label' => 'Titre de l\'annonce',
                'attr' => ['placeholder' => 'Titre', 'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez écrire un titre pour votre annonce.',
                    ]),
                ]
            ])
            ->add('marque', TextType::class, [
                'label' => 'Marque',
                'attr' => ['placeholder' => 'Marque du véhicule', 'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez écrire la marque de votre véhicule.',
                    ]),
                ]
            ])
            ->add('modele', TextType::class, [
                'label' => 'Modèle',
                'attr' => ['placeholder' => 'Modèle du véhicule', 'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez écrire le modèle de votre véhicule.',
                    ]),
                ]
            ])
            ->add('annee_modele', TextType::class, [
                'label' => 'Année-Modèle',
                'attr' => ['placeholder' => 'Année du véhicule', 'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Indiquez l\'Année de votre véhicule.',
                    ]),
                ]
            ])
            ->add('kilometre', TextType::class, [
                'label' => 'Kilométrage',
                'attr' => ['placeholder' => 'Kilométrage du véhicule', 'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le kilométrage de votre véhicule.',
                    ]),
                ]
            ])
            ->add('carburant', TextType::class, [
                'label' => 'Carburant',
                'attr' => ['placeholder' => 'Carburant', 'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Indiquez si votre véhicule est Essence ou Diesel.',
                    ]),
                ]
            ])
            ->add('boite_de_vitesse', TextType::class, [
                'label' => 'Boite de vitesse',
                'attr' => ['placeholder' => 'Boite de vitesse', 'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Indiquez si la boite de vitesse est Manuelle ou Automatique.',
                    ]),
                ]
            ])
            ->add('ann_contenu', CKEditorType::class, [
                'label' => 'Texte de l\'annonce',
                'attr' => ['placeholder' => 'Texte de l\'annonce', 'class' => 'form-control editor'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez écrire un texte pour votre annonce.',
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonces::class,
        ]);
    }
}
