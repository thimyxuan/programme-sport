<?php

use Controller\IndexController;
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

/* Déclaration des contrôleurs en service */
$app['index.controller'] = function () use ($app) {
    return new IndexController($app);
};

$app['jour.controller'] = function () use ($app) {
    return new JourController($app);
};

$app['jour.repository'] = function () use ($app) {
    return new JourRepository($app['db']);
};

$app['programme.controller'] = function () use ($app) 
{
    return new ProgrammeController($app);
};

$app['programme.repository'] = function () use ($app) 
{
    return new ProgrammeRepository($app['db']);    
};

$app['objectif.controller'] = function () use ($app) 
{
    return new ObjectifController($app);
};

$app['objectif.repository'] = function () use ($app) 
{
    return new ObjectifRepository($app['db']);    
};

return $app;
