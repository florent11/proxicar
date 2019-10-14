<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LogsRepository")
 */
class Logs
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $ann_id;

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
     * @ORM\Column(type="datetime")
     */
    private $log_date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ann_deleted;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ann_moderated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnId(): ?int
    {
        return $this->ann_id;
    }

    public function setAnnId(int $ann_id): self
    {
        $this->ann_id = $ann_id;

        return $this;
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

    public function getLogDate(): ?\DateTimeInterface
    {
        return $this->log_date;
    }

    public function setLogDate(\DateTimeInterface $log_date): self
    {
        $this->log_date = $log_date;

        return $this;
    }

    public function getAnnDeleted(): ?bool
    {
        return $this->ann_deleted;
    }

    public function setAnnDeleted(bool $ann_deleted): self
    {
        $this->ann_deleted = $ann_deleted;

        return $this;
    }

    public function getAnnModerated(): ?bool
    {
        return $this->ann_moderated;
    }

    public function setAnnModerated(bool $ann_moderated): self
    {
        $this->ann_moderated = $ann_moderated;

        return $this;
    }
}
