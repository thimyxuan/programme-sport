<?php

namespace Repository;

use Entity\Objectif;

class ObjectifRepository extends RepositoryAbstract
{
    public function getTable()
    {
        return 'objectif';
    }   
    
    
    // -------------- Méthode findAll() qui retourne un array --------------
    /**
     * 
     * @return array
     */
    public function findAll()
    {
        $dbObjectifs = $this->db->fetchAll('SELECT * FROM objectif ORDER BY id ASC');
        $objectifs = []; 
        
        foreach ($dbObjectifs as $dbObjectif) 
        {
            $objectif = new Objectif();
            
            $objectif 
                    ->setId($dbObjectif['id'])
                    ->setTitre($dbObjectif['titre'])
            ;
            
            $objectifs[] = $objectif;
        }
        return $objectifs;
    }   
    
    
    // -------------- Méthode find($id) recherche un objectif par id --------------
    /**
     * 
     * @param int $id
     * @return Objectif null
     */
    public function find($id)
    {
        $dbObjectif = $this->db->fetchAssoc(
                'SELECT * FROM objectif WHERE id = :id',
                 [':id'=> $id]
        );
        
        if(!empty($dbObjectif))
        {
            return $this->buildFromArray($dbObjectif);
        }
        return null;
    }    
    
    
    // --------- Méthode save() pour enregistrer ou update un objectif en bdd ----------
    /**
     * 
     * @param Objectif $objectif
     */
    public function save(Objectif $objectif)
    {
        $data = ['titre'=>$objectif->getTitre()];
        
        $where = !empty($objectif->getId())
                ? ['id' => $objectif->getId()] // modification
                : null // création
            ;
        $this->persist($data, $where);           
    }
    
    
    // ----------- Méthode delete() pour supprimer un objectif --------------
    
    /**
     * 
     * @param Objectif $objectif
     */
    public function delete(Objectif $objectif)
    {
        $this->db->delete('objectif', ['id' => $objectif->getId()]);
    }
    
    
    // ----------- Méthode buildFromArray() --------------
    
    /**
     * 
     * @param array $dbObjectif
     * @return Objectif
     */
    public function buildFromArray(array $dbObjectif)
    {
        $objectif = new Objectif();
        $objectif
                ->setId($dbObjectif['id'])
                ->setTitre($dbObjectif['titre'])
        ;
        return $objectif;
    }
}
