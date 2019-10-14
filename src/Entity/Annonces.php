<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnnoncesRepository")
 */
class Annonces
{
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
     * @ORM\Column(type="string", length=50)
     */
    private $ann_auteur;

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
    private $ann_a_valider;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ann_signaler;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ann_moderer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

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

    public function getAnnAuteur(): ?string
    {
        return $this->ann_auteur;
    }

    public function setAnnAuteur(string $ann_auteur): self
    {
        $this->ann_auteur = $ann_auteur;

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

    public function getAnnModerer(): ?bool
    {
        return $this->ann_moderer;
    }

    public function setAnnModerer(bool $ann_moderer): self
    {
        $this->ann_moderer = $ann_moderer;

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
}
