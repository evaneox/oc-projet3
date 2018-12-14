<?php
namespace App\Core;

class Route {

    private $path;
    private $callable;
    private $matches = [];
    private $params = [];

    /**
     * Constructeur
     *
     * @param $path
     * @param $callable
     */
    public function __construct($path, $callable){
        $this->path     = trim($path, '/');
        $this->callable = $callable;
    }

    /**
     * Ajoute des conditions à une route
     *
     * @param $param
     * @param $regex
     * @return $this
     */
    public function with($param, $regex){
        $this->params[$param] = str_replace('(', '(?:', $regex);
        return $this;
    }

    /**
     * Vérifie si la route indiqué correspond à une route enregistré
     * @param $url
     * @return bool
     */
    public function match($url){
        $url = trim($url, '/');
        $path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);
        $regex = "#^$path$#i";

        if(!preg_match($regex, $url, $matches)){
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    /**
     * Vérifie les conditions d'une route
     *
     * @param $match
     * @return string
     */
    private function paramMatch($match){
        if(isset($this->params[$match[1]])){
            return '(' . $this->params[$match[1]] . ')';
        }
        return '([^/]+)';
    }

    /**
     * On redirige vers le controleur correspondant à la route trouvé,
     * ou execute la fonction associé à cette route
     *
     * @return mixed
     */
    public function call(){
        // Dans le cas de l'appel vers un controler
        if(is_string($this->callable)){
            $params = explode('@', $this->callable);
            $controller = "App\\Controller\\" . $params[0] . "Controller";
            $controller = new $controller;
            return call_user_func_array([$controller, $params[1]], $this->matches);
        }else{
            // Dans le cas de l'appel vers une fonction anonyme
            return call_user_func_array($this->callable, $this->matches);
        }

    }


}