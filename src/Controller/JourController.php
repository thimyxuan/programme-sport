<?php

namespace Controller;

use Entity\Jour;
use Controller\ControllerAbstract;


class JourController extends ControllerAbstract
{
    
    public function editAction($id = null)
    {
        if (!is_null($id)) { // modification
            $jour = $this->app['jour.repository']->find($id);
        } else { // création
            $jour = new Jour();
        }
        
        $errors = [];
        
        if (!empty($_POST)) {
            
            $jour->setStatut($_POST['statut']);
            
            if(empty($_POST['statut'])) {
                $errors['statut'] = 'Le statut est obligatoire';
            }
            
            if (empty($errors)) {
                $this->app['jour.repository']->save($jour);
                $this->addFlashMessage('Le statut est enregistré');
                
                return $this->redirectRoute('controller_jours');
            } else {
                $message = '<b>Le Formulaire contient des erreurs</b>';
                $message .= '<br>' . implode('<br>', $errors);
                $this->addFlashMessage($message, 'error');
            }
        }
        
        return $this->render(
            'jour/edit.html.twig',
            [
                'jour' => $jour
            ]
        );
    }
    
    public function deleteAction($id)
    {
        $jour = $this->app['jour.repository']->find($id);
        
        $this->app['jour.repository']->delete($jour);
        
        $this->addFlashMessage('Le statut est supprimée');
        
        return $this->redirectRoute('admin_jours');
    }
}
