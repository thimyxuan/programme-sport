<?php

namespace Repository;

use Entity\Jour;

class JourRepository extends RepositoryAbstract
{
    public function getTable()
    {
        return 'jour';
    }
    
    
    /**
     * 
     * @return array
     */
    
    public function findAll()
    {
        $dbJours = $this->db->fetchAll('SELECT * FROM jour ORDER BY id DESC');
        $jours = []; // le tableau dans lequel vont être stockées les entités Jour
        
        foreach ($dbJours as $dbJour) {
            $jour = $this->buildFromArray($dbJour);
            
            $jours[] = $jour;
        }
        
        return $jours;
    }
    
    /**
     * 
     * @param type $id
     * @return Jour|null
     */
    public function find($id)
    {
        $dbJour = $this->db->fetchAssoc(
                'SELECT * FROM jour WHERE id = :id',
                [':id' => $id]
        );
        
        if (!empty($dbJour)) {
            return $this->buildFromArray($dbJour);
        }
        return null;
    }
    
    public function save(Jour $jour)
    {
        $data = ['ordre'=>$jour->getOrdre(),
                'programme_id'=>$jour->getProgramme_id(),
                'statut'=>$jour->getStatut()
                ];
                
        
        $where = !empty($jour->getId())
                ? ['id' => $jour->getId()] // modification
                : null // création
        ;
        
        $this->persist($data, $where);
    }
    
    public function delete(Jour $jour)
    {
        $this->db->delete('jour', ['id' => $jour->getId()]);
    }
    
    /**
     * 
     * @param array $dbJour
     * @return Jour
     */
    
    public function buildFromArray(array $dbJour)
    {
        $jour = new Jour();
        
        $jour
            ->setId($dbJour['id'])
            ->setOrdre($dbJour['ordre'])
            //->setProgramme_id($programme_id)
            ->setStatut($dbJour['statut'])
        ;
        
        return $jour;
    }
}