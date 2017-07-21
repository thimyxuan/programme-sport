<?php

namespace Controller\Admin;

use Controller\ControllerAbstract;
use Entity\Objectif;
use Repository\ProgrammeRepository;

class ProgrammeController extends ControllerAbstract
{
    
    //---------------- Méthode pour afficher la liste des programmes --------------
    
    public function ListAction() {
        
        $programmes = $this->app['programme.repository']->findAll();
        
        return $this->render
        (
            'admin/programme/liste_programme.html.twig',
            ['programmes' => $programmes]
        );
    }
    
    //---------------- Méthode pour supprimer un programme par un Admin --------------
    
    public function deleteAction($id)
    {
        $programme = $this->app['programme.repository']->find($id);
        $this->app['programme.repository']->delete($programme);
        $this->addFlashMessage('Le programme est supprimé !');
        return $this->redirectRoute('liste_programme');
    }
}
