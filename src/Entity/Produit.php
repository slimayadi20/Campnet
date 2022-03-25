<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;

use function PHPUnit\Framework\assertNotNull;


/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
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

    private $nom;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commande", mappedBy="id_produit")
     */

    private    $article;

    /**
     * @ORM\Column (type="string");
     */
    private $description;

    /**
     * @ORM\Column (type="string")
     */
    private $disponibilte;
    /**
     * @ORM\Column (type="string");
     */
    private $photo;


    public function __toString()
    {
        return $this->nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $Id): self
    {
        $this->Id = $Id;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;

    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param mixed $article
     */
    public function setArticle($article): void
    {
        $this->article = $article;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDisponibilte()
    {
        return $this->disponibilte;
    }

    /**
     * @param mixed $disponibilte
     */
    public function setDisponibilte($disponibilte): void
    {
        $this->disponibilte = $disponibilte;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo): void
    {
        $this->photo = $photo;
    }


}
