<?php



class Router
{
    public function __construct()
    {
        Autoloader::register();
    }
    
    public function setRoute($route, $action)
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            $route = preg_replace("/(^\/)|(\/$)/", "", $route);
            $reqUri = preg_replace("/(^\/)|(\/$)/", "", $_SERVER['REQUEST_URI']);
            $reqUri = str_replace(strstr($reqUri, "?"), "", $reqUri);
        } else {
            $reqUri = "/";
        }

        if ($reqUri == $route) {
            $routerAction = explode("@", $action);
            $fileToInclude = './Controller/' . str_replace("Controller", "", $routerAction[0]) . '.php';

            include($fileToInclude);

            $className = 'Controller\\' . $routerAction[0];
            $method = $routerAction[1];
            $instance = new $className();
            $instance->$method();
            exit;
        }
    }

    public function notFound($file)
    {
        include($file);
        exit();
    }
}
