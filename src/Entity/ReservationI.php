<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationIRepository::class)
 */
class ReservationI
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
    private $nbr_p;

    /**
     * @ORM\Column(type="date")
     */
    private $Date;

    /**
     * @ORM\Column(type="date")
     */
    private $date_fin;

    /**
     * @ORM\ManyToOne(targetEntity=CentreCamp::class, inversedBy="reservations")
     */
    private $key_reserv;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrP(): ?int
    {
        return $this->nbr_p;
    }

    public function setNbrP(int $nbr_p): self
    {
        $this->nbr_p = $nbr_p;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getKeyReserv(): ?CentreCamp
    {
        return $this->key_reserv;
    }

    public function setKeyReserv(?CentreCamp $key_reserv): self
    {
        $this->key_reserv = $key_reserv;

        return $this;
    }
}
