<?php


namespace App\Controller;

use \Twig_Loader_Filesystem;
use \Twig_Environment;
use \Twig_Extension_Debug;

use \App\Core\Session;

use App\Model\ArticleManager;
use \App\Model\UserManager;
use \App\Model\CommentManager;


class Controller {

    /**
     * Constructeur
     */
    public function __construct(){

        // Création des instances des managers
        $this->articleManager = new ArticleManager();
        $this->commentManager = new CommentManager();
        $this->userManager    = new UserManager();
        $this->session        = new Session();

        // Récupération de la session du visiteur
        $this->user =  $this->userManager->getByID($this->session->getAuth());


        // Création d'un instance twig (vue)
        $loader = new Twig_Loader_Filesystem( APP. 'View/');
        $this->twig = new Twig_Environment($loader, array(
            'cache' => false,
            'debug' => true,
        ));
        $this->twig->addExtension(new Twig_Extension_Debug());
        $this->twig->addGlobal('baseUrl', BASE_URL);
        $this->twig->addGlobal('auth', $this->user);
        $this->twig->addGlobal('errors', $this->session->getFlash('errors'));
        $this->twig->addGlobal('message', $this->session->getFlash('message'));
    }

    /**
     * Vérifie si l'utilisateur à les permissions suffisante
     * pour accéder aux pages d'administration
     *
     * @return bool
     */
    protected function havePermission(){
        return (!is_null($this->user) AND $this->user->isAdmin()) ? true : false;
    }
}