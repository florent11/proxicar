<?php

namespace App\Form;

use App\Entity\Annonces;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CreerAnnonceType extends AbstractType
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
            ->add('carburant', ChoiceType::class, [ 
                'attr' => ['class' => 'form-control'
                ],
                'placeholder' => 'Sélectionner',
                'choices' => [
                    'Essence' => 'Essence',
                    'Diesel' => 'Diesel',
                    'Electrique' => 'Electrique',
                    'Hybride' => 'Hybride',
                    'GPL' => 'GPL'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Indiquez le type d\'énergie de votre véhicule.',
                    ]),
                ]
            ])
            ->add('boite_de_vitesse', ChoiceType::class, [ 
                'attr' => ['class' => 'form-control'
                ],
                'placeholder' => 'Sélectionner',
                'choices' => [
                    'Manuelle' => 'Manuelle',
                    'Automatique'=> 'Automatique'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Indiquez si la boite de vitesse est Manuelle ou Automatique.',
                    ]),
                ]
            ])
            ->add('ann_prix', TextType::class, [
                'label' => 'Prix',
                'attr' => ['placeholder' => 'Prix de vente', 'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le prix de vente de votre véhicule.',
                    ]),
                ]
            ])
            ->add('cv', TextType::class, [
                'label' => 'Puissance fiscale',
                'attr' => ['placeholder' => 'Puissance fiscale du véhicule', 'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir la puissance fiscale de votre véhicule.',
                    ]),
                ]
            ])
            ->add('region', ChoiceType::class, [ 
                'label' => 'Région',
                'attr' => ['class' => 'form-control'
                ],
                'placeholder' => 'Sélectionner',
                'choices' => [
                    'Auvergne-Rhône-Alpes' => 'Auvergne-Rhône-Alpes',
                    'Bourgogne-Franche-Comté' => 'Bourgogne-Franche-Comté',
                    'Bretagne' => 'Bretagne',
                    'Centre-Val de Loire' => 'Centre-Val de Loire',
                    'Corse' => 'Corse',
                    'Grand Est' => 'Grand Est',
                    'Hauts-de-France' => 'Hauts-de-France',
                    'Île-de-France' => 'Île-de-France',
                    'Normandie' => 'Normandie',
                    'Nouvelle-Aquitaine' => 'Nouvelle-Aquitaine',
                    'Occitanie' => 'Occitanie',
                    'Pays de la Loire' => 'Pays de la Loire',
                    'Provence-Alpes-Côte d\'Azur' => 'Provence-Alpes-Côte d\'Azur',
                    'Outre-Mer' => 'Outre-Mer'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Indiquez votre région.',
                    ]),
                ]
            ])
            ->add('departement', ChoiceType::class, [
                'label' => 'Département', 
                'attr' => ['class' => 'form-control'
                ],
                'placeholder' => 'Sélectionner',
                'choices' => [
                    'Ain (01)' => 'Ain (01)',
                    'Aisne (02)' => 'Aisne (02)',
                    'Allier (03)' => 'Allier (03)',
                    'Alpes-De-Haute-Provence (04)' => 'Alpes-De-Haute-Provence (04)',
                    'Hautes-Alpes (05)' => 'Hautes-Alpes (05)',
                    'Alpes-Maritimes (06)' => 'Alpes-Maritimes (06)',
                    'Ardèche (07)' => 'Ardèche (07)',
                    'Ardennes (08)' => 'Ardennes (08)',
                    'Ariège (09)' => 'Ariège (09)',
                    'Aube (10)' => 'Aube (10)',
                    'Aude (11)' => 'Aude (11)',
                    'Aveyron (12)' => 'Aveyron (12)',
                    'Bouches-Du-Rhône (13)' => 'Bouches-Du-Rhône (13)',
                    'Calvados (14)' => 'Calvados (14)',
                    'Cantal (15)' => 'Cantal (15)',
                    'Charente (16)' => 'Charente (16)',
                    'Charente-Maritime (17)' => 'Charente-Maritime (17)',
                    'Cher (18)' => 'Cher (18)',
                    'Corrèze (19)' => 'Corrèze (19)',
                    'Côte d\'Or (21)' => 'Côte d\'Or (21)',
                    'Côtes-d\'Armor (22)' => 'Côtes-d\'Armor (22)',
                    'Creuse (23)' => 'Creuse (23)',
                    'Dordogne (24)' => 'Dordogne (24)',
                    'Doubs (25)' => 'Doubs (25)',
                    'Drôme (26)' => 'Drôme (26)',
                    'Eure (27)' => 'Eure (27)',
                    'Eure-Et-Loir (28)' => 'Eure-Et-Loir (28)',
                    'Finistère (29)' => 'Finistère (29)',
                    'Corse-Du-Sud (2A)' => 'Corse-Du-Sud (2A)',
                    'Haute-Corse (2B)' => 'Haute-Corse (2B)',
                    'Gard (30)' => 'Gard (30)',
                    'Haute-Garonne (31)' => 'Haute-Garonne (31)',
                    'Gers (32)' => 'Gers (32)',
                    'Gironde (33)' => 'Gironde (33)',
                    'Hérault (34)' => 'Hérault (34)',
                    'Ille-Et-Villaine (35)' => 'Ille-Et-Villaine (35)',
                    'Indre (36)' => 'Indre (36)',
                    'Indre-Et-Loire (37)' => 'Indre-Et-Loire (37)',
                    'Isère (38)' => 'Isère (38)',
                    'Jura (39)' => 'Jura (39)',
                    'Landes (40)' => 'Landes (40)',
                    'Loir-Et-Cher (41)' => 'Loir-Et-Cher (41)',
                    'Loire (42)' => 'Loire (42)',
                    'Haute-Loire (43)' => 'Haute-Loire (43)',
                    'Loire-Atlantique (44)' => 'Loire-Atlantique (44)',
                    'Loiret (45)' => 'Loiret (45)',
                    'Lot (46)' => 'Lot (46)',
                    'Lot-Et-Garonne (47)' => 'Lot-Et-Garonne (47)',
                    'Lozère (48)' => 'Lozère (48)',
                    'Maine-Et-Loire (49)' => 'Maine-Et-Loire (49)',
                    'Manche (50)' => 'Manche (50)',
                    'Marne (51)' => 'Marne (51)',
                    'Haute-Marne (52)' => 'Haute-Marne (52)',
                    'Mayenne (53)' => 'Mayenne (53)',
                    'Meurthe-Et-Moselle (54)' => 'Meurthe-Et-Moselle (54)',
                    'Meuse (55)' => 'Meuse (55)',
                    'Morbihan (56)' => 'Morbihan (56)',
                    'Moselle (57)' => 'Moselle (57)',
                    'Nièvre (58)' => 'Nièvre (58)',
                    'Nord (59)' => 'Nord (59)',
                    'Oise (60)' => 'Oise (60)',
                    'Orne (61)' => 'Orne (61)',
                    'Pas-De-Calais (62)' => 'Pas-De-Calais (62)',
                    'Puy-De-Dôme (63)' => 'Puy-De-Dôme (63)',
                    'Pyrénées-Atlantiques (64)' => 'Pyrénées-Atlantiques (64)',
                    'Hautes-Pyrénées (65)' => 'Hautes-Pyrénées (65)',
                    'Pyrénées-Orientales (66)' => 'Pyrénées-Orientales (66)',
                    'Bas-Rhin (67)' => 'Bas-Rhin (67)',
                    'Haut-Rhin (68)' => 'Haut-Rhin (68)',
                    'Rhône (69)' => 'Rhône (69)',
                    'Haute-Saône (70)' => 'Haute-Saône (70)',
                    'Saône-Et-Loire (71)' => 'Saône-Et-Loire (71)',
                    'Sarthe (72)' => 'Sarthe (72)',
                    'Savoie (73)' => 'Savoie (73)',
                    'Haute-Savoie (74)' => 'Haute-Savoie (74)',
                    'Paris (75)' => 'Paris (75)',
                    'Seine-Maritime (76)' => 'Seine-Maritime (76)',
                    'Seine-Et-Marne (77)' => 'Seine-Et-Marne (77)',
                    'Yvelines (78)' => 'Yvelines (78)',
                    'Deux-Sèvres (79)' => 'Deux-Sèvres (79)',
                    'Somme (80)' => 'Somme (80)',
                    'Tarn (81)' => 'Tarn (81)',
                    'Tarn-Et-Garonne (82)' => 'Tarn-Et-Garonne (82)',
                    'Var (83)' => 'Var (83)',
                    'Vaucluse (84)' => 'Vaucluse (84)',
                    'Vendée (85)' => 'Vendée (85)',
                    'Vienne (86)' => 'Vienne (86)',
                    'Haute-Vienne (87)' => 'Haute-Vienne (87)',
                    'Vosges (88)' => 'Vosges (88)',
                    'Yonne (89)' => 'Yonne (89)',
                    'Territoire-De-Belfort (90)' => 'Territoire-De-Belfort (90)',
                    'Essonne (91)' => 'Essonne (91)',
                    'Hauts-De-Seine (92)' => 'Hauts-De-Seine (92)',
                    'Seine-Saint-Denis (93)' => 'Seine-Saint-Denis (93)',
                    'Val-De-Marne (94)' => 'Val-De-Marne (94)',
                    'Val-d\'Oise (95)' => 'Val-d\'Oise (95)',
                    'Guadeloupe (971)' => 'Guadeloupe (971)',
                    'Martinique (972)' => 'Martinique (972)',
                    'Guyane (973)' => 'Guyane (973)',
                    'La Réunion (974)' => 'La Réunion (974)',
                    'Mayotte (976)' => 'Mayotte (976)'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Indiquez votre département.',
                    ]),
                ]
            ])
            ->add('ville', TextType::class, [ 
                'label' => 'Ville',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Votre ville'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Indiquez votre ville.',
                    ]),
                ]
            ])
            ->add('images', FileType::class, [
                'label' => 'Photos (8 photos maxi)',
                'multiple' => true,
                'mapped' => false,
                'attr' => ['accept' => "image/jpeg, image/png"
                ],
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