<?php

namespace Controller;

use DateTime;

use Controller\ControllerAbstract;
use Entity\Membre;
use Repository\MembreRepository;
use Repository\ProgrammeRepository;

class MembreController extends ControllerAbstract {
    
//    Enregistrement
    public function registerAction()
    {
        
        $membre = new Membre();
        
        $errors = [];
        
        if($_POST) {
                
            if(!empty($_POST))
            {
                $membre
                    ->setPseudo($_POST['pseudo'])
                    ->setNom($_POST['nom'])
                    ->setPrenom($_POST['prenom'])
                    ->setMdp($_POST['mdp'])
                    ->setEmail($_POST['email'])    
                    ->setCivilite($_POST['civilite']) 
                    //->setAvatar($_POST['avatar'])
                    ->setStatut($membre->getStatut())
                ;
                
                if(!empty($_FILES['avatar']['name'])) {

                    $nom_photo = str_replace('', '_', $_POST['email']) . '_' . $_FILES['avatar']['name']; // ondéfinit le nom de la photo
                    // on définit le nom complet de la photo. Nous nous servirons de cette variable pour enregistrer 
                    // le chemin complet de la photo en BDD puisqu'on ne garde jamais la photo, mais le lien en bdd
                    $photo_bdd = "http://localhost/programme-sport/web/photo/avatar/$nom_photo";
                    // on définit le chemin physique du dossier de destination pour enregistrer la photo
                    $photo_dossier = $_SERVER['DOCUMENT_ROOT'] . "/programme-sport/" . "web/photo/avatar/$nom_photo";
                    // la fonction copy permet de copier la photo directement dans le dossier de destination
                    copy($_FILES['avatar']['tmp_name'], $photo_dossier);

                    $membre->setAvatar($photo_bdd);
                }
            }

            if(empty($_POST['nom']))
            {
                $errors['nom'] = 'Le nom est obligatoire';
            }
            elseif(strlen($_POST['nom'])>20)
            {
                $errors['nom'] = 'Le nom ne doit pas faire plus de 20 caractères' ;
            }
            if(empty($_POST['prenom']))
            {
                $errors['prenom'] = 'Le prénom est obligatoire';
            }
            elseif(strlen($_POST['prenom'])>20)
            {
                $errors['nom'] = 'Le prénom ne doit pas faire plus de 20 caractères' ;
            }
            if(empty($_POST['email']))
            {
                $errors['email'] = 'L\'e-mail est obligatoire';
            }
            elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            {
                $errors['email'] = 'L\'email n\'est pas valide';
            }
            elseif(!empty($this->app['membre.repository']->findByEmail($_POST['email'])))
            {
                $errors['email'] = 'Cet email est déjà utilisé';
            }
            if(empty($_POST['mdp']))
            {
                $errors['mdp'] = 'Le mot de passe est obligatoire';
            }
            elseif(!preg_match('/^[a-zA-Z0-9_-]{6,20}$/', $_POST['mdp'])) // chapeau=début chêine et $=fin de chaine
            {
                $errors['mdp'] = 'Le mot de passe doit faire entre 6 et 20 caractères et ne doit contenir que des lettres/chiffres, ou les caractères _ et -';
            }
            if(empty($_POST['mdp_confirm']))
            {
                $errors['mdp_confirm'] = 'La confirmation du mot de passe est obligatoire';
            }
            elseif($_POST['mdp'] != $_POST['mdp_confirm'])
            {
                $errors['mdp_confirm'] = 'Les mots de passe ne sont pas identiques';
            }
            if(empty($errors))
            {
                $membre->setMdp($this->app['user.manager']->encodePassword($_POST['mdp']));
                $this->app['membre.repository']->save($membre);
                $this->app['user.manager']->login($membre);

                return $this->redirectRoute('homepage');
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
            'membre/inscription.html.twig',
            [
                'membre' => $membre
            ]
            );
    }
    
//    Connexion
    public function loginAction()
    {
        $email='';
        
        if(!empty($_POST))
        {
            $email = $_POST['email'];
            
            $membre = $this->app['membre.repository']->findByEmail($email);
            
            if(!is_null($membre))
            {
                //if($this->app['user.manager']->verifyPassword($_POST['mdp'], $membre->getMdp()))
                //{
                    $this->app['user.manager']->login($membre);
                    return $this->redirectRoute('profil');                    
                //}                
            }            
            $this->addFlashMessage('Identification incorrecte', 'error');
        }
        
        return $this->render
            (
                'membre/connexion.html.twig',
                ['email'=>$email]
            );
    }
    
//      Déconnexion    
    public function logoutAction()
    {
        $this->app['user.manager']->logout();
        
        return $this->redirectRoute('homepage');
    }

//      Détails du membre connecté (Mon profil)    
    public function membreDetailAction() {
        
        $membre = $this->app['user.manager']->getUser();
        
        $programmes = $this->app['programme.repository']->findByMembre($membre);
        return $this->render(
            'membre/profil.html.twig',
            [
                'membre' => $membre,
                'programmes' => $programmes
            ]
            );
    }
    
//      Détails d'un autre profil
    public function indexAction($id) 
    {
        $membre = $this->app['membre.repository']->findById($id);
        
        $programmes = $this->app['programme.repository']->findByMembre($membre);
        
        return $this->render(
            'membre/index.html.twig',
            [
                'membre'=>$membre,
                'programmes'=>$programmes
            ]
            );
    }   
    
    
}
