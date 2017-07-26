<?php

namespace Repository;

use Entity\Exercice;
use Entity\Jour;

class ExerciceRepository extends RepositoryAbstract {
    
    public function getTable()
    {
        return 'exercice';
    }
    
    public function findByJour(Jour $jour) {
        
        $query = <<<EOS
SELECT e.*
FROM exercice e
JOIN jour j ON e.jour_id = j.id
WHERE e.jour_id = :id
ORDER BY e.id ASC
EOS;
    
        $dbExercices = $this->db->fetchAll(
            $query,
            [':id' => $jour->getId()]
        );
    
        $exercices = [];

        foreach ($dbExercices as $dbExercice) {

            $exercice = $this->buildFromArray($dbExercice);

            $exercices[] = $exercice;
        }

        return $exercices;
    }
    
    public function save(Exercice $exercice)
    {
        $data = ['titre'=>$exercice->getTitre(),
                'consigne'=>$exercice->getConsigne(),
                'difficulte'=>$exercice->getDifficulte(),
                'zone_musculaire'=>$exercice->getZoneMusculaire(),
                'muscle_cible'=>$exercice->getMuscleCible(),
                'jour_id'=>$exercice->getJourId(),
                'photo'=>$exercice->getPhoto(),
                'serie'=>$exercice->getSerie(),
                'repetition'=>$exercice->getRepetition(),
                'detail_serie'=>$exercice->getDetailSerie(),
                'temps_repos'=>$exercice->getTempsRepos()
                ];
                
        
        $where = !empty($exercice->getId())
                ? ['id' => $exercice->getId()] // modification
                : null // création
        ;
        
        $this->persist($data, $where);
        
        if (empty($exercice->getId())) {
            $exercice->setId($this->db->lastInsertId());
        }
    }
    
    public function buildFromArray(array $dbExercice)
    {
        $jour = new Jour();
        $exercice = new Exercice();
        $exercice
                ->setId($dbExercice['id'])
                ->setTitre($dbExercice['titre'])
                ->setConsigne($dbExercice['consigne'])
                ->setDifficulte($dbExercice['difficulte'])
                ->setZoneMusculaire($dbExercice['zone_musculaire'])
                ->setMuscleCible($dbExercice['muscle_cible'])
                ->setJour($jour)
                ->setPhoto($dbExercice['photo'])
                ->setSerie($dbExercice['serie'])
                ->setRepetition($dbExercice['repetition'])
                ->setDetailSerie($dbExercice['detail_serie'])
                ->setTempsRepos($dbExercice['temps_repos'])
        ;
        return $exercice;
    }
}
