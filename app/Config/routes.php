<?php

/******************************************
 * GENERAL
 *********************/
    /* ==================================
      Gestion des erreurs
    =========================================*/
    $router->get('/404', "Error@notFound");

/******************************************
 * FRONT-END
 *********************/
    /* ==================================
      Index du blog
    =========================================*/
    $router->get('/', "Article@index");

    /* ==================================
      Vue d'un article
    =========================================*/
    $router->get('/article/:id/:slug', "Article@view")->with('id', '[0-9]+')->with('slug','[a-z\-0-9]+');

    /* ==================================
      Ajout d'un commentaire
    =========================================*/
    $router->post('/comment/add/:id', "Comment@add")->with('id', '[0-9]+');

    /* ==================================
      Signalement d'un commentaire
    =========================================*/
    $router->get('/comment/report/:id', "Comment@report")->with('id', '[0-9]+');

    /* ==================================
      Déconnexion du compte
    =========================================*/


/******************************************
 * BACK-END
 *********************/

    /* ==================================
      Index administration
    =========================================*/
    $router->get('/admin', "Admin@index");

    /* ==================================
      Page de connexion
    =========================================*/
    $router->get('/admin/login', "Admin@login");
    $router->post('/admin/login', "Admin@login");

    /* ==================================
      Déconnexion
    =========================================*/
    $router->get('/admin/logout', "Admin@logout");

    /* ==================================
      Ajout d'un article
    =========================================*/
    $router->get('/admin/article/add', "Admin@addArticle");
    $router->post('/admin/article/add', "Article@add");

    /* ==================================
      Edition d'un article
    =========================================*/
    $router->get('/admin/article/edit/:id', "Admin@editArticle");
    $router->post('/admin/article/edit/:id', "Article@edit");

    /* ==================================
      Liste des articles et commentaires
    =========================================*/
    $router->get('/admin/list/:element', "Admin@listOfElement");

    /* ==================================
      Mise à jour des articles
    =========================================*/
    $router->get('/admin/article/:id/:action', "Article@update")->with('id', '[0-9]+');

    /* ==================================
      Mise à jour des commentaire
    =========================================*/
    $router->get('/admin/comment/:id/:action', "Comment@update")->with('id', '[0-9]+');

    /* ==================================
      Supression articles et purge de la corbeille
    =========================================*/
    $router->get('/admin/article/delete/:id', "Article@delete")->with('id', '[0-9]+');
    $router->get('/admin/article/trash/purge', "Article@purgeTrash")->with('id', '[0-9]+');

    /* ==================================
      Supression commentaires et purge de la corbeille
    =========================================*/
    $router->get('/admin/comment/delete/:id', "Comment@delete")->with('id', '[0-9]+');
    $router->get('/admin/comment/trash/purge', "Comment@purgeTrash")->with('id', '[0-9]+');
