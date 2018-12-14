<?php


namespace App\Controller;


class ErrorController extends Controller{

    /**
     * Gestion de l'affichage de la page d'erreur
     */
    public function notFound(){
        header("HTTP/1.0 404 Not Found");
        echo $this->twig->render('not-found.php');
    }

}