<?php

namespace Repository;

use DateTime;
use Entity\Membre;
use Entity\Objectif;
use Entity\Programme;

class ProgrammeRepository extends RepositoryAbstract
{
    public function getTable()
    {
        return 'programme';
    }
    
    
    // ------------ Méthode findAll qui retourne un array -------------    
    /**
     * 
     * @return array
     */
     public function findAll()
    {
        $query = <<<EOS
SELECT p.*, o.titre as objectif_titre, m.pseudo
FROM programme p
JOIN objectif o ON p.objectif_id = o.id
JOIN membre m ON p.membre_id = m.id
ORDER BY date_publication DESC
EOS;
        $dbProgrammes = $this->db->fetchAll($query);
        $programmes = []; // le tableau dans lequel vont être stockées les entités
        foreach ($dbProgrammes as $dbProgramme) 
        {
            $programme = $this->buildFromArray($dbProgramme);            
            
            $programmes[] = $programme;
        }
        return $programmes;
    }    
    
    
    // --------- Méthode find($id) pour rechercher un programme par id ----------    
    /**
     * 
     * @param int $id
     * @return Programme null
     */
    public function find($id)
    {
        $query = <<<EOS
SELECT p.*, o.titre as objectif_titre, m.pseudo
FROM programme p
JOIN objectif o ON p.objectif_id = o.id
JOIN membre m ON p.membre_id = m.id
WHERE p.id = :id 
EOS;

        $dbProgramme = $this->db->fetchAssoc(
                $query,
                [':id'=> $id]
        );
        
        if(!empty($dbProgramme))
        {
            return $this->buildFromArray($dbProgramme);
        }
        return null;
    }
    
    
    // ------- Méthode findByObjectif() pour afficher les programmes par objectif --------
    
    public function findByObjectif(Objectif $objectif)
    {
        $query = <<<EOS
SELECT p.*, o.titre as objectif_titre, m.pseudo
FROM programme p
JOIN objectif o ON p.objectif_id = o.id
JOIN membre m ON p.membre_id = m.id
WHERE p.objectif_id = :id
ORDER BY id DESC
EOS;
        
        $dbProgrammes = $this->db->fetchAll(
                $query,
                [':id' => $objectif->getId()]
            );
        
        $programmes = [];
        foreach ($dbProgrammes as $dbProgramme) 
        {
            $programme = $this->buildFromArray($dbProgramme);            
            
            $programmes[] = $programme;
        }
        return $programmes;
        
    }
    
    public function findByMembre(Membre $membre)
    {
        $query = <<<EOS
SELECT p.*, m.*
FROM programme p
JOIN membre m ON p.membre_id = m.id
WHERE p.membre_id = :id
ORDER BY id DESC
EOS;
        
        $dbProgrammes = $this->db->fetchAll(
                $query,
                [':id' => $membre->getId()]
            );
        
        $programmes = [];
        foreach ($dbProgrammes as $dbProgramme) 
        {
            $programme = $this->buildFromArray($dbProgramme);            
            
            $programmes[] = $programme;
        }
        return $programmes;
        
    }
    
    // ---------- Enregistrement ou update d'un programme en bdd -----------
    /**
     * 
     * @param Programme $programme
     */
    public function save(Programme $programme)
    {
        $data = ['titre'=>$programme->getTitre(),
                'objectif_id'=>$programme->getObjectifId(),
                'materiel'=>$programme->getMateriel(),
                'difficulte'=>$programme->getDifficulte(),
                'photo_programme'=>$programme->getPhotoProgramme(),
                'sport'=>$programme->getSport(),
                'duree'=>$programme->getDuree(),            
                'membre_id'=>$programme->getMembreId(),
                'date_publication'=>$programme->getDatePublication()->format('Y-m-d H:i:s')
                ];
                //var_dump($programme->getId());
        $where = !empty($programme->getId())
                ? ['id' => $programme->getId()] // modification
                : null // création
            ;
        $this->persist($data, $where);        

        if(empty($programme->getId()))
        {
            $programme->setId($this->db->lastInsertId());
        }
    }
    
    
    
    // ---------- Suppression d'un programme en bdd -----------
    /**
     * 
     * @param Programme $programme
     */
    public function delete(Programme $programme)
    {
        $this->db->delete('programme', ['id' => $programme->getId()]);
    }
    
    
    
    // ---------------  Méthode buildFromArray() --------------- 
    /**
     * 
     * @param array $dbProgramme
     * @return Programme
     */
    public function buildFromArray(array $dbProgramme)
    {
        $programme = new Programme();
        
        $objectif = new Objectif();
        
        $membre = new Membre();
        
        $membre
                ->setId($dbProgramme['membre_id'])
                ->setPseudo($dbProgramme['pseudo'])
        ;
        
        $objectif
                ->setId($dbProgramme['objectif_id'])
                ->setTitre($dbProgramme['objectif_titre'])
        ;
        
        $programme
                ->setId($dbProgramme['id'])
                ->setTitre($dbProgramme['titre'])
                ->setMateriel($dbProgramme['materiel'])
                ->setDifficulte($dbProgramme['difficulte'])
                ->setPhotoProgramme($dbProgramme['photo_programme'])
                ->setSport($dbProgramme['sport'])
                ->setDuree($dbProgramme['duree'])
                ->setDatePublication(new \DateTime($dbProgramme['date_publication']))
                ->setObjectif($objectif)
                ->setMembre($membre)                
        ;
        
        return $programme;
    }
    
    public function search($motRecherche) {
        
//        ECRIRE LA GROSSE REQUETE DE JOINTURE POUR COMPARAISON DE LA RECHERCHE DU FORMULAIRE
        
        // EXEMPLE
        $query = "SELECT * FROM programme WHERE titre = :titre";

        $dbProgramme = $this->db->fetchAll(
                $query,
                [':titre'=> $motRecherche]
        );
        
        return $dbProgramme;
        
    }
    
}
