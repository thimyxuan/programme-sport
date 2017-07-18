<?php

namespace Controller\Admin;

use Controller\ControllerAbstract;

class MembreController extends ControllerAbstract {
    
    public function membreListAction() {
        
        $membres = $this->app['membre.repository']->findAll();
        
        return $this->render
        (
            'admin/membre/liste_membre.html.twig',
            ['membres' => $membres]
        );
    }
}
