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
        }
        
        return $this->render(
           'programme/index.html.twig',
           [
           'programme'=>$programme,
           'jours' => $jours,
           'exercices' => $allExercices,          
           ]
        );
        
        
    }


    public function editAction($id = null) 
    {        
        $objectifs = $this->app['objectif.repository']->findAll();
        $objectif = new Objectif;
        
        if(!is_null($id))
        {
            $programme = $this->app['programme.repository']->find($id);
            
        }        
        else
        {        
            $programme = new Programme;
            
            
        }
        
        $tabJours = [];
        
        $tabExercices = [];
        
        $errors = [];
        
        $membre_id = $this->app['user.manager']->getUser()->getId();
        
        if ($_POST) {
            
            
            if(!empty($_POST))
            {
                $membre = $this->app['user.manager']->getUser();
              
                $objectif->setId($_POST['objectif_id']);
                $programme
                    ->setTitre($_POST['titre'])
                    ->setObjectif($objectif)
                    ->setMateriel($_POST['materiel'])
                    ->setDifficulte($_POST['difficulte'])    
                    ->setSport($_POST['sport'])    
                    ->setDuree($_POST['duree'])
                    ->setMembre($membre)
                    //->setDatePublication($_POST['date_publication'])
                ; 
                
                if(!empty($_FILES['photo_programme']['name'])) {

                    $nom_photo_programme = str_replace(array(' ', '/', '\\'), '_', $_POST['titre']) . '_' . $_FILES['photo_programme']['name']; // ondéfinit le nom de la photo
                    // on définit le nom complet de la photo. Nous nous servirons de cette variable pour enregistrer 
                    // le chemin complet de la photo en BDD puisqu'on ne garde jamais la photo, mais le lien en bdd
                    $photo_bdd_programme = "http://localhost/programme-sport/web/photo/$nom_photo_programme";
                    // on définit le chemin physique du dossier de destination pour enregistrer la photo
                    $photo_dossier_programme = $_SERVER['DOCUMENT_ROOT'] . "/programme-sport/" . "web/photo/$nom_photo_programme";
                    // la fonction copy permet de copier la photo directement dans le dossier de destination
                    copy($_FILES['photo_programme']['tmp_name'], $photo_dossier_programme);

                    $programme->setPhotoProgramme($photo_bdd_programme);
                }
                // Il faut faire un foreach poour chaque $_POST['jour']
                // et à l'intérieur de ce foreach, il faut faire un for
                // ce qui donnerait dans le for quelque chose comme ça :
                // 

                if (isset($_POST['jour'])) {
                    $i = 0;
                    foreach ($_POST['jour'] as $index => $formJour) {

                        $jour = new Jour();

                            $jour
                                ->setOrdre($index)
                                ->setStatut($formJour['statut'])
                                ->setProgramme($programme)
                            ;
                            
                        $tabJours[] = $jour;

                        
                        if (isset($_POST['jour'][$index]['exercice'])) {
                            
                            foreach ($_POST['jour'][$index]['exercice'] as $ind => $formExercice) {
                               
                                $exercice = new Exercice();
                                
                                $exercice
                                    ->setTitre($formExercice['titre'])
                                    ->setConsigne($formExercice['consigne'])
                                    ->setDifficulte($formExercice['difficulte'])
                                    ->setZoneMusculaire($formExercice['zone_musculaire'])
                                    ->setMuscleCible($formExercice['muscle_cible'])
                                    ->setSerie($formExercice['serie'])
                                    ->setRepetition($formExercice['repetition'])
                                    ->setDetailSerie($formExercice['detail_serie'])
                                    ->setTempsRepos($formExercice['temps_repos'])
                                    ->setJour($jour)
                                    ;
                                
                                if ($formJour['statut'] == 'repos') {
                                    
                                    $exercice
                                    ->setTitre('repos')
                                    ->setConsigne('repos')
                                    ->setDifficulte('facile')
                                    ->setZoneMusculaire('cou')
                                    ->setMuscleCible('repos')
                                    ->setSerie(0)
                                    ->setRepetition(0)
                                    ->setDetailSerie('repos')
                                    ->setTempsRepos(0)
                                    ->setJour($jour)
                                    ;
                                }
                                
                                if(!empty($_FILES['jour']['name'][$index]['exercice'][$ind]['photo'])) {
                                    $nom_photo_exercice = str_replace(' ', '_', $formExercice['titre']) . '_' . $_FILES['jour']['name'][$index]['exercice'][$ind]['photo']; // ondéfinit le nom de la photo
                                    // on définit le nom complet de la photo. Nous nous servirons de cette variable pour enregistrer 
                                    // le chemin complet de la photo en BDD puisqu'on ne garde jamais la photo, mais le lien en bdd
                                    $photo_bdd_exercice = "http://localhost/programme-sport/web/photo/$nom_photo_exercice";
                                    // on définit le chemin physique du dossier de destination pour enregistrer la photo
                                    $photo_dossier_exercice = $_SERVER['DOCUMENT_ROOT'] . "/programme-sport/" . "web/photo/$nom_photo_exercice";
                                    // la fonction copy permet de copier la photo directement dans le dossier de destination
                                    copy($_FILES['jour']['tmp_name'][$index]['exercice'][$ind]['photo'], $photo_dossier_exercice);
                                    
                                    $exercice->setPhoto($photo_bdd_exercice);
                                }

                                $tabExercices[$i][] = $exercice;
                            }   
                        }
                        $i++;
                    }
                }            
            }
            

            //--------- Vérification des champs du formulaire avant entrée en bdd
            
            // Condition addslashes() pour la table PROGRAMME
            foreach($_POST as $indice => $valeur)
            {
                if($indice == 'titre' || $indice == 'photo' || $indice == 'duree')
                {
                    $_POST[$indice] = htmlspecialchars(addslashes($valeur));
                }

            }
            
            // Condition addslashes() pour la table EXERCICE
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
            
            
            // Contrôles des champs de la table PROGRAMME
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
            
            foreach($_POST as $indice => $valeur)
            {
                if ($indice == 'jour'){
                    foreach($valeur as $index => $value){                        
                        //echo '<pre>'; print_r($_POST[$indice][$index]['statut']); echo '</pre>';                        
                        if(empty($value['statut']))
                        {
                            $errors['statut'] = 'Le statut du jour est obligatoire';
                           
                        }
                        
                            foreach($value['exercice'] as $ind => $val){
                            if($value['statut'] == 'entrainement') {       
                                if(empty($val['titre']))
                                {
                                    $errors['exercice_titre'] = 'Le titre de l\'exercice est obligatoire';
                                }
                                elseif(strlen($val['titre'])>100)
                                {
                                    $errors['exercice_titre'] = 'Le titre de l\'exercice ne doit pas faire plus de 100 caractères' ;
                                }
                                if(strlen($val['consigne'])>600)
                                {
                                    $errors['exercice_consigne'] = 'La consigne ne doit pas faire plus de 600 caractères' ;
                                }
                                if(empty($val['zone_musculaire']))
                                {
                                    $errors['exercice_zone'] = 'La zone musculaire est obligatoire';
                                }
                                if(strlen($val['muscle_cible'])>200)
                                {
                                    $errors['exercice_muscle'] = 'Le champs muscle ciblé ne doit pas faire plus de 200 caractères ' ;
                                }
                                if(empty($val['serie']))
                                {
                                    $errors['exercice_serie'] = 'Le nombre de séries est obligatoire';
                                }
                                elseif(!is_numeric($val['serie']))
                                {
                                    $errors['exercice_serie'] = 'Le nombre de séries n\'est pas valide';
                                }
                                if(empty($val['repetition']))
                                {
                                    $errors['exercice_repetition'] = 'Le nombre de répétitions est obligatoire';
                                }
                                elseif(!is_numeric($val['repetition']))
                                {
                                    $errors['exercice_repetition'] = 'Le nombre de répétitions n\'est pas valide';
                                }
                                if(empty($val['temps_repos']))
                                {
                                    $errors['exercice_repos'] = 'Le temps de repos est obligatoire';
                                }
                                elseif(!is_numeric($val['temps_repos']))
                                {
                                    $errors['exercice_repos'] = 'Le temps de repos n\'est pas valide';
                                }
                                if(strlen($val['detail_serie'])>600)
                                {
                                    $errors['exercice_detail'] = 'Le champ détail série ne doit pas faire plus de 600 caractères' ;
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

                foreach ($tabExercices as $exercices) {
                    foreach ($exercices as $exercice) {
                    $this->app['exercice.repository']->save($exercice);
                    }
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

        } else {
            $tabJours = $this->app['jour.repository']->findByProgramme($programme);
            
            foreach($tabJours as $index => $jour) {
                
            $tabExercices[$index] = $this->app['exercice.repository']->findByJour($jour);
            }
            
            
        }
        
        $membre = $this->app['user.manager']->getUser();
        
        return $this->render(
            'programme/edit.html.twig',
            [
                'objectifs' => $objectifs,
                'programme' => $programme,
                'jours'=> $tabJours,
                'exercices' => $tabExercices,
                'membre' => $membre
                //'i' => $index_modif
            ]
        );

    }
    
    public function listAllProgrammesAction() {
        
        $programmes = $this->app['programme.repository']->findAll();
        
        return $this->render(
            'programme/all_programmes.html.twig',
            [
                'programmes' => $programmes
            ]
        );
    }
    
    public function searchAction() {
        
        if(isset($_POST['form'])) {
        
            $motRecherche = htmlentities($_POST['motRecherche']);

            $resultat = $this->app['programme.repository']->search($motRecherche);
        }
        
        return $this->render(
            'search.html.twig',
            [
                'resultat' => $resultat
            ]
        );
    }

}

