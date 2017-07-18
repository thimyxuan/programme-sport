<?php

namespace Controller;

use Controller\ControllerAbstract;
use Entity\Programme;

class ProgrammeController extends ControllerAbstract 
{
    public function indexAction($id)
    {
        $programme = $this->app['programme.repository']->find($id);
        
    return $this->render(
           'programme/index.html.twig',
           [
           'programme'=>$programme
           ]
        );
    }
}
