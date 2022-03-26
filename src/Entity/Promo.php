<?php

namespace App\Entity;

use App\Repository\PromoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=PromoRepository::class)
 */
class Promo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")

     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Groups("post:read")

     */
    private $Nom_promo;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     *
     */
    private $nv_prix;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")

     */
    private $Description_promo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPromo(): ?string
    {
        return $this->Nom_promo;
    }

    public function setNomPromo(string $Nom_promo): self
    {
        $this->Nom_promo = $Nom_promo;

        return $this;
    }

    public function getNvPrix(): ?string
    {
        return $this->nv_prix;
    }

    public function setNvPrix(string $nv_prix): self
    {
        $this->nv_prix = $nv_prix;

        return $this;
    }

    public function getDescriptionPromo(): ?string
    {
        return $this->Description_promo;
    }

    public function setDescriptionPromo(string $Description_promo): self
    {
        $this->Description_promo = $Description_promo;

        return $this;
    }
}
