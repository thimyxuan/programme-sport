<?php

namespace Entity;

class Exercice {
    
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
    
    /**
     *
     * @var string
     */
    private $consigne;
    
    /**
     *
     * @var string
     */
    private $difficulte;
    
    /**
     *
     * @var string
     */
    private $zoneMusculaire;
    
    /**
     *
     * @var string
     */
    private $muscleCible;
    
    /**
     *
     * @var Jour
     */
    private $jour;
    
    /**
     *
     * @var array 
     */
    private $jours = [];
    
    /**
     *
     * @var string
     */
    private $photo;
    
    /**
     *
     * @var int
     */
    private $serie;
    
    /**
     *
     * @var int
     */
    private $repetition;
    
    /**
     *
     * @var string
     */
    private $detailSerie;
    
    /**
     *
     * @var int
     */
    private $tempsRepos;

//--------------------------------------------    
//    GETTERS
    public function getId() {
        return $this->id;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function getConsigne() {
        return $this->consigne;
    }

    public function getDifficulte() {
        return $this->difficulte;
    }

    public function getZoneMusculaire() {
        return $this->zoneMusculaire;
    }

    public function getMuscleCible() {
        return $this->muscleCible;
    }

    public function getJour() {
        return $this->jour;
    }
    
    public function getJourId() {
        if(!is_null($this->jour)) {
            return $this->jour->getId();
        }
        return null;
    }

    public function getPhoto() {
        return $this->photo;
    }

    public function getSerie() {
        return $this->serie;
    }

    public function getRepetition() {
        return $this->repetition;
    }

    public function getDetailSerie() {
        return $this->detailSerie;
    }

    public function getTempsRepos() {
        return $this->tempsRepos;
    }
//-------------------------------------------
//    SETTERS
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setTitre($titre) {
        $this->titre = $titre;
        return $this;
    }

    public function setConsigne($consigne) {
        $this->consigne = $consigne;
        return $this;
    }

    public function setDifficulte($difficulte) {
        $this->difficulte = $difficulte;
        return $this;
    }

    public function setZoneMusculaire($zoneMusculaire) {
        $this->zoneMusculaire = $zoneMusculaire;
        return $this;
    }

    public function setMuscleCible($muscleCible) {
        $this->muscleCible = $muscleCible;
        return $this;
    }

    public function setJour(Jour $jour) {
        $this->jour = $jour;
        return $this;
    }

    public function setPhoto($photo) {
        $this->photo = $photo;
        return $this;
    }

    public function setSerie($serie) {
        $this->serie = $serie;
        return $this;
    }

    public function setRepetition($repetition) {
        $this->repetition = $repetition;
        return $this;
    }

    public function setDetailSerie($detailSerie) {
        $this->detailSerie = $detailSerie;
        return $this;
    }

    public function setTempsRepos($tempsRepos) {
        $this->tempsRepos = $tempsRepos;
        return $this;
    }
    //----------------------
    public function setJours(array $jours) {
        $this->jours = $jours;
        return $this;
    }

}
