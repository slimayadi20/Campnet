<?php

namespace App\Entity;

use App\Repository\CentreCampRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use App\Form\PropertySearchType;
/**
 * @ORM\Entity(repositoryClass=CentreCampRepository::class)
 */
class CentreCamp
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_centre;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $Description_centre;

    /**
     *
     * @ORM\Column(type="string", length=255)
     *
     */
    private $img_centre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieux;

    /**
     * @ORM\Column(type="string", length=8)
     *  min = 8,
     *  max = 12,
     */
    private $tlf_centre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *  message = "The email '{{ value }}' is not a valid email.")
     *
     */
    private $mail_centre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mdps_centre;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="key_reserv")
     */
    private $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCentre(): ?string
    {
        return $this->nom_centre;
    }

    public function setNomCentre(string $nom_centre): self
    {
        $this->nom_centre = $nom_centre;

        return $this;
    }

    public function getDescriptionCentre(): ?string
    {
        return $this->Description_centre;
    }

    public function setDescriptionCentre(string $Description_centre): self
    {
        $this->Description_centre = $Description_centre;

        return $this;
    }

    public function getImgCentre()
    {
        return $this->img_centre;
    }

    public function setImgCentre($img_centre)
    {
        $this->img_centre = $img_centre;

        return $this;
    }

    public function getLieux(): ?string
    {
        return $this->lieux;
    }

    public function setLieux(string $lieux): self
    {
        $this->lieux = $lieux;

        return $this;
    }

    public function getTlfCentre(): ?string
    {
        return $this->tlf_centre;
    }

    public function setTlfCentre(string $tlf_centre): self
    {
        $this->tlf_centre = $tlf_centre;

        return $this;
    }

    public function getMailCentre(): ?string
    {
        return $this->mail_centre;
    }

    public function setMailCentre(string $mail_centre): self
    {
        $this->mail_centre = $mail_centre;

        return $this;
    }

    public function getMdpsCentre(): ?string
    {
        return $this->mdps_centre;
    }

    public function setMdpsCentre(string $mdps_centre): self
    {
        $this->mdps_centre = $mdps_centre;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setKeyReserv($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getKeyReserv() === $this) {
                $reservation->setKeyReserv(null);
            }
        }

        return $this;
    }
}
