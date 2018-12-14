<?php
namespace App\Core;

class Router {

    private $url;
    private $routes = [];
    private $namedRoutes = [];

    /**
     * Constructeur
     *
     * @param $url
     */
    public function __construct($url){
        $this->url = $url;
    }

    /**
     * Declaration d'une route de type GET
     *
     * @param $path
     * @param $callable
     * @return Route
     */
    public function get($path, $callable){
        return $this->add($path, $callable, 'GET');
    }

    /**
     * Declaration d'une route de type POST
     *
     * @param $path
     * @param $callable
     * @return Route
     */
    public function post($path, $callable){
        return $this->add($path, $callable, 'POST');
    }

    /**
     * Enregistrement d'une nouvelle route
     *
     * @param $path
     * @param $callable
     * @param $method
     * @return Route
     */
    private function add($path, $callable, $method){
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;
        return $route;
    }

    /**
     * On compare la route de l'utilisateur avec les routes enregistrées,
     * si aucune route ne correspond alors on retourne une erreur 404
     *
     * @return mixed
     */
    public function run(){
        if(isset($this->routes[$_SERVER['REQUEST_METHOD']])){
            foreach($this->routes[$_SERVER['REQUEST_METHOD']] as $route){
                if($route->match($this->url)){
                    return $route->call();
                }
            }
        }
        // Retourne une erreur 404
        header('Location: '.BASE_URL.'/404');
    }

}