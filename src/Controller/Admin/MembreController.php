<?php

namespace Controller\Admin;

use DateTime;

use Controller\ControllerAbstract;
use Entity\Membre;
use Repository\MembreRepository;

class MembreController extends ControllerAbstract {
    
    public function membreListAction() {
        
        $membres = $this->app['membre.repository']->findAll();
        
        return $this->render
        (
            'admin/membre/liste_membre.html.twig',
            ['membres' => $membres]
        );
    }
    
    
    //---------------- Méthode pour éditer un membre par un Admin --------------
    
    public function editAction($id = null)
    {
        if(!is_null($id))
        {
            $membre = $this->app['membre.repository']->findById($id);
        }
        else // création
        {
            $membre = new Membre();
        }
            
        $errors = [];
            
        if(!empty($_POST))
        {
            $membre
                    ->setPseudo($_POST['pseudo'])
                    ->setNom($_POST['nom'])
                    ->setPrenom($_POST['prenom'])
                    ->setMdp($_POST['mdp'])
                    ->setEmail($_POST['email'])    
                    ->setCivilite($_POST['civilite']) 
                    ->setAvatar($_POST['avatar'])
                    ->setDateEnregistrement(new \DateTime($dbMembre['date_enregistrement']))
                    ->setStatut($_POST['avatar'])
            ;            
            
            if(empty($_POST['nom']))
            {
                $errors['nom'] = 'Le nom est obligatoire';
            }
            elseif(strlen($_POST['nom'])>20)
            {
                $errors['nom'] = 'Le prénom ne doit pas faire plus de 20 caractères' ;
            }
            if(empty($_POST['prenom']))
            {
                $errors['prenom'] = 'Le prénom est obligatoire';
            }
            elseif(strlen($_POST['nom'])>20)
            {
                $errors['prenom'] = 'Le prénom ne doit pas faire plus de 20 caractères' ;
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
            if(empty($_POST['pseudo']))
            {
                $errors['pseudo'] = 'Le pseudo est obligatoire';
            }
            elseif(strlen($_POST['pseudo'])>20)
            {
                $errors['pseudo'] = 'Le pseudo ne doit pas faire plus de 20 caractères' ;
            }
            elseif(!empty($this->app['membre.repository']->findByPseudo($_POST['pseudo'])))
            {
                $errors['pseudo'] = 'Ce pseudo est déjà utilisé';
            }
            if(empty($_POST['mdp']))
            {
                $errors['mdp'] = 'Le mot de passe est obligatoire';
            }
            elseif(!preg_match('/^[a-zA-Z0-9_-]{6,20}$/', $_POST['mdp']))
            {
                $errors['mdp'] = 'Le mot de passe doit faire entre 6 et 20 caractères et ne doit contenir que des lettres, chiffres ou les caractères _ et -';
            }
            
            
            // ---- Si pas d'erreur c'est OK pour aller en bdd :
            
            if(empty($errors))
            {
                $this->app['membre.repository']->save($membre);
                $this->addFlashMessage('Le membre est enregistré !');
                return $this->redirectRoute('liste_membre');
            }
            else
            {
                $message = '<b>Le formulaire contient des erreurs</b>';
                $message .= '<br>' . implode('<br>', $errors);
                $this->addFlashMessage($message, 'error');
            }
        }
        
        return $this->render
        (
            'admin/membre/edit.html.twig',
            ['membre' => $membre]
        );
    }
    
    //---------------- Méthode pour supprimer un membre par un Admin --------------
    
    public function deleteAction($id)
    {
        $membre = $this->app['membre.repository']->findById($id);
        $this->app['membre.repository']->delete($membre);
        $this->addFlashMessage('Le membre est supprimé !');
        return $this->redirectRoute('liste_membre');
    }
    
    
}
