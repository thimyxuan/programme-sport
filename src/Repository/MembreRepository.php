<?php

namespace Repository;

use Entity\Membre;

class MembreRepository extends RepositoryAbstract {
    
    public function getTable()
    {
        return 'membre';
    }
    
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
                'mdp'=>$membre->getMdp()
                ];
        
        $where = !empty($membre->getId())
                ? ['id' => $membre->getId()] // modification
                : null // création
            ;
        $this->persist($data, $where);           
    }
    
    /**
     * 
     * @param type $email
     * @return string
     */
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
    
    /**
     * 
     * @param type $id
     * @return int
     */
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
        ;
        return $membre;
    }
}
