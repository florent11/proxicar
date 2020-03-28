<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImagesRepository")
 */
class Images
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image_name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Annonces", inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ann_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageName(): ?string
    {
        return $this->image_name;
    }

    public function setImageName(string $image_name): self
    {
        $this->image_name = $image_name;

        return $this;
    }

    public function getAnnId(): ?Annonces
    {
        return $this->ann_id;
    }

    public function setAnnId(?Annonces $ann_id): self
    {
        $this->ann_id = $ann_id;

        return $this;
    }
}
