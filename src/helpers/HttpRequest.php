<?php

namespace Helpers;

class HttpRequest
{
    public string $method;
    public array $route;
    /**
     * Récupère la méthode (ex : GET, POST, etc ...)
     * et les différentes partie de la route sous forme de tableau
     * (ex : ["product", 1])
     */
    private function __construct()
    {
        $this->method = $_SERVER["REQUEST_METHOD"];
        
        $request = filter_var(trim($_SERVER["REQUEST_URI"], '/'), FILTER_SANITIZE_URL);
            
        $request = trim($request,'/');
        $request = explode('/',$request);
        if ($_ENV['current'] == "dev" && $_SERVER['SERVER_NAME'] == 'localhost') {
            array_shift($request);
        }
    
        $this->route = $request;
    }

    private static $instance;
    /**
     * Crée une instance de HttpRequest si $instance est null
     * puis retourne cette instance
     */
    public static function instance(): HttpRequest
    {
        if(is_null(self::$instance)) {
            self::$instance = new HttpRequest();  
          }
        return self::$instance;
    }
}
