<?php

use Controller\Admin\MembreController as AdminMembreController;
use Controller\IndexController;
use Controller\MembreController;
use Repository\MembreRepository;
use Service\UserManager;
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
    // add custom globals, filters, tags, ...

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

$app['index.controller'] = function () use ($app) {
    return new IndexController($app);
};

$app['membre.controller'] = function () use ($app) 
{
    return new MembreController($app);
    
};

/* Déclaration des contrôleurs ADMIN en service */

$app['admin.membre.controller'] = function () use ($app) 
{
    return new AdminMembreController($app);
    
};

/* Déclaration des repository en service */

$app['membre.repository'] = function () use ($app) 
{
    return new MembreRepository($app['db']);    
};

return $app;
