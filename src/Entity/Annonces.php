<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnnoncesRepository")
 * @Vich\Uploadable
 */
class Annonces
{
    /**
     * @Gedmo\Slug(fields={"ann_titre"})
     * @ORM\Column(length=100, unique=true)
     */
    private $slug;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $ann_date;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $ann_titre;

    /**
     * @ORM\Column(type="text")
     */
    private $ann_contenu;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ann_a_valider = true;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ann_signaler = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $marque;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $modele;

    /**
     * @ORM\Column(type="integer")
     */
    private $annee_modele;

    /**
     * @ORM\Column(type="integer")
     */
    private $kilometre;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $carburant;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $boite_de_vitesse;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ann_moderee = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ann_active = true;

    /**
     * @ORM\Column(type="integer")
     */
    private $ann_prix;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $featured_image;

    /**
     * @Vich\UploadableField(mapping="featured_images", fileNameProperty="featured_image")
     * @var File
     */
    private $imageFile;

    public function getSlug()
    {
        return $this->slug;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnDate(): ?\DateTimeInterface
    {
        return $this->ann_date;
    }

    public function setAnnDate(\DateTimeInterface $ann_date): self
    {
        $this->ann_date = $ann_date;

        return $this;
    }

    public function getAnnTitre(): ?string
    {
        return $this->ann_titre;
    }

    public function setAnnTitre(string $ann_titre): self
    {
        $this->ann_titre = $ann_titre;

        return $this;
    }

    public function getAnnContenu(): ?string
    {
        return $this->ann_contenu;
    }

    public function setAnnContenu(string $ann_contenu): self
    {
        $this->ann_contenu = $ann_contenu;

        return $this;
    }

    public function getAnnAValider(): ?bool
    {
        return $this->ann_a_valider;
    }

    public function setAnnAValider(bool $ann_a_valider): self
    {
        $this->ann_a_valider = $ann_a_valider;

        return $this;
    }

    public function getAnnSignaler(): ?bool
    {
        return $this->ann_signaler;
    }

    public function setAnnSignaler(bool $ann_signaler): self
    {
        $this->ann_signaler = $ann_signaler;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getAnneeModele(): ?int
    {
        return $this->annee_modele;
    }

    public function setAnneeModele(int $annee_modele): self
    {
        $this->annee_modele = $annee_modele;

        return $this;
    }

    public function getKilometre(): ?int
    {
        return $this->kilometre;
    }

    public function setKilometre(int $kilometre): self
    {
        $this->kilometre = $kilometre;

        return $this;
    }

    public function getCarburant(): ?string
    {
        return $this->carburant;
    }

    public function setCarburant(string $carburant): self
    {
        $this->carburant = $carburant;

        return $this;
    }

    public function getBoiteDeVitesse(): ?string
    {
        return $this->boite_de_vitesse;
    }

    public function setBoiteDeVitesse(string $boite_de_vitesse): self
    {
        $this->boite_de_vitesse = $boite_de_vitesse;

        return $this;
    }

    public function getAnnModeree(): ?bool
    {
        return $this->ann_moderee;
    }

    public function setAnnModeree(bool $ann_moderee): self
    {
        $this->ann_moderee = $ann_moderee;

        return $this;
    }

    public function getAnnActive(): ?bool
    {
        return $this->ann_active;
    }

    public function setAnnActive(bool $ann_active): self
    {
        $this->ann_active = $ann_active;

        return $this;
    }

    public function getAnnPrix(): ?int
    {
        return $this->ann_prix;
    }

    public function setAnnPrix(int $ann_prix): self
    {
        $this->ann_prix = $ann_prix;

        return $this;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->updated_at = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getFeaturedImage()
    {
        return $this->featured_image;
    }

    public function setFeaturedImage($featured_image)
    {
        $this->featured_image = $featured_image;

        return $this;
    }
}
