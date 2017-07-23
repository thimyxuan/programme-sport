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


    public function editAction($id = null) 
    {        
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
        
        if($_POST) {
        
            if (!empty($_POST)) {

                //echo '<pre>'; print_r($_POST);echo '</pre>';

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
            }



            //--------- Vérification des champs du formulaire avant entrée en bdd

            foreach($_POST as $indice => $valeur)
            {
                if($indice == 'titre' || $indice == 'photo' || $indice == 'duree')
                {
                    $_POST[$indice] = htmlspecialchars(addslashes($valeur));
                }

            }
            if(empty($_POST['titre']))
            {
                $errors['titre'] = 'Le titre du programme est obligatoire';
            }
            elseif(strlen($_POST['titre'])>100)
            {
                $errors['nom'] = 'Le titre ne doit pas faire plus de 100 caractères' ;
            }
            if(empty($_POST['objectif_id']))
            {
                $errors['objectif_id'] = 'L\'objectif est obligatoire';
            }
            if(empty($_POST['materiel']))
            {
                $errors['materiel'] = 'Le champ matériel est obligatoire';
            }
            if(empty($_POST['difficulte']))
            {
                $errors['difficulte'] = 'Le champ difficulté est obligatoire';
            }
            if(empty($_POST['sport']))
            {
                $errors['sport'] = 'Le champ sport est obligatoire';
            }
            if(empty($_POST['duree']))
            {
                $errors['duree'] = 'La durée est obligatoire';
            }
            elseif(!is_numeric($_POST['duree']))
            {
                $errors['duree'] = 'La durée n\'est pas valide';
            }
            //for ($i=1; $i<10; $i++)
            //{
                if(empty($_POST['jour']['statut']))
                {
                    $errors['statut'] = 'Le statut du jour est obligatoire';
                }
            //}
            /*if(empty($_POST['jour']['exercice']['titre']))
            {
                $errors['exercicetitre'] = 'Le titre de l\'exercice est obligatoire';
            }
            elseif(strlen($_POST['jour']['exercice']['titre'])>100)
            {
                $errors['exercicetitre'] = 'Le titre de l\'exercice ne doit pas faire plus de 100 caractères' ;
            }
            if(empty($_POST['jour']['exercice']['consigne']))
            {
                $errors['exerciceconsigne'] = 'La consigne est obligatoire';
            }
            elseif(strlen($_POST['jour']['exercice']['consigne'])>600)
            {
                $errors['exerciceconsigne'] = 'La consigne ne doit pas faire plus de 600 caractères' ;
            }
            if(empty($_POST['jour']['exercice']['zone_musculaire']))
            {
                $errors['exercicezone'] = 'La zone musculaire est obligatoire';
            }
            if(empty($_POST['jour']['exercice']['muscle_cible']))
            {
                $errors['exercicemuscle'] = '' ;
            }
            elseif(strlen($_POST['jour']['exercice']['muscle_cible'])>200)
            {
                $errors['exercicemuscle'] = 'Le champs muscle ciblé ne doit pas faire plus de 200 caractères ' ;
            }
            if(empty($_POST['jour']['exercice']['serie']))
            {
                $errors['exerciceserie'] = 'Le nombre de séries est obligatoire';
            }
            elseif(!is_numeric($_POST['jour']['exercice']['serie']))
            {
                $errors['exerciceserie'] = 'Le nombre de séries n\'est pas valide';
            }
            if(empty($_POST['jour']['exercice']['repetition']))
            {
                $errors['exercicerepetition'] = 'Le nombre de répétitions est obligatoire';
            }
            elseif(!is_numeric($_POST['jour']['exercice']['repetition']))
            {
                $errors['exercicerepetition'] = 'Le nombre de répétitions n\'est pas valide';
            }
            if(empty($_POST['jour']['exercice']['temps_repos']))
            {
                $errors['exercicerepos'] = 'Le temps de repos est obligatoire';
            }
            elseif(!is_numeric($_POST['jour']['exercice']['temps_repos']))
            {
                $errors['exercicerepos'] = 'Le temps de repos n\'est pas valide';
            }
            if(empty($_POST['jour']['exercice']['detail_serie']))
            {
                $errors['exercicedetail'] = '' ;
            }
            elseif(strlen($_POST['jour']['exercice']['detail_serie'])>600)
            {
                $errors['exercicedetail'] = 'Le champ détail série ne doit pas faire plus de 600 caractères' ;
            }*/




            // Si pas d'erreur c'est ok pour envoyer en bdd :

            if (empty($errors)) {
                $this->app['programme.repository']->save($programme);

                foreach ($tabJours as $jour) {
                    $this->app['jour.repository']->save($jour); 
                }

                foreach ($tabExercices as $exercice) {
                    $this->app['exercice.repository']->save($exercice);
                }
                $msg='<strong>Enregistrement réussi !</strong>';
                $this->addFlashMessage($msg, 'success');
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

