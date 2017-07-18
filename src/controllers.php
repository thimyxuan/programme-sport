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


/* BACK */

$app
    ->get('/admin/liste_membres', 'admin.membre.controller:membreListAction')
    ->bind('liste_membres')
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
