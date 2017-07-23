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
            
                       
            echo '<pre>'; print_r($_POST); echo '</pre>';
            
            // Contrôles pour les champs de la table PROGRAMME
            foreach($_POST as $indice => $valeur)
            {
                if($indice == 'titre' || $indice == 'photo' || $indice == 'duree')
                {
                    $_POST[$indice] = htmlspecialchars(addslashes($valeur));
                }

            }
            
            // Contrôles pour les champs de la table EXERCICE
            foreach($_POST as $indice => $valeur)
            {
                if ($indice == 'jour'){
                    foreach($valeur as $index => $value){
                        if($index == 'exercice'){
                            foreach($value as $ind => $val){
                                $_POST[$ind] = htmlspecialchars(addslashes($val));
                            }
                        }
                    }
                }
            }
            
            
            // Contrôles pour les champs de la table PROGRAMME
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
            
            
            // Contrôles pour les champs de la table JOUR
            
            foreach($_POST as $indice => $valeur)
            {
                if ($indice == 'jour'){
                    foreach($valeur as $index => $value){                        
                        //echo '<pre>'; print_r($_POST[$indice][$index]['statut']); echo '</pre>';                        
                        if(empty($_POST[$indice][$index]['statut']))
                        {
                            $errors['statut'] = 'Le statut du jour est obligatoire';
                        }
                    }
                }            
            }            
            /*
            for ($i=1; $i<8; $i++) {// ceci ne fonctionne pas, est remplacé par le code ci-dessus
                if(empty($_POST['jour'][$i]['statut'])) {
                        $errors['statut'] = 'Le statut du jour est obligatoire';
                    }    
            }*/
            
            
            // Contrôles pour les champs de la table EXERCICE
            
            foreach($_POST as $indice => $valeur)
            {
                if ($indice == 'jour'){
                    foreach($valeur as $index => $value){
                        if($index == 'exercice'){
                            foreach($value as $ind => $val){
                                
                                // toutes mes conditions ici :
                                
                                //echo '<pre>'; print_r($_POST[$indice][$index][$ind]['difficulte']); echo '</pre>'; 
                                
                                if(empty($_POST[$indice][$index][$ind]['titre']))
                                {
                                    $errors['exercice_titre'] = 'Le titre de l\'exercice est obligatoire';
                                }
                                elseif(strlen($_POST[$indice][$index][$ind]['titre'])>100)
                                {
                                    $errors['exercice_titre'] = 'Le titre de l\'exercice ne doit pas faire plus de 100 caractères' ;
                                }
                                if(empty($_POST[$indice][$index][$ind]['consigne']))
                                {
                                    $errors['exercice_consigne'] = 'La consigne est obligatoire';
                                }
                                elseif(strlen($_POST[$indice][$index][$ind]['consigne'])>600)
                                {
                                    $errors['exercice_consigne'] = 'La consigne ne doit pas faire plus de 600 caractères' ;
                                }
                                if(empty($_POST[$indice][$index][$ind]['zone_musculaire']))
                                {
                                    $errors['exercice_zone'] = 'La zone musculaire est obligatoire';
                                }
                                if(empty($_POST[$indice][$index][$ind]['muscle_cible']))
                                {
                                    $errors['exercice_muscle'] = '' ;
                                }
                                elseif(strlen($_POST[$indice][$index][$ind]['muscle_cible'])>200)
                                {
                                    $errors['exercice_muscle'] = 'Le champs muscle ciblé ne doit pas faire plus de 200 caractères ' ;
                                }
                                if(empty($_POST[$indice][$index][$ind]['serie']))
                                {
                                    $errors['exercice_serie'] = 'Le nombre de séries est obligatoire';
                                }
                                elseif(!is_numeric($_POST[$indice][$index][$ind]['serie']))
                                {
                                    $errors['exercice_serie'] = 'Le nombre de séries n\'est pas valide';
                                }
                                if(empty($_POST[$indice][$index][$ind]['repetition']))
                                {
                                    $errors['exercice_repetition'] = 'Le nombre de répétitions est obligatoire';
                                }
                                elseif(!is_numeric($_POST[$indice][$index][$ind]['repetition']))
                                {
                                    $errors['exercice_repetition'] = 'Le nombre de répétitions n\'est pas valide';
                                }
                                if(empty($_POST[$indice][$index][$ind]['temps_repos']))
                                {
                                    $errors['exercice_repos'] = 'Le temps de repos est obligatoire';
                                }
                                elseif(!is_numeric($_POST[$indice][$index][$ind]['temps_repos']))
                                {
                                    $errors['exercice_repos'] = 'Le temps de repos n\'est pas valide';
                                }
                                if(empty($_POST[$indice][$index][$ind]['detail_serie']))
                                {
                                    $errors['exercice_detail'] = '' ;
                                }
                                elseif(strlen($_POST[$indice][$index][$ind]['detail_serie'])>600)
                                {
                                    $errors['exercic_edetail'] = 'Le champ détail série ne doit pas faire plus de 600 caractères' ;
                                }
                                
                            }
                        }
                    }
                }
            }
           
           

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

