<?php

namespace Repository;

use DateTime;
use Entity\Membre;

class MembreRepository extends RepositoryAbstract {
    
    public function getTable()
    {
        return 'membre';
    }
    
    //--------------- Enregistrement ou update d'un membre en bdd --------------------
    /**
     * 
     * @param User $user
     */
    public function save(Membre $membre)
    {
        $data = [
                'pseudo'=>$membre->getPseudo(),
                'prenom'=>$membre->getPrenom(),
                'nom'=>$membre->getNom(),
                'email'=>$membre->getEmail(),
                'civilite'=>$membre->getCivilite(),
                'statut'=>$membre->getStatut(),
                'mdp'=>$membre->getMdp(),     
                'avatar'=>$membre->getAvatar()//,
                //'date_enregistrement'=>$membre->getDateEnregistrement()->format('Y-m-d H:i:s')
                ];
        
        $where = !empty($membre->getId())
                ? ['id' => $membre->getId()] // modification
                : null // création
            ;
        $this->persist($data, $where);
    }
    
    
    //--------------- Méthode pour rechercher un membre par son email --------------------

    public function findByEmail($email)
    {
        $dbMembre = $this->db->fetchAssoc(
                'SELECT * FROM membre WHERE email = :email', [':email'=>$email]
                );
        
        if(!empty($dbMembre))
        {
            return $this->buildFromArray($dbMembre);
        }      
        return null;
    }
    
    
    //--------------- Méthode pour rechercher un membre par son id --------------------
    
    public function findById($id)
    {
        $dbMembre = $this->db->fetchAssoc(
                'SELECT * FROM membre WHERE id = :id', [':id'=>$id]
                );
        
        if(!empty($dbMembre))
        {
            return $this->buildFromArray($dbMembre);
        }      
        return null;
    }
    
    
    //--------------- Méthode pour rechercher un membre par son pseudo --------------------
    
    public function findByPseudo($pseudo)
    {
        $dbMembre = $this->db->fetchAssoc(
                'SELECT * FROM membre WHERE pseudo = :pseudo', [':pseudo'=>$pseudo]
                );
        
        if(!empty($dbMembre))
        {
            return $this->buildFromArray($dbMembre);
        }      
        return null;
    }
    
    
    
    // ------------ Méthode findAll qui retourne un array -------------
    
    public function findAll() {
        
        $query = 'SELECT * FROM membre';
        
        $dbMembres = $this->db->fetchAll($query);
        
        $membres = [];
        
        foreach ($dbMembres as $dbMembre) {
            $membre = $this->buildFromArray($dbMembre);
            $membres[] = $membre;
        }
        return $membres;
    }
    
    // ----------- Méthode delete() pour supprimer un membre --------------
    
    /**
     * 
     * @param Membre $membre
     */
    public function delete(Membre $membre)
    {
        $this->db->delete('membre', ['id' => $membre->getId()]);
    }
    
    
    // ---------------  Méthode buildFromArray() --------------- 
    /**
     * 
     * @param array $dbMembre
     * @return Membre
     */
    public function buildFromArray(array $dbMembre)
    {
        $membre = new Membre();
        $membre
                ->setId($dbMembre['id'])
                ->setPseudo($dbMembre['pseudo'])
                ->setPrenom($dbMembre['prenom'])
                ->setNom($dbMembre['nom'])
                ->setEmail($dbMembre['email'])
                ->setCivilite($dbMembre['civilite'])
                ->setMdp($dbMembre['mdp'])
                ->setStatut($dbMembre['statut'])
                ->setAvatar($dbMembre['avatar'])
                ->setDateEnregistrement(new \DateTime($dbMembre['date_enregistrement']))
        ;
        return $membre;
    }
}
