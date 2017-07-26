<?php

namespace Entity;

use DateTime;

class Membre {
    
    /**
     *
     * @var int 
     */
    private $id;
    
    /**
     *
     * @var string 
     */
    private $pseudo;
    
    /**
     *
     * @var string 
     */
    private $mdp;
    
    /**
     *
     * @var string 
     */
    private $nom;
    
    /**
     *
     * @var string 
     */
    private $prenom;
    
    /**
     *
     * @var string 
     */
    private $email;
    
    /**
     *
     * @var string 
     */
    private $civilite;
    
    /**
     *
     * @var string 
     */
    private $statut = 'membre';
    
    /**
     *
     * @var string 
     */
    private $avatar;
    
    /**
     *
     * @var DateTime 
     */
    private $dateEnregistrement;
    
    
    /**** GETTERS ****/
    
    public function getId() {
        return $this->id;
    }

    public function getPseudo() {
        return $this->pseudo;
    }

    public function getMdp() {
        return $this->mdp;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getCivilite() {
        return $this->civilite;
    }

    public function getStatut() {
        return $this->statut;
    }
    
    public function getAvatar() {
        return $this->avatar;
    }
    
    /**
     * 
     * @return DateTime
     */
    public function getDateEnregistrement() {
    return $this->dateEnregistrement;
    }

    
    
    /**** SETTERS ****/
    
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setPseudo($pseudo) {
        $this->pseudo = $pseudo;
        return $this;
    }

    public function setMdp($mdp) {
        $this->mdp = $mdp;
        return $this;
    }

    public function setNom($nom) {
        $this->nom = $nom;
        return $this;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setCivilite($civilite) {
        $this->civilite = $civilite;
        return $this;
    }

    public function setStatut($statut) {
        $this->statut = $statut;
        return $this;
    }
    
    public function setAvatar($avatar) {
        $this->avatar = $avatar;
        return $this;
    }
    
    /**
     * 
     * @param \Entity\DateTime $dateEnregistrement
     * @return Membre
     */
    public function setDateEnregistrement(DateTime $dateEnregistrement) {
        $this->dateEnregistrement = $dateEnregistrement;
    return $this;
    }

    public function isAdmin() {
        
        return $this->statut == 'admin';
    }

}
