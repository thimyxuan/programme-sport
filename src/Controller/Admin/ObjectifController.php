<?php

namespace Controller\Admin;

use Controller\ControllerAbstract;
use Entity\Objectif;
use Repository\ObjectifRepository;

class ObjectifController extends ControllerAbstract {
    
    public function ListAction() {
        
        $objectifs = $this->app['objectif.repository']->findAll();
        
        return $this->render
        (
            'admin/objectif/liste_objectif.html.twig',
            ['objectifs' => $objectifs]
        );
    }
    
    public function editAction($id = null)
    {
        if(!is_null($id))
        {
            $objectif = $this->app['objectif.repository']->find($id);
        }
        else // création
        {
            $objectif = new Objectif();
        }
            
        $errors = [];
            
        if(!empty($_POST))
        {
            $objectif
                    ->setTitre($_POST['titre'])
            ;           
            
            if(empty($_POST['titre']))
            {
                $errors['titre'] = 'Le titre est obligatoire';
            }
             elseif(strlen($_POST['titre'])>100)
            {
                $errors['titre'] = 'Le titre ne doit pas faire plus de 100 caractères' ;
            }
            
            // ---- Si pas d'erreur c'est OK pour aller en bdd :
            
            if(empty($errors))
            {
                $this->app['objectif.repository']->save($objectif);
                $this->addFlashMessage('L\'objectif est enregistré !');
                return $this->redirectRoute('liste_objectif');
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
            'admin/objectif/edit.html.twig',
            ['objectif' => $objectif]
        );
    }
    
    public function deleteAction($id)
    {
        $objectif = $this->app['objectif.repository']->find($id);
        $this->app['objectif.repository']->delete($objectif);
        $this->addFlashMessage('L\'objectif est supprimé !');
        return $this->redirectRoute('liste_objectif');
    }
}
