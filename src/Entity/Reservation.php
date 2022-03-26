<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbr_pers;


    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_r;

    /**
     * @ORM\ManyToOne(targetEntity=Evenement::class, inversedBy="reservations")
     */
    private $Evenement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrPers(): ?int
    {
        return $this->nbr_pers;
    }

    public function setNbrPers(int $nbr_pers): self
    {
        $this->nbr_pers = $nbr_pers;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDateR(): ?\DateTime
    {
        return $this->date_r;
    }

    public function setDateR(\DateTime $date_r): self
    {
        $this->date_r = $date_r;

        return $this;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->Evenement;
    }

    public function setEvenement(?Evenement $Evenement): self
    {
        $this->Evenement = $Evenement;

        return $this;
    }
}
