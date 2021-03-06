<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/* FRONT */
$app->get('/', 'index.controller:indexAction')
    ->bind('homepage') // nom de la route
;

$app->get('/programme/liste_programmes', 'programme.controller:listAllProgrammesAction')
    ->bind('liste_all_programmes') // nom de la route
;

$app
    ->get('/rubriques/menu', 'objectif.controller:menuAction')
    ->bind('objectif_menu')
;

$app
    ->get('/rubriques/{id}', 'objectif.controller:indexAction')
    ->bind('objectif')
;

$app
    ->match('/programmes/{id}', 'programme.controller:indexAction')
    ->bind('programme')
;

$app
    ->match('/search/', 'programme.controller:searchAction')
    ->bind('search')
;

$app
    ->match('/membres/{id}', 'membre.controller:indexAction')
    ->bind('membre')
;

/* USER */
$app
    ->match('/inscription', 'membre.controller:registerAction') // on prend match() car contiendra un formulaire d'inscription
    ->bind('inscription')
;

$app
    ->match('/connexion', 'membre.controller:loginAction')
    ->bind('connexion')
;

$app
    ->get('/profil', 'membre.controller:membreDetailAction')
    ->bind('profil')
;

$app
    ->match('/programme/edit/{id}', 'programme.controller:editAction')
    ->value('id', null)
    ->bind('programme_edit')
;

$app
    ->match('/deconnexion', 'membre.controller:logoutAction')
    ->bind('logout')
;

/* BACK */

// crée un groupe de routes pour la partie Admin
$admin = $app['controllers_factory'];
// toutes les toures définies dans le groupe $admin auront le préfixe /admin
$app->mount('/admin',$admin);


// protection de l'accès au backoffice
/*
$admin->before(function() use ($app)
{
    if(!$app['user.manager']->isAdmin())
    {
        $app->abort(403, 'Accès refusé');
    }
});*/

$admin
    ->get('/liste_membre', 'admin.membre.controller:membreListAction')
    ->bind('liste_membre')
;

$admin
    ->match('/membres/edit/{id}', 'admin.membre.controller:editAction') 
    ->value('id', null) 
    ->bind('admin_membre_edit')
;

$admin
    ->get('/membre/delete/{id}', 'admin.membre.controller:deleteAction')
    ->bind('admin_membre_delete')
;

$admin
    ->get('/liste_objectif', 'admin.objectif.controller:ListAction')
    ->bind('liste_objectif')
;

$admin
    ->match('/objectifs/edit/{id}', 'admin.objectif.controller:editAction') 
    ->value('id', null) 
    ->bind('admin_objectif_edit')
;

$admin
    ->get('/objectif/delete/{id}', 'admin.objectif.controller:deleteAction')
    ->bind('admin_objectif_delete')
;

$admin
    ->get('/liste_programme', 'admin.programme.controller:ListAction')
    ->bind('liste_programme')
;

$admin
    ->get('/programme/delete/{id}', 'admin.programme.controller:deleteAction')
    ->bind('admin_programme_delete')
;

// -----------------

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
