<?php
/* ControllerAbstract sert à intégrer des méthodes dans chaque autres contrôleurs
 * chaque autre contrôleur héritera de celui-ci
 * Facilite pour coder les autres contrôleur car appel function par son nom, donc besoin juste du nom dans autres contrôleurs.
 */

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Session\Session;

abstract class ControllerAbstract
{
    /**
     *
     * @var Application
     */
    protected $app;
    
    /**
     *
     * @var \Twig_Environment
     */
    protected $twig;
    
    /**
     *
     * @var Session
     */
    protected $session;
    
    /**
     * 
     * ControllerAbstract constructor
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->twig = $app['twig'];
        $this->session = $app['session'];
    }
    
    /**
     * 
     * @param type $view
     * @param array $parameters
     * @return string
     */
    
    public function render($view, array $parameters = [])
    {
        return $this->twig->render($view, $parameters);
    }
    
    
    /**
     * Enregistre un message en session pour affichage
     * au prochain chargement de page
     * 
     * @param type $message
     * @param type $type
     */
    public function addFlashMessage($message, $type = 'success')
    {
        $this->session->getFlashBag()->add($type, $message);
    }
    
    public function redirectRoute($routeName, array $parameters = [])
    {
        
        return $this->app->redirect(
                $this->app['url_generator']->generate($routeName, $parameters)
        );
    }
}
