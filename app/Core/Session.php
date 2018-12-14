<?php


namespace App\Core;

class Session {

    /**
     * Constructeur
     */
    public function __construct(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Récupération de la session de l'utilisateur
     * @return int|null
     */
    public function getAuth(){
        if(!empty($_COOKIE["auth.session"])){
            $_SESSION["auth.session"] = (int) $_COOKIE["auth.session"];
        }

        return !empty($_SESSION["auth.session"]) ? (int) $_SESSION["auth.session"] : null;
    }

    /**
     * Creation d'une nouvelle session
     * @param $auth
     * @param $remember
     */
    public function setAuth($auth, $remember){
        $_SESSION["auth.session"] = $auth;

        if($remember){
            setcookie("auth.session", $auth, time() + ( 3600 * 24 ) );
        }
    }

    /**
     * Gére la destruction de la session
     */
    public function deleteAuth(){

        if(!empty($_COOKIE["auth.session"])){
            setcookie("auth.session", "", time()-3600);
        }

        if (!empty($_SESSION))
        {
            $_SESSION = array();
            session_unset();
            session_destroy();
        }
    }

    /**
     * Enregistrement d'un message flash
     *
     * @param $varname
     * @param $value
     */
    public function setFlash($varname, $value){
        $_SESSION['flash'][$varname] = $value;
    }

    /**
     * Récupération d'un message flash
     *
     * @param $varname
     * @return mixed
     */
    public function getFlash($varname){

        // On retourne le message flash si il existe
        // sans oublier de le détruire pour eviter la propogation à d'autre page
        if(isset($_SESSION['flash'][$varname])){
            $message = $_SESSION['flash'][$varname];
            unset($_SESSION['flash'][$varname]);
            return $message;
        }else{
            return null;
        }

    }


}