<?php


namespace Entity;


class Jour
{
    /**
     *
     * @var int
     */
    private $id;
    
    /**
     *
     * @var int
     */
    private $ordre;
    
    /**
     *
     * @var Programme
     */
    private $programme;
    
    /**
     *
     * @var string
     */
    private $statut;
    
    /**
     *
     * @var array 
     */
    private $exercices = [];
    
    /**
     * 
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * 
     * @return int
     */
    public function getOrdre() {
        return $this->ordre;
    }
    
    /**
     * 
     * @return Programme
     */
    public function getProgramme() {
        return $this->programme;
    }
    
    public function getProgrammeId() {
        if(!is_null($this->programme)) {
            return $this->programme->getId();
        }
        return null;
    }
    
    /**
     * 
     * @return string
     */
    public function getStatut() {
        return $this->statut;
    }
    
    /**
     * 
     * @param int $id
     * @return Jour
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * 
     * @param int $ordre
     * @return Jour
     */
    public function setOrdre($ordre) {
        $this->ordre = $ordre;
        return $this;
    }
    
    /**
     * 
     * @param \Entity\Programme $programme
     * @return Jour
     */
    public function setProgramme(Programme $programme) {
        $this->programme = $programme;
        return $this;
    }
    
    /**
     * 
     * @param int $statut
     * @return Jour
     */
    public function setStatut($statut) {
        $this->statut = $statut;
        return $this;
    }
    
    //------------------------------------------
    // SUREMENT A SUPPRIMER
    public function getJourId() {
        
        if(!is_null($this->programme))
        {    
            return $this->programme->getId();
        }
    }
    
    public function getExercices() {
        return $this->exercices;
    }

    public function setExercices(array $exercices) {
        $this->exercices = $exercices;
        return $this;
    }


}

