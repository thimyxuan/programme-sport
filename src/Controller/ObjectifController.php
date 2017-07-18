<?php

namespace Controller;

class ObjectifController extends ControllerAbstract
{
    
    // ---------- Méthode pour afficher les objectifs dans le menu ----------
    
    public function menuAction()
    {
        $objectifs = $this->app['objectif.repository']->findAll();
        
        return $this->render(
                'objectif/menu.html.twig',
                ['objectifs'=>$objectifs]
            );
    }
    
    // ---------- Méthode pour afficher les programmes par objectif ----------
    
    public function indexAction($id)
    {
        $objectif = $this->app['objectif.repository']->find($id);
        $programmes = $this->app['programme.repository']->findByObjectif($objectif);
        
        return $this->render(
                'objectif/index.html.twig',
                [
                    'objectif'=>$objectif,
                    'programmes'=>$programmes
                ]
            );
    }
    
}
