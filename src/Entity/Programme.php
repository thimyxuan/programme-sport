<?php

namespace Entity;

use DateTime;

class Programme 
{
    /**
     *
     * @var int 
     */
    private $id;
    
    /**
     *
     * @var Objectif 
     */
    private $objectif;
    
    /**
     *
     * @var string 
     */
    private $titre;
    
    /**
     *
     * @var string 
     */
    private $materiel;
    
    /**
     *
     * @var string 
     */
    private $difficulte;
    
    /**
     *
     * @var string 
     */
    private $photo;
    
    /**
     *
     * @var string
     */
    private $sport;
    
    /**
     *
     * @var int
     */
    private $duree;
    
    /**
     *
     * @var Membre
     */
    private $membre;
    
    /**
     *
     * @var DateTime
     */
    private $datePublication;
    
      /**
     *  Programme constructor
     */
    public function __construct()
    {
        $this->datePublication = new DateTime();
    }
    
    
    // -------- Getter ----------
    
    /**
     * 
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * 
     * @return Objectif
     */
    public function getObjectif() {
        return $this->objectif;
    }
    
    /**
     * 
     * @return string
     */
    public function getObjectifTitre() {
        if (!is_null($this->objectif))
        {
            return $this->objectif->getTitre();
        }
        return '';
    }
    
    /**
     * 
     * @return int !null
     */
    public function getObjectifId() {
        if (!is_null($this->objectif))
        {
            return $this->objectif->getId();
        }
        return null;
    }
    
    /**
     * 
     * @return string
     */
    public function getTitre() {
        return $this->titre;
    }

    /**
     * 
     * @return string
     */
    public function getMateriel() {
        return $this->materiel;
    }

    /**
     * 
     * @return string
     */
    public function getDifficulte() {
        return $this->difficulte;
    }

    /**
     * 
     * @return string
     */
    public function getPhoto() {
        return $this->photo;
    }

    /**
     * 
     * @return string
     */
    public function getSport() {
        return $this->sport;
    }

    /**
     * 
     * @return int
     */
    public function getDuree() {
        return $this->duree;
    }

    /**
     * 
     * @return Membre
     */
    public function getMembre() {
        return $this->membre;
    }
    
    /**
     * 
     * @return string
     */
    public function getMembrePseudo() {
        if (!is_null($this->membre))
        {
            return $this->membre->getPseudo();
        }
    }
    
    /**
     * 
     * @return int
     */
    public function getMembreId() {
        if(!is_null($this->membre))
        {
            return $this->membre->getId();
        }
    }

    /**
     * 
     * @return DateTime
     */
    public function getDatePublication() {
    return $this->datePublication;
    }
    
     
    
    // -------- Setter ----------
    
    /**
     * 
     * @param int $id
     * @return Programme
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * 
     * @param Objectif $objectif
     * @return Programme
     */
    public function setObjectif(Objectif $objectif) {
        $this->objectif = $objectif;
        return $this;
    }
    
    /**
     * 
     * @param string $id
     * @return Programme
     */
    public function setTitre($titre) {
        $this->titre = $titre;
        return $this;
    }

    /**
     * 
     * @param string $materiel
     * @return Programme
     */
    public function setMateriel($materiel) {
        $this->materiel = $materiel;
        return $this;
    }

    /**
     * 
     * @param string $difficulte
     * @return Programme
     */
    public function setDifficulte($difficulte) {
        $this->difficulte = $difficulte;
        return $this;
    }

    /**
     * 
     * @param string $photo
     * @return Programme
     */
    public function setPhoto($photo) {
        $this->photo = $photo;
        return $this;
    }

    /**
     * 
     * @param string $sport
     * @return Programme
     */
    public function setSport($sport) {
        $this->sport = $sport;
        return $this;
    }

    /**
     * 
     * @param int $duree
     * @return Programme
     */
    public function setDuree($duree) {
        $this->duree = $duree;
        return $this;
    }

    /**
     * 
     * @param Membre $membre
     * @return Programme
     */
    public function setMembre(Membre $membre) {
        $this->membre = $membre;
        return $this;
    }
    
    /**
     * 
     * @param \Entity\DateTime $date_publication
     * @return Programme
     */
    public function setDatePublication(DateTime $datePublication) {
        $this->datePublication = $datePublication;
    return $this;
    }


}
