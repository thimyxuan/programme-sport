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
ORDER BY ordre ASC
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
    
    public function buildFromArray(array $dbExercice)
    {
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
