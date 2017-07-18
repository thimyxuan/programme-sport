<?php

use Controller\Admin\MembreController as AdminMembreController;
use Controller\IndexController;
use Controller\MembreController;
use Repository\MembreRepository;
use Service\UserManager;
use Controller\JourController;
use Repository\JourRepository;
use Controller\ObjectifController;
use Controller\ProgrammeController;
use Repository\ObjectifRepository;
use Repository\ProgrammeRepository;
use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app['twig'] = $app->extend('twig', function ($twig, $app) {
    

    return $twig;
});

$app->register(
        new DoctrineServiceProvider(),
        [
            'db.options' => [
                'driver' => 'pdo_mysql',
                'host' => 'localhost',
                'dbname' => 'test_projet',
                'user' => 'root',
                'password' => '',
                'charset' => 'utf8'
            ]
        ]
);

// pour pouvoir utiliser le gestionnaire de session de Symfony
// rien à ajouter par composer
$app->register(new SessionServiceProvider());

$app['user.manager'] = function () use ($app) 
{
    return new UserManager($app['session']);
};


/* Déclaration des contrôleurs en service */


// FRONT
$app['index.controller'] = function () use ($app) {
    return new IndexController($app);
};

$app['programme.controller'] = function () use ($app) 
{
    return new ProgrammeController($app);
};

$app['jour.controller'] = function () use ($app) {
    return new JourController($app);
};

$app['objectif.controller'] = function () use ($app) 
{
    return new ObjectifController($app);
};


// USER
$app['membre.controller'] = function () use ($app) 
{
    return new MembreController($app);    
};



// BACK
$app['admin.membre.controller'] = function () use ($app) 
{
    return new AdminMembreController($app);
    
};

/* Déclaration des repository en service */

$app['membre.repository'] = function () use ($app) 
{
    return new MembreRepository($app['db']);
};

$app['jour.repository'] = function () use ($app) {
    return new JourRepository($app['db']);
};

$app['programme.repository'] = function () use ($app) 
{
    return new ProgrammeRepository($app['db']);    
};

$app['objectif.repository'] = function () use ($app) 
{
    return new ObjectifRepository($app['db']);    
};

return $app;
