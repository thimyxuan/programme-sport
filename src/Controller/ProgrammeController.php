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
        $allExercices = [];
   
        foreach ($jours as $index => $jour) {
            
            $exercices = $this->app['exercice.repository']->findByJour($jour);
            $allExercices[] = $exercices;
            //echo '<pre>'; print_r($exercices); echo '</pre>';
        }
        
        $allExercices = $allExercices[0]; // commenter cette ligne après avoir bouclé dans TWIG
        //echo '<pre>'; print_r($allExercices); echo '</pre>';
        
        return $this->render(
           'programme/index.html.twig',
           [
           'programme'=>$programme,
           'jours' => $jours,
           'exercices' => $allExercices
           ]
        );
        
        
    }


    public function editAction($id = null) {
        
        $objectifs = $this->app['objectif.repository']->findAll();
        
        if(!is_null($id))
        {
            $programme = $this->app['programme.repository']->find($id);
            //$jour = $this->app['jour.repository']->findByProgramme($programme);
            //echo '<pre>'; print_r($jour) ; echo '</pre>';
            //$exercice = $this->app['exercice.repository']->findByJour($jour);
            //echo '<pre>'; print_r($exercice) ; echo '</pre>';
        }        
        else
        {        
            $programme = new Programme;
            
            $objectif = new Objectif;
        }
        
        $tabJours = [];
        
        $tabExercices = [];
        
        $errors = [];
        
        if ($_POST) {
            
            echo '<pre>'; print_r($_POST);echo '</pre>';
            
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
                
                // Il faut faire un foreach poour chaque $_POST['jour']
                // et à l'intérieur de ce foreach, il faut faire un for
                // ce qui donnerait dans le for quelque chose comme ça :
                // 
                
                if (isset($_POST['jour'])) {
                    
                    foreach ($_POST['jour'] as $index => $formJour) {

                        $jour = new Jour();

                            $jour
                                ->setOrdre($index)
                                ->setStatut($formJour['statut'])
                                ->setProgramme($programme)
                            ;

                        $tabJours[] = $jour;
                        
                                                
                        if (isset($_POST['jour'][$index]['exercice'])) {
                        
                            foreach ($_POST['jour'][$index]['exercice'] as $index => $formExercice) {

                                $exercice = new Exercice();

                                $exercice
                                    ->setTitre($formExercice['titre'])
                                    ->setConsigne($formExercice['consigne'])
                                    ->setDifficulte($formExercice['difficulte'])
                                    ->setZoneMusculaire($formExercice['zone_musculaire'])
                                    ->setMuscleCible($formExercice['muscle_cible'])
                                    ->setPhoto($formExercice['photo'])
                                    ->setSerie($formExercice['serie'])
                                    ->setRepetition($formExercice['repetition'])
                                    ->setDetailSerie($formExercice['detail_serie'])
                                    ->setTempsRepos($formExercice['temps_repos'])
                                    ->setJour($jour)
                                    ;

                                $tabExercices[] = $exercice;

                            }
                        }

                    }
                }
          
                if (empty($errors)) {
                    $this->app['programme.repository']->save($programme);
                    foreach ($tabJours as $jour) {
                        $this->app['jour.repository']->save($jour); 
                    }   
                    foreach ($tabExercices as $exercice) {
                        $this->app['exercice.repository']->save($exercice);
                    }
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
        }
        
        return $this->render(
            'programme/edit.html.twig',
            [
                'objectifs' => $objectifs,
                'programme' => $programme,
                //'exercice' => $exercice,
                'jours'=> $tabJours,
                'exercices' => $tabExercices
            ]
        );
        
    }

}
