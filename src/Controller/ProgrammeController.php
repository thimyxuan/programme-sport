<?php

namespace Controller;

use Controller\ControllerAbstract;
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
        
        $programme = new Programme;
        
        $objectif = new Objectif;
        
        $jour = new Jour;
        
        $errors = [];
        
        if ($_POST) {
            
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
                
                //var_dump($programme);
            }
            if (empty($errors)) {
                $this->app['programme.repository']->save($programme);
                $this->app['jour.repository']->save($jour);
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
                'jour'=> $jour
            ]
            );
    }
    
}
