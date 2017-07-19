<?php

namespace Repository;

use Entity\Jour;
use Entity\Programme;

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
                'programme_id'=>$jour->getProgrammeId(),
                'statut'=>$jour->getStatut()
                ];
                
        
        $where = !empty($jour->getId())
                ? ['id' => $jour->getId()] // modification
                : null // création
        ;
        
        $this->persist($data, $where);
        
        if (empty($jour->getId())) {
            $jour->setId($this->db->lastInsertId());
        }
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
        $programme = new Programme;
        
        $jour
            ->setId($dbJour['id'])
            ->setOrdre($dbJour['ordre'])
            ->setProgramme($programme)
            ->setStatut($dbJour['statut'])
        ;
        
        return $jour;
    }
    
    public function findByProgramme(Programme $programme) {
        
        $query = <<<EOS
SELECT j.*
FROM jour j
JOIN programme p ON j.programme_id = p.id
WHERE j.programme_id = :id
ORDER BY ordre ASC
EOS;
    
        $dbJours = $this->db->fetchAll(
            $query,
            [':id' => $programme->getId()]
        );
    
        $jours = [];

        foreach ($dbJours as $dbJour) {

            $jour = $this->buildFromArray($dbJour);

            $jours[] = $jour;
        }

        return $jours;
    }
}