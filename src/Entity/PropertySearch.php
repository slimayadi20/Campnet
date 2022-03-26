<?php
namespace App\Entity ;

class PropertySearch
{

    private $lieu;

    public function getlieu():?string
    {

        return $this->lieu;
    }

    public function setlieu(string $lieu):self{

        $this->lieu = $lieu;
        return $this ;
    }
}