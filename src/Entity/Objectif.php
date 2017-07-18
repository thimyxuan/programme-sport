<?php

namespace Entity;

class Objectif 
{
    /**
     *
     * @var int 
     */
    private $id;
    
    /**
     *
     * @var string 
     */
    private $titre;
    
    
    // ------------ Getter --------------
    
    /**
     * 
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * 
     * @return string
     */
    public function getTitre() {
        return $this->titre;
    }

    
    // ------------ Setter --------------    
    
    /**
     * 
     * @param int $id
     * @return Objectif
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * 
     * @param string $titre
     * @return Objectif
     */
    public function setTitre($titre) {
        $this->titre = $titre;
        return $this;
    }
    
    
    public function __toString() 
    {
        return $this->titre;
    }
    // Note : la méthode magique __tostring() permet de convertir un objet en chaine de caractères lorsque je fais un echo d'un objet de la classe Objectif
  
}
