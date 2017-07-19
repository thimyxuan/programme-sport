<?php

namespace Controller;

use Controller\ControllerAbstract;

use Entity\Exercice;
use Entity\Jour;
use Entity\Membre;
use Entity\Objectif;
use Entity\Programme;

class ProgrammeController extends ControllerAbstract 
{
    public function indexAction($id)
    {
        $programme = $this->app['programme.repository']->find($id);
        
        $jours = $this->app['jour.repository']->findByProgramme($programme);
        
        foreach ($jours as $jour) {
            $exercices = $this->app['exercice.repository']->findByJour($jour);
            $jour->setExercices($exercices);
        }
        
        
        return $this->render(
           'programme/index.html.twig',
           [
           'programme'=>$programme,
           'jours' => $jours,
           'exercices' => $exercices 
           ]
        ); 
    }
    
    public function registerAction() {
        /*
         * jour -> 1
         *          ->statut
         *      -> 2
         *          ->statut
         */       
        $programme = new Programme;
        
        $objectif = new Objectif;
        
        $exercice = new Exercice;

        $jour = new Jour;
        
        $errors = [];
        
        if ($_POST) {
            echo '<pre>'; print_r($_POST);echo '</pre>';die;
            if(!empty($_POST))
            {
                $membre = new Membre();
                $membre->setId(1);
//              $membre = $this->app['user.manager']->getUser();
                $objectif->setId($_POST['objectif_id']);
                $programme
                    ->setTitre($_POST['titre'])
                    ->setObjectif($objectif)
                    ->setMateriel($_POST['materiel'])
                    ->setDifficulte($_POST['difficulte'])    
                    ->setPhoto($_POST['photo'])    
                    ->setSport($_POST['sport'])    
                    ->setDuree($_POST['duree'])
                    ->setMembre($membre)
                    //->setDatePublication($_POST['date_publication'])
                ;                
                
                $jour
                    ->setOrdre($_POST['ordre'])
                    ->setStatut($_POST['statut'])
                    ->setProgramme($programme)
                ;
               
                $exercice
                    ->setTitre($_POST['titre'])
                    ->setConsigne($_POST['consigne'])
                    ->setDifficulte($_POST['difficulte'])
                    ->setZoneMusculaire($_POST['zone_musculaire'])
                    ->setMuscleCible($_POST['muscle_cible'])
                    ->setJour($jour)
                    ->setPhoto($_POST['photo'])
                    ->setSerie($_POST['serie'])
                    ->setRepetition($_POST['repetition'])
                    ->setDetailSerie($_POST['detail_serie'])
                    ->setTempsRepos($_POST['temps_repos'])
                    ;    
                

            }
          
            if (empty($errors)) {
                $this->app['programme.repository']->save($programme);
                $this->app['jour.repository']->save($jour);
                $this->app['exercice.repository']->save($exercice);           
            }
          
            else
            {
                $msg='<strong>Le formulaire contient des erreurs</strong>';
                $msg .= '<br>' . implode('<br>', $errors);
                // on utilise IMPLODE car on stock les erreurs dans un array errors=[];
                // implode permet d'ajouer un <br> après chaque élément du tableau
                $this->addFlashMessage($msg, 'error');
            }
            
        }
        
        return $this->render(
            'programme/creation.html.twig',
            [
                'programme' => $programme,
                'exercice' => $exercice,
                'jour'=> $jour
            ]
            );
    }
    
}
